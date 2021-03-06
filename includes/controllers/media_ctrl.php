<?php
// Controller for the Media Menu
//
// webtrees: Web based Family History software
// Copyright (C) 2010 webtrees development team.
//
// Derived from PhpGedView
// Copyright (C) 2002 to 2009 PGV Development Team.  All rights reserved.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// @version $Id$

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('WT_MEDIA_CTRL_PHP', '');

require_once WT_ROOT.'includes/functions/functions_print_facts.php';
require_once WT_ROOT.'includes/controllers/basecontrol.php';
require_once WT_ROOT.'includes/classes/class_menu.php';
require_once WT_ROOT.'includes/classes/class_gedcomrecord.php';
require_once WT_ROOT.'includes/functions/functions_import.php';

class MediaController extends BaseController{
	var $mid;
	var $mediaobject;
	var $show_changes=true;

	function init() {
		global $MEDIA_DIRECTORY, $USE_MEDIA_FIREWALL, $GEDCOM;

		$filename = safe_GET('filename');
		$this->mid = safe_GET_xref('mid');

		if ($USE_MEDIA_FIREWALL && empty($filename) && empty($this->mid)) {
			// this section used by mediafirewall.php to determine what media file was requested

			if (isset($_SERVER['REQUEST_URI'])) {
				// NOTE: format of this server variable:
				// Apache: /phpGedView/media/a.jpg
				// IIS:    /phpGedView/mediafirewall.php?404;http://server/phpGedView/media/a.jpg
				$requestedfile = $_SERVER['REQUEST_URI'];
				// urldecode the request
				$requestedfile = rawurldecode($requestedfile);
				// make sure the requested file is in the media directory
				if (strpos($requestedfile, $MEDIA_DIRECTORY) !== false) {
					// strip off the wt directory and media directory from the requested url so just the image information is left
					$filename = substr($requestedfile, strpos($requestedfile, $MEDIA_DIRECTORY) + strlen($MEDIA_DIRECTORY) - 1);
					// strip the ged param if it was passed on the querystring
					// would be better if this could remove any querystring, but '?' are valid in unix filenames
					if (strpos($filename, '?ged=') !== false) {
						$filename = substr($filename, 0, strpos($filename, '?ged='));
					}
					// if user requested a thumbnail, lookup permissions based on the original image
					$filename = str_replace('/thumbs', '', $filename);
				} else {
					// the MEDIA_DIRECTORY of the current GEDCOM was not part of the requested file
					// either the requested file is in a different GEDCOM (with a different MEDIA_DIRECTORY)
					// or the Media Firewall is being called from outside the MEDIA_DIRECTORY
					// this condition can be detected by the media firewall by calling controller->getServerFilename()
				}
			}
		}

		//Checks to see if the File Name ($filename) exists
		if (!empty($filename)) {
			//If the File Name ($filename) is set, then it will call the method to get the Media ID ($this->mid) from the File Name ($filename)
			$this->mid = get_media_id_from_file($filename);
			if (!$this->mid) {
				//This will set the Media ID to be false if the File given doesn't match to anything in the database
				$this->mid = false;
				// create a very basic gedcom record for this file so that the functions of the media object will work
				// this is used by the media firewall when requesting an object that exists in the media firewall directory but not in the gedcom
				$this->mediaobject = new Media("0 @"."0"."@ OBJE\n1 FILE ".$filename);
			}
		}

		//checks to see if the Media ID ($this->mid) is set. If the Media ID isn't set then there isn't any information avaliable for that picture the picture doesn't exist.
		if ($this->mid) {
			//This creates a Media Object from the getInstance method of the Media Class. It takes the Media ID ($this->mid) and creates the object.
			$this->mediaobject = Media::getInstance($this->mid);
			//This sets the controller ID to be the Media ID
			$this->pid = $this->mid;
		}

		if (is_null($this->mediaobject)) return false;
		$this->mediaobject->ged_id=WT_GED_ID; // This record is from a file

		$this->mid=$this->mediaobject->getXref(); // Correct upper/lower case mismatch

		//-- perform the desired action
		switch($this->action) {
		case 'addfav':
			if (WT_USER_ID && !empty($_REQUEST['gid']) && array_key_exists('user_favorites', WT_Module::getActiveModules())) {
				$favorite = array(
					'username' => WT_USER_NAME,
					'gid'      => $_REQUEST['gid'],
					'type'     => 'OBJE',
					'file'     => WT_GEDCOM,
					'url'      => '',
					'note'     => '',
					'title'    => ''
				);
				user_favorites_WT_Module::addFavorite($favorite);
			}
			unset($_GET['action']);
			break;
		case 'accept':
			if (WT_USER_CAN_ACCEPT) {
				accept_all_changes($this->pid, WT_GED_ID);
				$this->show_changes=false;
				$this->accept_success=true;
				//-- check if we just deleted the record and redirect to index
				$mediarec = find_media_record($this->pid, WT_GED_ID);
				if (empty($mediarec)) {
					header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH);
					exit;
				}
				$this->mediaobject = new Media($mediarec);
			}
			unset($_GET['action']);
			break;
		case 'undo':
			if (WT_USER_CAN_ACCEPT) {
				reject_all_changes($this->pid, WT_GED_ID);
				$this->show_changes=false;
				$this->accept_success=true;
				$mediarec = find_media_record($this->pid, WT_GED_ID);
				//-- check if we just deleted the record and redirect to index
				if (empty($mediarec)) {
					header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH);
					exit;
				}
				$this->mediaobject = new Media($mediarec);
			}
			unset($_GET['action']);
			break;
		}

		}
	}

	/**
	* return the title of this page
	* @return string the title of the page to go in the <title> tags
	*/
	function getPageTitle() {
		if ($this->mediaobject) {
			return $this->mediaobject->getFullName()." - ".i18n::translate('Multimedia Information');
		} else {
			return i18n::translate('Unable to find record with ID');
		}
	}

	function canDisplayDetails() {
		return $this->mediaobject->canDisplayDetails();
	}

	/**
	* get edit menu
	*/
	function getEditMenu() {
		global $TEXT_DIRECTION, $WT_IMAGES, $GEDCOM, $SHOW_GEDCOM_RECORD;

		if (!$this->mediaobject) return null;
		if ($TEXT_DIRECTION=="rtl") {
			$ff="_rtl";
		} else {
			$ff="";
		}

		$links = get_media_relations($this->pid);
		$linktoid = "new";
		foreach ($links as $linktoid => $type) {
			break; // we're only interested in the key of the first list entry
		}

		// edit menu
		$menu = new Menu(i18n::translate('Edit'));
		$menu->addIcon('edit_media');
		$menu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}", 'icon_large_gedcom');

		if (WT_USER_CAN_EDIT) {
			$submenu = new Menu(i18n::translate('Edit media'));
			$submenu->addOnclick("window.open('addmedia.php?action=editmedia&pid={$this->pid}', '_blank', 'top=50,left=50,width=600,height=500,resizable=1,scrollbars=1')");
			$submenu->addIcon('edit_media');
			$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}");
			$menu->addSubmenu($submenu);

			// main link displayed on page
			if (WT_USER_GEDCOM_ADMIN && file_exists(WT_ROOT.'modules/GEDFact_assistant/_MEDIA/media_1_ctrl.php')) {
				$submenu = new Menu(i18n::translate('Manage links'));
			} else {
				$submenu = new Menu(i18n::translate('Set link'));
			}

			// GEDFact assistant Add Media Links =======================
			if (WT_USER_GEDCOM_ADMIN && file_exists(WT_ROOT.'modules/GEDFact_assistant/_MEDIA/media_1_ctrl.php')) {
				$submenu->addOnclick("return ilinkitem('".$this->pid."','manage');");
				$submenu->addIcon('edit_media');
				$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}");
				// Do not print ssubmunu
			} else {
				$submenu->addOnclick("return ilinkitem('".$this->pid."','person');");
				$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}");

				$ssubmenu = new Menu(i18n::translate('To Person'));
				$ssubmenu->addOnclick("return ilinkitem('".$this->pid."','person');");
				$ssubmenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}");
				$submenu->addSubMenu($ssubmenu);

				$ssubmenu = new Menu(i18n::translate('To Family'));
				$ssubmenu->addOnclick("return ilinkitem('".$this->pid."','family');");
				$ssubmenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}");
				$submenu->addSubMenu($ssubmenu);

				$ssubmenu = new Menu(i18n::translate('To Source'));
				$ssubmenu->addOnclick("return ilinkitem('".$this->pid."','source');");
				$ssubmenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}");
				$submenu->addSubMenu($ssubmenu);
			}
			$menu->addSubmenu($submenu);

			$menu->addSeparator();
		}

		// show/hide changes
		if (find_updated_record($this->pid, WT_GED_ID)!==null) {
			if (!$this->show_changes) {
				$label = i18n::translate('This record has been updated.  Click here to show changes.');
				$link = "mediaviewer.php?mid={$this->pid}&amp;show_changes=yes";
			} else {
				$label = i18n::translate('Click here to hide changes.');
				$link = "mediaviewer.php?mid={$this->pid}&samp;how_changes=no";
			}
			$submenu = new Menu($label, $link);
			$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}");
			$menu->addSubmenu($submenu);

			if (WT_USER_CAN_ACCEPT) {
				$submenu = new Menu(i18n::translate('Undo all changes'), "mediaviewer.php?mid={$this->pid}&amp;action=undo");
				$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}");
				$submenu->addIcon('notes');
				$menu->addSubmenu($submenu);
				$submenu = new Menu(i18n::translate('Approve all changes'), "mediaviewer.php?mid={$this->pid}&amp;action=accept");
				$submenu->addIcon('notes');
				$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}");
				$menu->addSubmenu($submenu);
			}

			$menu->addSeparator();
		}

		// edit/view raw gedcom
		if (WT_USER_IS_ADMIN || $SHOW_GEDCOM_RECORD) {
			$submenu = new Menu(i18n::translate('Edit raw GEDCOM record'));
			$submenu->addOnclick("return edit_raw('".$this->pid."');");
			$submenu->addIcon('gedcom');
			$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}");
			$menu->addSubmenu($submenu);
		} elseif ($SHOW_GEDCOM_RECORD) {
			$submenu = new Menu(i18n::translate('View GEDCOM Record'));
			$submenu->addIcon('gedcom');
			if ($this->show_changes && WT_USER_CAN_EDIT) {
				$submenu->addOnclick("return show_gedcom_record('new');");
			} else {
				$submenu->addOnclick("return show_gedcom_record();");
			}
			$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}");
			$menu->addSubmenu($submenu);
		}

		// delete
		if (WT_USER_GEDCOM_ADMIN) {
			$submenu = new Menu(i18n::translate('Remove object'), "admin_media.php?action=removeobject&amp;xref=".$this->pid);
			$submenu->addOnclick("return confirm('".i18n::translate('Are you sure you want to remove this object from the database?')."')");
			$submenu->addIcon('edit_media');
			$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}");
			$menu->addSubmenu($submenu);
		}

		// add to favorites
		$submenu = new Menu(i18n::translate('Add to My Favorites'), "mediaviewer.php?action=addfav&amp;mid={$this->mid}&amp;gid={$this->mid}");
		$submenu->addIcon('favorites');
		$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}", "submenu{$ff}");
		$menu->addSubmenu($submenu);

		//-- get the link for the first submenu and set it as the link for the main menu
		if (isset($menu->submenus[0])) {
			$link = $menu->submenus[0]->onclick;
			$menu->addOnclick($link);
		}
		return $menu;
	}

	/**
	* check if we can show the gedcom record
	* @return boolean
	*/
	function canShowGedcomRecord() {
		global $SHOW_GEDCOM_RECORD;
		if ($SHOW_GEDCOM_RECORD && $this->mediaobject->canDisplayDetails())
			return true;
	}

	/**
	* return a list of facts
	* @return array
	*/
	function getFacts($includeFileName=true) {
		global $GEDCOM, $MEDIA_TYPES;

		$ignore = array("TITL","FILE");
		if ($this->show_changes) {
			$ignore = array();
		} elseif (WT_USER_GEDCOM_ADMIN) {
			$ignore = array("TITL");
		}

		$facts = $this->mediaobject->getFacts($ignore);
		sort_facts($facts);
		//if ($includeFileName) $facts[] = new Event("1 FILE ".$this->mediaobject->getFilename());
		$mediaType = $this->mediaobject->getMediatype();
		if (array_key_exists($mediaType, $MEDIA_TYPES)) $facts[] = new Event("1 TYPE ".$MEDIA_TYPES[$mediaType]);
		else $facts[] = new Event("1 TYPE ".i18n::translate('Other'));

		if ($this->show_changes && ($newrec=find_updated_record($this->pid, WT_GED_ID))!==null) {
			$newmedia = new Media($newrec);
			$newfacts = $newmedia->getFacts($ignore);
			if ($includeFileName) $newfacts[] = new Event("1 TYPE ".$MEDIA_TYPES[$mediaType]);
			$newfacts[] = new Event("1 FORM ".$newmedia->getFiletype());
			$mediaType = $newmedia->getMediatype();
			if (array_key_exists($mediaType, $MEDIA_TYPES)) $newfacts[] = new Event("1 TYPE ".$mediaType);
			else $newfacts[] = new Event("1 TYPE ".i18n::translate('Other'));
			//-- loop through new facts and add them to the list if they are any changes
			//-- compare new and old facts of the Personal Fact and Details tab 1
			for ($i=0; $i<count($facts); $i++) {
				$found=false;
				foreach ($newfacts as $indexval => $newfact) {
					if (trim($newfact->gedcomRecord)==trim($facts[$i]->gedcomRecord)) {
						$found=true;
						break;
					}
				}
				if (!$found) {
					$facts[$i]->gedcomRecord.="\nWT_OLD\n";
				}
			}
			foreach ($newfacts as $indexval => $newfact) {
				$found=false;
				foreach ($facts as $indexval => $fact) {
					if (trim($fact->gedcomRecord)==trim($newfact->gedcomRecord)) {
						$found=true;
						break;
					}
				}
				if (!$found) {
					$newfact->gedcomRecord.="\nWT_NEW\n";
					$facts[]=$newfact;
				}
			}
		}

		if ($this->mediaobject->fileExists()) {
			// get height and width of image, when available
			if ($this->mediaobject->getWidth()) {
				$facts[] = new Event("1 EVEN " . '<span dir="ltr">' . $this->mediaobject->getWidth()." x ".$this->mediaobject->getHeight() . '</span>' . "\n2 TYPE image_size");
			}
			//Prints the file size
			//Rounds the size of the image to 2 decimal places
			$facts[] = new Event("1 EVEN " . '<span dir="ltr">' . round($this->mediaobject->getFilesizeraw()/1024, 2)." kb" . '</span>' . "\n2 TYPE file_size");
		}

		sort_facts($facts);
		return $facts;
	}

	/**
	* get the relative file path of the image on the server
	* @return string
	*/
	function getLocalFilename() {
		if ($this->mediaobject) {
			return $this->mediaobject->getLocalFilename();
		} else {
			return false;
		}
	}

	/**
	* get the file name on the server
	* @return string
	*/
	function getServerFilename() {
		if ($this->mediaobject) {
			return $this->mediaobject->getServerFilename();
		} else {
			return false;
		}
	}
}
