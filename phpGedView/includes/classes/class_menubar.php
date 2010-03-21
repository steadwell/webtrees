<?php
/**
* System for generating menus.
*
* webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
* Copyright (C) 2002 to 2010 PGV Development Team. All rights reserved.
*
* Modifications Copyright (c) 2010 Greg Roach
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*
* @package webtrees
* @version $Id$
*/

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_CLASS_MENUBAR_PHP', '');

require_once PGV_ROOT.'includes/classes/class_menu.php';
require_once 'includes/classes/class_module.php';

class MenuBar
{
	/**
	* get the home menu
	* @return Menu the menu item
	*/
	static function getHomeMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $HOME_SITE_URL, $HOME_SITE_TEXT;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		//-- main home menu item
		$menu = new Menu($HOME_SITE_TEXT, $HOME_SITE_URL, "down");
		if (!empty($PGV_IMAGES["home"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["home"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_home");
		return $menu;
	}

	/**
	* get the menu with links to the gedcom portals
	* @return Menu the menu item
	*/
	static function getGedcomMenu() {
		global $ALLOW_CHANGE_GEDCOM, $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES;

		if ($TEXT_DIRECTION=='rtl') $ff='_rtl'; else $ff='';
		//-- main menu
		$menu = new Menu(i18n::translate('Welcome page'), 'index.php?ctype=gedcom', 'down');
		if (!empty($PGV_IMAGES['gedcom']['large']))
			$menu->addIcon($PGV_IMAGE_DIR.'/'.$PGV_IMAGES['gedcom']['large']);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", 'icon_large_gedcom');
		//-- gedcom list
		$gedcom_titles=get_gedcom_titles();
		if ($ALLOW_CHANGE_GEDCOM && count($gedcom_titles)>1) {
			foreach ($gedcom_titles as $gedcom_title) {
				$submenu = new Menu(PrintReady($gedcom_title->gedcom_title, true), encode_url('index.php?ctype=gedcom&ged='.$gedcom_title->gedcom_name));
				if (!empty($PGV_IMAGES['gedcom']['small'])) {
					$submenu->addIcon($PGV_IMAGE_DIR.'/'.$PGV_IMAGES['gedcom']['small']);
				}
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", '', 'icon_small_gedcom');
				$menu->addSubmenu($submenu);
			}
		}
		//-- Welcome Menu customization
		$filename = PGV_ROOT.'includes/extras/custom_welcome_menu.php';
		if (file_exists($filename)) {
			require $filename;
		}

		return $menu;
	}

	/**
	* get the mygedview menu
	* @return Menu the menu item
	*/
	static function getMygedviewMenu() {
		global $MEDIA_DIRECTORY, $MULTI_MEDIA;
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES;
		global $PEDIGREE_FULL_DETAILS, $PEDIGREE_LAYOUT;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";

		$showFull = ($PEDIGREE_FULL_DETAILS) ? 1 : 0;
		$showLayout = ($PEDIGREE_LAYOUT) ? 1 : 0;

		if (!PGV_USER_ID) {
			return new Menu('', '', '');
		}

		//-- main menu
		$menu = new Menu(i18n::translate('My Page'), "index.php?ctype=user", "down");
		if (!empty($PGV_IMAGES["mygedview"]["large"])) {
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["mygedview"]["large"]);
		} elseif (!empty($PGV_IMAGES["gedcom"]["large"])) {
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["large"]);
		}
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_mygedview");

		//-- mygedview submenu
		$submenu = new Menu(i18n::translate('MyGedView'), "index.php?ctype=user");
		if (!empty($PGV_IMAGES["mygedview"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["mygedview"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_mygedview");
		$menu->addSubmenu($submenu);
		//-- editaccount submenu
		if (get_user_setting(PGV_USER_ID, 'editaccount')) {
			$submenu = new Menu(i18n::translate('My Account'), "edituser.php");
			if (!empty($PGV_IMAGES["mygedview"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["mygedview"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_mygedview");
			$menu->addSubmenu($submenu);
		}
		if (PGV_USER_GEDCOM_ID) {
			//-- my_pedigree submenu
			$submenu = new Menu(i18n::translate('My Pedigree'), encode_url("pedigree.php?rootid=".PGV_USER_GEDCOM_ID."&show_full={$showFull}&talloffset={$showLayout}"));
			if (!empty($PGV_IMAGES["pedigree"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["pedigree"]["small"]);
			//$submenu->addIcon($PGV_IMAGE_DIR."/small/pedigree.gif");
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_pedigree");
			$menu->addSubmenu($submenu);
			//-- my_indi submenu
			$submenu = new Menu(i18n::translate('My Individual Record'), "individual.php?pid=".PGV_USER_GEDCOM_ID);
			if (!empty($PGV_IMAGES["indis"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_indis");
			$menu->addSubmenu($submenu);
		}
		if (PGV_USER_GEDCOM_ADMIN){
			$menu->addSeparator();
			//-- admin submenu
			$submenu = new Menu(i18n::translate('Admin'), "admin.php");
			if (!empty($PGV_IMAGES["admin"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["admin"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_admin");
			$menu->addSubmenu($submenu);
			//-- manage_gedcoms submenu
			$submenu = new Menu(i18n::translate('Manage GEDCOMs and edit Privacy'), "editgedcoms.php");
			if (!empty($PGV_IMAGES["admin"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["admin"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_admin");
			$menu->addSubmenu($submenu);
			//-- user_admin submenu
			if (PGV_USER_IS_ADMIN) {
				$submenu = new Menu(i18n::translate('User administration'), "useradmin.php");
				if (!empty($PGV_IMAGES["admin"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["admin"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_admin");
				$menu->addSubmenu($submenu);
				//-- manage_media submenu
				if (is_writable($MEDIA_DIRECTORY) && $MULTI_MEDIA) {
					$submenu = new Menu(i18n::translate('Manage MultiMedia'), "media.php");
					if (!empty($PGV_IMAGES["menu_media"]["small"]))
						$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_media"]["small"]);
					$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_menu_media");
					$menu->addSubmenu($submenu);
				}
			}
		}
		if (PGV_USER_CAN_EDIT) {
			//-- upload_media submenu
			if (is_writable($MEDIA_DIRECTORY) && $MULTI_MEDIA) {
				$menu->addSeparator();
				$submenu = new Menu(i18n::translate('Upload Media files'), "uploadmedia.php");
				if (!empty($PGV_IMAGES["menu_media"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_media"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_menu_media");
				$menu->addSubmenu($submenu);
			}
		}
		return $menu;
	}

	/**
	* get the menu for the charts
	* @return Menu the menu item
	*/
	static function getChartsMenu($rootid='') {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $SEARCH_SPIDER;
		global $PEDIGREE_FULL_DETAILS, $PEDIGREE_LAYOUT;
		global $controller;
		
		$style = "top";
		if ($rootid) $style = "sub";
		if (isset($controller)) {
			if (!$rootid) {
				if (isset($controller->pid)) $rootid = $controller->pid;
				if (isset($controller->rootid)) $rootid = $controller->rootid;
			}
		}

		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if (!empty($SEARCH_SPIDER)) {
			$menu = new Menu("", "", "");
			return $menu;
		}

		$showFull = ($PEDIGREE_FULL_DETAILS) ? 1 : 0;
		$showLayout = ($PEDIGREE_LAYOUT) ? 1 : 0;

		//-- main charts menu item
		$link = "pedigree.php?ged={$GEDCOM}&show_full={$showFull}&talloffset={$showLayout}";
		if ($rootid) $link .= "&rootid={$rootid}";
		if ($style=="sub") {
			$menu = new Menu(i18n::translate('Charts'), encode_url($link));
			if (!empty($PGV_IMAGES["pedigree"]["small"]))
				$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["pedigree"]["small"]);
			$menu->addClass("submenuitem$ff", "submenuitem_hover$ff", "submenu$ff", "", "icon_small_pedigree");
		}
		else {
			// top menubar
			$menu = new Menu(i18n::translate('Charts'), encode_url($link), "down");
			if (!empty($PGV_IMAGES["pedigree"]["large"]))
				$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["pedigree"]["large"]);
			$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_pedigree");
		}

		// Build a sortable list of submenu items and then sort it in localized name order
		$menuList = array();
		$menuList["pedigree"] = i18n::translate('Pedigree Chart');
		if (file_exists(PGV_ROOT.'descendancy.php')) $menuList["descendancy"] = i18n::translate('Descendancy Chart');
		if (file_exists(PGV_ROOT.'ancestry.php')) $menuList["ancestry"] = i18n::translate('Ancestry Chart');
		if (file_exists(PGV_ROOT.'compact.php')) $menuList["compact"] = i18n::translate('Compact Chart');
		if (file_exists(PGV_ROOT.'fanchart.php') && function_exists("imagettftext")) $menuList["fanchart"] = i18n::translate('Circle Diagram');
		if (file_exists(PGV_ROOT.'hourglass.php')) $menuList["hourglass"] = i18n::translate('Hourglass Chart');
		if (file_exists(PGV_ROOT.'familybook.php')) $menuList["familybook"] = i18n::translate('Family Book Chart');
		if (file_exists(PGV_ROOT.'timeline.php')) $menuList["timeline"] = i18n::translate('Timeline Chart');
		if (file_exists(PGV_ROOT.'lifespan.php')) $menuList["lifespan"] = i18n::translate('Lifespan Chart');
		if (file_exists(PGV_ROOT.'relationship.php')) $menuList["relationship"] = i18n::translate('Relationship Chart');
		if (file_exists(PGV_ROOT.'statistics.php')) $menuList["statistics"] = i18n::translate('Statistics');
		if (file_exists(PGV_ROOT.'treenav.php')) $menuList["treenav"] = i18n::translate('Interactive Tree');
		if (file_exists(PGV_ROOT.'modules/googlemap/pedigree_map.php')) {
			$menuList["pedigree_map"] = i18n::translate('Pedigree Map');//added for pedigree_map
		}
		asort($menuList);

		// Produce the submenus in localized name order
		foreach($menuList as $menuType => $menuName) {
			switch ($menuType) {
			case "pedigree":
				//-- pedigree
				$link = "pedigree.php?ged={$GEDCOM}&show_full={$showFull}&talloffset={$showLayout}";
				if ($rootid) $link .= "&rootid={$rootid}";
				$submenu = new Menu(i18n::translate('Pedigree Chart'), encode_url($link));
				if (!empty($PGV_IMAGES["pedigree"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["pedigree"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_pedigree");
				$menu->addSubmenu($submenu);
				break;

			case "descendancy":
				//-- descendancy
				$link = "descendancy.php?ged={$GEDCOM}";
				if ($rootid) $link .= "&pid={$rootid}&show_full={$showFull}";
				$submenu = new Menu(i18n::translate('Descendancy Chart'), encode_url($link));
				if (!empty($PGV_IMAGES["descendant"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["descendant"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_descendant");
				$menu->addSubmenu($submenu);
				break;

			case "ancestry":
				//-- ancestry
				$link = "ancestry.php?ged=".$GEDCOM;
				if ($rootid) $link .= "&rootid={$rootid}&show_full={$showFull}";
				$submenu = new Menu(i18n::translate('Ancestry Chart'), encode_url($link));
				if (!empty($PGV_IMAGES["ancestry"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["ancestry"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_ancestry");
				$menu->addSubmenu($submenu);
				break;

			case "compact":
				//-- compact
				$link = "compact.php?ged=".$GEDCOM;
				if ($rootid) $link .= "&rootid=".$rootid;
				$submenu = new Menu(i18n::translate('Compact Chart'), encode_url($link));
				if (!empty($PGV_IMAGES["ancestry"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["ancestry"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_ancestry");
				$menu->addSubmenu($submenu);
				break;

			case "fanchart":
				//-- fan chart
				$link = "fanchart.php?ged=".$GEDCOM;
				if ($rootid) $link .= "&rootid=".$rootid;
				$submenu = new Menu(i18n::translate('Circle Diagram'), encode_url($link));
				if (!empty($PGV_IMAGES["fanchart"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["fanchart"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_fanchart");
				$menu->addSubmenu($submenu);
				break;

			case "hourglass":
				//-- hourglass
				$link = "hourglass.php?ged=".$GEDCOM;
				if ($rootid) $link .= "&pid={$rootid}&show_full={$showFull}";
				$submenu = new Menu(i18n::translate('Hourglass Chart'), encode_url($link));
				if (!empty($PGV_IMAGES["hourglass"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["hourglass"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_hourglass");
				$menu->addSubmenu($submenu);
				break;

			case "familybook":
				//-- familybook
				$link = "familybook.php?ged=".$GEDCOM;
				if ($rootid) $link .= "&pid={$rootid}&show_full={$showFull}";
				$submenu = new Menu(i18n::translate('Family Book Chart'), encode_url($link));
				if (!empty($PGV_IMAGES["fambook"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["fambook"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_fambook");
				$menu->addSubmenu($submenu);
				break;

			case "timeline":
				//-- timeline
				$link = "timeline.php?ged=".$GEDCOM;
				if ($rootid) $link .= "&amp;pids[]=".$rootid;
				$submenu = new Menu(i18n::translate('Timeline Chart'), $link);
				if (!empty($PGV_IMAGES["timeline"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["timeline"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_timeline");
				$menu->addSubmenu($submenu);
				if (isset($controller) && !empty($controller->famid)) {
					// Build a sortable list of submenu items and then sort it in localized name order
					$menuList = array();
					$menuList["parentTimeLine"] = i18n::translate('Show couple on timeline chart');
					$menuList["childTimeLine"] = i18n::translate('Show children on timeline chart');
					$menuList["familyTimeLine"] = i18n::translate('Show family on timeline chart');
					asort($menuList);
			
					// Produce the submenus in localized name order
					foreach($menuList as $menuType => $menuName) {
						switch ($menuType) {
						case "parentTimeLine":
							// charts / parents_timeline
							$submenu = new Menu(i18n::translate('Show couple on timeline chart'), encode_url('timeline.php?pids[0]='.$controller->getHusband().'&pids[1]='.$controller->getWife()));
							if (!empty($PGV_IMAGES["timeline"]["small"])) {
								$submenu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['timeline']['small']}");
							}
							$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}");
							$menu->addSubmenu($submenu);
							break;
			
						case "childTimeLine":
							// charts / children_timeline
							$submenu = new Menu(i18n::translate('Show children on timeline chart'), encode_url('timeline.php?'.$controller->getChildrenUrlTimeline()));
							if (!empty($PGV_IMAGES["timeline"]["small"])) {
								$submenu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['timeline']['small']}");
							}
							$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}");
							$menu->addSubmenu($submenu);
							break;
			
						case "familyTimeLine":
							// charts / family_timeline
							$submenu = new Menu(i18n::translate('Show family on timeline chart'), encode_url('timeline.php?pids[0]='.$controller->getHusband().'&pids[1]='.$controller->getWife().'&'.$controller->getChildrenUrlTimeline(2)));
							if (!empty($PGV_IMAGES["timeline"]["small"])) {
								$submenu->addIcon("{$PGV_IMAGE_DIR}/{$PGV_IMAGES['timeline']['small']}");
							}
							$submenu->addClass("submenuitem{$ff}", "submenuitem_hover{$ff}");
							$menu->addSubmenu($submenu);
							break;
			
						}
					}
				}
				
				break;

			case "lifespan":
				//-- lifespan
				$link = "lifespan.php?ged=".$GEDCOM;
				if ($rootid) $link .= "&pids[]={$rootid}&addFamily=1";
				$submenu = new Menu(i18n::translate('Lifespan Chart'), encode_url($link));
				if (!empty($PGV_IMAGES["timeline"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["timeline"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_timeline");
				$menu->addSubmenu($submenu);
				break;

			case "relationship":
				//-- relationship
				if ($rootid) {
					// We are generating the "individual" menu
					$pids=array(PGV_USER_GEDCOM_ID, PGV_USER_ROOT_ID);
					if (PGV_USER_ID) {
						foreach (getUserFavorites(PGV_USER_NAME) as $favorite) {
							// An indi in this gedcom?
							if ($favorite['type']=='INDI' && $favorite['file']==PGV_GEDCOM) {
								$pids[]=$favorite['gid'];
							}
						}
					}
					foreach (array_unique($pids) as $pid) {
						if ($pid && $pid!=$rootid) {
							$person=Person::getInstance($pid);
							if ($person) {
								$name=$person->getFullName();
							} else {
								$name=$pid;
							}
							$submenu = new Menu(
								i18n::translate('Relationship Chart').": ".PrintReady($name),
								encode_url("relationship.php?pid1={$pid}&pid2={$rootid}&pretty=2&followspouse=1&ged=".PGV_GEDCOM)
							);
							if (!empty($PGV_IMAGES["relationship"]["small"])) {
								$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["relationship"]["small"]);
							}
							$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_relationship");
							$menu->addSubmenu($submenu);
						}
					}
				} else {
					// We are generating the "toplinks" menu
					$submenu = new Menu(
						i18n::translate('Relationship Chart'),
						encode_url("relationship.php?ged=".$GEDCOM)
					);
					if (!empty($PGV_IMAGES["relationship"]["small"])) {
						$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["relationship"]["small"]);
					}
					$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_relationship");
					$menu->addSubmenu($submenu);
				}
				break;

			case "statistics":
				//-- statistics plot
				$submenu = new Menu(i18n::translate('Statistics'), encode_url("statistics.php?ged=".PGV_GEDCOM));
				if (!empty($PGV_IMAGES["statistic"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["statistic"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_statistic");
				$menu->addSubmenu($submenu);
				break;

			case "treenav":
				//-- interactive tree
				$link = "treenav.php?ged={$GEDCOM}";
				if ($rootid) $link .= "&rootid=".$rootid;
				$submenu = new Menu(i18n::translate('Interactive Tree'), encode_url($link));
				if (!empty($PGV_IMAGES["gedcom"]["tree"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["tree"]);
				else if (!empty($PGV_IMAGES["gedcom"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_gedcom");
				$menu->addSubmenu($submenu);
				break;

			//added for pedigree_map
			case "pedigree_map":
				//-- pedigree map
				$link = "module.php?ged={$GEDCOM}&mod=googlemap&pgvaction=pedigree_map";
				if ($rootid) $link .= "&rootid=".$rootid;
				$submenu = new Menu(i18n::translate('Pedigree Map'), encode_url($link));
				$submenu->addIcon('modules/googlemap/images/pedigree_map.gif');
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff");
				$menu->addSubmenu($submenu);
				break;
			//end of added for pedigree_map
			}
		}
		return $menu;
	}

	/**
	* get the menu for the lists
	* @return Menu the menu item
	*/
	static function getListsMenu($surname="") {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;
		global $SHOW_SOURCES, $MULTI_MEDIA, $SEARCH_SPIDER;
		global $ALLOW_CHANGE_GEDCOM;
		global $controller;
		
		$style = "top";
		if ($surname) $style = "sub";
		if (isset($controller)) {
			if (!$surname) {
				if (isset($controller->indi)) {
					list($surname)=explode(',', $controller->indi->getSortName());
				}
				if (isset($controller->rootid)) {
					$person = Person::getInstance($controller->rootid);
					list($surname)=explode(',', $person->getSortName());
				}
			}
		}
		
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";

		if (!empty($SEARCH_SPIDER)) { // Only want the indi list for search engines.
			//-- main lists menu item
			$link = "indilist.php?ged={$GEDCOM}";
			if ($style=="sub") {
				$link .= "&surname={$surname}";
				$menu = new Menu(i18n::translate('Lists'), encode_url($link));
				if (!empty($PGV_IMAGES["indis"]["small"]))
					$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["small"]);
				$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_small_indis");
			}
			else {
				$menu = new Menu(i18n::translate('Lists'), encode_url($link), "down");
				if (!empty($PGV_IMAGES["indis"]["large"]))
					$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["large"]);
				$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_indis");
			}

			//-- gedcom list
			if ($ALLOW_CHANGE_GEDCOM) {
				foreach (get_all_gedcoms() as $ged_id=>$gedcom) {
					$submenu = new Menu(i18n::translate('Individuals')." - ".PrintReady(get_gedcom_setting($ged_id, 'title')), encode_url('indilist.php?ged='.$gedcom));
					if (!empty($PGV_IMAGES["gedcom"]["small"]))
						$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["gedcom"]["small"]);
					$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_gedcom");
					$menu->addSubmenu($submenu);
				}
			}
			return $menu;
		}
		//-- main lists menu item
		$link = "indilist.php?ged=".$GEDCOM;
		if ($style=="sub") {
			$link .= "&surname=".$surname;
			$menu = new Menu(i18n::translate('Lists'), encode_url($link));
			if (!empty($PGV_IMAGES["indis"]["small"]))
				$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["small"]);
			$menu->addClass("submenuitem$ff", "submenuitem_hover$ff", "submenu$ff", "icon_small_indis");
		}
		else {
			$menu = new Menu(i18n::translate('Lists'), $link, "down");
			if (!empty($PGV_IMAGES["indis"]["large"]))
				$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["large"]);
			$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_indis");
		}

		// Build a sortable list of submenu items and then sort it in localized name order
		$menuList = array();
		$menuList["individual"] = i18n::translate('Individuals');
		if (file_exists(PGV_ROOT.'famlist.php')) $menuList["family"] = i18n::translate('Families');
		if (file_exists(PGV_ROOT.'branches.php')) $menuList["branches"] = i18n::translate('Branches');
		if ($style=="top" && file_exists(PGV_ROOT.'sourcelist.php') && $SHOW_SOURCES>=PGV_USER_ACCESS_LEVEL) $menuList["source"] = i18n::translate('Sources');
		if ($style=="top" && file_exists(PGV_ROOT.'notelist.php') && $SHOW_SOURCES>=PGV_USER_ACCESS_LEVEL) $menuList["note"] = i18n::translate('Shared Notes');
		if ($style=="top" && file_exists(PGV_ROOT.'repolist.php')) $menuList["repository"] = i18n::translate('Repositories');
		if ($style=="top" && file_exists(PGV_ROOT.'placelist.php')) $menuList["places"] = i18n::translate('Place Hierarchy');
		if ($style=="top" && file_exists(PGV_ROOT.'medialist.php') && $MULTI_MEDIA) $menuList["media"] = i18n::translate('MultiMedia');
		asort($menuList);

		// Produce the submenus in localized name order

		foreach($menuList as $menuType => $menuName) {
			switch ($menuType) {
			case "individual":
				//-- indi list sub menu
				$link = "indilist.php?ged=".$GEDCOM;
				if ($surname) $link .= "&surname=".$surname;
				$submenu = new Menu(i18n::translate('Individuals'), encode_url($link));
				if (!empty($PGV_IMAGES["indis"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["indis"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_indis");
				$menu->addSubmenu($submenu);
				break;

			case "family":
				//-- famlist sub menu
				$link = "famlist.php?ged=$GEDCOM";
				if ($surname) $link .= "&amp;surname=".$surname;
				$submenu = new Menu(i18n::translate('Families'), $link);
				if (!empty($PGV_IMAGES["cfamily"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["cfamily"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_cfamily");
				$menu->addSubmenu($submenu);
				break;

			case "branches":
				//-- branches sub menu
				$link = "branches.php?ged=$GEDCOM";
				if ($surname) $link .= "&amp;surn=".$surname;
				$submenu = new Menu(i18n::translate('Branches'), $link);
				if (!empty($PGV_IMAGES["patriarch"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["patriarch"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_patriarch");
				$menu->addSubmenu($submenu);
				break;

			case "source":
				//-- source
				$submenu = new Menu(i18n::translate('Sources'), encode_url('sourcelist.php?ged='.$GEDCOM));
				if (!empty($PGV_IMAGES["menu_source"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_source"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_menu_source");
				$menu->addSubmenu($submenu);
				break;

			case "note":
				//-- shared note
				$submenu = new Menu(i18n::translate('Shared Notes'), encode_url('notelist.php?ged='.$GEDCOM));
				if (!empty($PGV_IMAGES["menu_note"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_note"]["small"]);
				else if (!empty($PGV_IMAGES["notes"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["notes"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_notes");
				$menu->addSubmenu($submenu);
				break;

			case "repository":
				//-- repository
				$submenu = new Menu(i18n::translate('Repositories'), "repolist.php");
				if (!empty($PGV_IMAGES["menu_repository"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_repository"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_menu_repository");
				$menu->addSubmenu($submenu);
				break;

			case "places":
				//-- places
				$submenu = new Menu(i18n::translate('Place Hierarchy'), encode_url('placelist.php?ged='.$GEDCOM));
				if (!empty($PGV_IMAGES["place"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["place"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_place");
				$menu->addSubmenu($submenu);
				break;

			case "media":
				//-- medialist
				$submenu = new Menu(i18n::translate('MultiMedia'), encode_url('medialist.php?ged='.$GEDCOM));
				if (!empty($PGV_IMAGES["menu_media"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_media"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_menu_media");
				$menu->addSubmenu($submenu);
				break;
			}
		}

		return $menu;
	}

	/**
	* get the menu for the calendar
	* @return Menu the menu item
	*/
	static function getCalendarMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $SEARCH_SPIDER, $GEDCOM;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if ((!file_exists(PGV_ROOT.'calendar.php')) || (!empty($SEARCH_SPIDER))) {
			$menu = new Menu("", "", "");
//			$menu->print_menu = null;
			return $menu;
			}
		//-- main calendar menu item
		$menu = new Menu(i18n::translate('Anniversary Calendar'), encode_url('calendar.php?ged='.$GEDCOM), "down");
		if (!empty($PGV_IMAGES["calendar"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["calendar"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_calendar");
		//-- viewday sub menu
		$submenu = new Menu(i18n::translate('View Day'), encode_url('calendar.php?ged='.$GEDCOM));
		if (!empty($PGV_IMAGES["calendar"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["calendar"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_calendar");
		$menu->addSubmenu($submenu);
		//-- viewmonth sub menu
		$submenu = new Menu(i18n::translate('View Month'), encode_url("calendar.php?ged={$GEDCOM}&action=calendar"));
		if (!empty($PGV_IMAGES["calendar"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["calendar"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_calendar");
		$menu->addSubmenu($submenu);
		//-- viewyear sub menu
		$submenu = new Menu(i18n::translate('View Year'), encode_url("calendar.php?ged={$GEDCOM}&action=year"));
		if (!empty($PGV_IMAGES["calendar"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["calendar"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_calendar");
		$menu->addSubmenu($submenu);
		return $menu;
	}

	/**
	* get the reports menu
	* @return Menu the menu item
	*/
	static function getReportsMenu($pid="", $famid="") {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;
		global $LANGUAGE, $PRIV_PUBLIC, $PRIV_USER, $PRIV_NONE, $PRIV_HIDE, $SEARCH_SPIDER;
		global $controller;
		
		$style = "top";
		if ($pid || $famid) $style = "sub";
		if (isset($controller)) {
			if (!$pid) {
				if (isset($controller->pid)) $pid = $controller->pid;
				if (isset($controller->rootid)) $pid = $controller->rootid;
			}
			if (!$famid) {
				if (isset($controller->famid)) $famid = $controller->famid;
			}
		}
		
		
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if ((!file_exists(PGV_ROOT.'reportengine.php')) || (!empty($SEARCH_SPIDER))) {
			$menu = new Menu("", "", "");
//			$menu->print_menu = null;
			return $menu;
			}

		//-- main reports menu item
		if ($style=="sub") {
			$menu = new Menu(i18n::translate('Reports'), "#");
			if (!empty($PGV_IMAGES["reports"]["small"]))
				$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["reports"]["small"]);
			$menu->addClass("submenuitem$ff", "submenuitem_hover$ff", "submenu$ff", "icon_small_reports");
		}
		else {
			// top menubar
			$menu = new Menu(i18n::translate('Reports'), encode_url('reportengine.php?ged='.$GEDCOM), "down");
			if (!empty($PGV_IMAGES["reports"]["large"]))
				$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["reports"]["large"]);
			$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_reports");
		}

		// Build a list of reports and sort that list into localized title order
		$reports = get_report_list();
		$menuList = array();
		foreach ($reports as $file=>$report) {
			if (!empty($report["title"][$LANGUAGE])) $label = $report["title"][$LANGUAGE];
			else $label = implode("", $report["title"]);
			$menuList[$file] = trim($label);
		}
		asort($menuList);

		// Produce those submenus in localized name order

		//print_r($reports);
		$username = PGV_USER_NAME;
		foreach($menuList as $file=>$label) {
			$report = $reports[$file];
			if (!isset($report["access"])) {
				$report["access"] = $PRIV_PUBLIC;
			}
			if ($report["access"]>=PGV_USER_ACCESS_LEVEL) {
				if (!empty($report["title"][$LANGUAGE])) {
					$label = $report["title"][$LANGUAGE];
				} else {
					$label = implode("", $report["title"]);
				}
				// indi report
				if ($pid) {
					$submenu = new Menu($label, encode_url("reportengine.php?ged={$GEDCOM}&action=setup&report={$report['file']}&pid={$pid}"));
				}
				// family report
				elseif ($famid) {
					$submenu = new Menu($label, encode_url("reportengine.php?ged={$GEDCOM}&action=setup&report={$report['file']}&famid={$famid}"));
				}
				// default
				else {
					$submenu = new Menu($label, encode_url("reportengine.php?ged={$GEDCOM}&action=setup&report={$report['file']}"));
				}
				if (isset($PGV_IMAGES["reports"]["small"]) && isset($PGV_IMAGES[$report["icon"]]["small"])) $iconfile=$PGV_IMAGE_DIR."/".$PGV_IMAGES[$report["icon"]]["small"];
				if (isset($iconfile) && file_exists($iconfile)) $submenu->addIcon($iconfile);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_".$report["icon"]);
				// indi report
				if ($pid && $report["icon"]!="sfamily" && $report["icon"]!="place") {
					$menu->addSubmenu($submenu);
				}
				// family report
				elseif ($famid && $report["icon"]=="sfamily") {
					$menu->addSubmenu($submenu);
				}
				// default
				elseif (empty($pid) && empty($famid)) {
					$menu->addSubmenu($submenu);
				}
			}
		}
		return $menu;
	}

	/**
	* get the optional site-specific menu
	* @return Menu the menu item
	*/
	static function getOptionalMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $SEARCH_SPIDER;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if (!file_exists(PGV_ROOT.'includes/extras/optional_menu.php') || !empty($SEARCH_SPIDER)) {
			$menu = new Menu("", "", "");
//			$menu->print_menu = null;
			return $menu;
		}
		require PGV_ROOT.'includes/extras/optional_menu.php';
		return $menu;
	}

	/**
	* get the print_preview menu
	* @return Menu the menu item
	*/
	static function getPreviewMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $QUERY_STRING, $SEARCH_SPIDER;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if (!empty($SEARCH_SPIDER)) {
			$menu = new Menu("", "", "");
//			$menu->print_menu = null;
			return $menu;
			}
		//-- main print_preview menu item
		$menu = new Menu(i18n::translate('Printer-friendly Version'), PGV_SCRIPT_NAME.normalize_query_string($QUERY_STRING."&amp;view=preview"), "down");
		if (!empty($PGV_IMAGES["printer"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["printer"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_printer");
		return $menu;
	}

	/**
	* get the search menu
	* @return Menu the menu item
	*/
	static function getSearchMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM;
		global $SHOW_MULTISITE_SEARCH, $SEARCH_SPIDER;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if ((!file_exists(PGV_ROOT.'search.php')) || (!empty($SEARCH_SPIDER))) {
			$menu = new Menu("", "", "");
//			$menu->print_menu = null;
			return $menu;
			}
		//-- main search menu item
		$menu = new Menu(i18n::translate('Search'), encode_url('search.php?ged='.$GEDCOM), "down");
		if (!empty($PGV_IMAGES["search"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["search"]["large"]);
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_search");
		//-- search_general sub menu
		$submenu = new Menu(i18n::translate('General Search'), encode_url("search.php?ged={$GEDCOM}&action=general"));
		if (!empty($PGV_IMAGES["search"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["search"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_search");
		$menu->addSubmenu($submenu);
		//-- search_soundex sub menu
		$submenu = new Menu(i18n::translate('Soundex Search'), encode_url("search.php?ged={$GEDCOM}&action=soundex"));
		if (!empty($PGV_IMAGES["search"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["search"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_search");
		$menu->addSubmenu($submenu);
		//-- advanced search
		$submenu = new Menu(i18n::translate('Advanced Search'), encode_url("search_advanced.php?ged={$GEDCOM}"));
		if (!empty($PGV_IMAGES["search"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["search"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_search");
		$menu->addSubmenu($submenu);
		//-- search_replace sub menu
		if (PGV_USER_CAN_EDIT) {
			$submenu = new Menu(i18n::translate('Search and Replace'), encode_url("search.php?ged={$GEDCOM}&action=replace"));
			if (!empty($PGV_IMAGES["search"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["search"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_search");
			$menu->addSubmenu($submenu);
		}

		//-- search_multisite sub menu
		if ($SHOW_MULTISITE_SEARCH >= PGV_USER_ACCESS_LEVEL) {
			$sitelist = get_server_list();
			if (count($sitelist)>0) {
				$submenu = new Menu(i18n::translate('Multi Site Search'), encode_url("search.php?ged={$GEDCOM}&action=multisite"));
				if (!empty($PGV_IMAGES["search"]["small"]))
					$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["search"]["small"]);
				$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_search");
				$menu->addSubmenu($submenu);
			}
		}
		return $menu;
	}

	/**
	* get an array of module menu objects
	* @return array
	*/
	static function getModuleMenus() {
		$modules = array();
		$mods = PGVModule::getActiveList('M', PGV_USER_ACCESS_LEVEL);
		uasort($mods, "PGVModule::compare_menu_order");
		foreach ($mods as $mod) {
			$menu = $mod->getMenu();
			if ($menu) $modules[] = $mod->getMenu();
		}

		return $modules;
	}

	/**
	* get the help menu
	* @return Menu the menu item
	*/
	static function getHelpMenu() {
		global $TEXT_DIRECTION, $PGV_IMAGE_DIR, $PGV_IMAGES, $GEDCOM, $SEARCH_SPIDER;
		global $SHOW_CONTEXT_HELP, $QUERY_STRING, $helpindex, $action;
		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";
		if (!empty($SEARCH_SPIDER)) {
			$menu = new Menu("", "", "");
//			$menu->print_menu = null;
			return $menu;
			}
		//-- main help menu item
		$menu = new Menu(i18n::translate('Help'), "#", "down");
		if (!empty($PGV_IMAGES["help"]["large"]))
			$menu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["help"]["large"]);
		if (empty($helpindex))
			$menu->addOnclick("return helpPopup('".PGV_SCRIPT_NAME."');");
		else
			$menu->addOnclick("return helpPopup('".$helpindex."');");
		$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_help");

		//-- help_for_this_page sub menu
		$submenu = new Menu(i18n::translate('Help with this page'), "#");
		if (!empty($PGV_IMAGES["menu_help"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_help"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_menu_help");
		if (empty($helpindex))
			$submenu->addOnclick("return helpPopup('".PGV_SCRIPT_NAME."');");
		else
			$submenu->addOnclick("return helpPopup('".$helpindex."');");
		$menu->addSubmenu($submenu);
		//-- help_contents sub menu
		$submenu = new Menu(i18n::translate('Help Contents'), "#");
		if (!empty($PGV_IMAGES["menu_help"]["small"]))
			$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_help"]["small"]);
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_menu_help");
		$submenu->addOnclick("return helpPopup('help_contents_help');");
		$menu->addSubmenu($submenu);
		//-- faq sub menu
		if (file_exists(PGV_ROOT.'faq.php')) {
			$submenu = new Menu(i18n::translate('FAQ List'), "faq.php");
			if (!empty($PGV_IMAGES["menu_help"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["menu_help"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_menu_help");
			$menu->addSubmenu($submenu);
		}
		//-- add wiki links
		$menu->addSeparator();
		$submenu = new Menu(i18n::translate('Wiki Main Page'), PGV_PHPGEDVIEW_WIKI.'/en/index.php?title=Main_Page" target="_blank');
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_wiki");
		$menu->addSubmenu($submenu);

		$submenu = new Menu(i18n::translate('Wiki User\'s Guide'), PGV_PHPGEDVIEW_WIKI.'/en/index.php?title=Users_Guide" target="_blank');
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_wiki");
		$menu->addSubmenu($submenu);

		if (PGV_USER_GEDCOM_ADMIN) {
			$submenu = new Menu(i18n::translate('Wiki Administrator\'s Guide'), PGV_PHPGEDVIEW_WIKI.'/en/index.php?title=Administrators_Guide" target="_blank');
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_wiki");
			$menu->addSubmenu($submenu);
		}

		//-- add contact links to help menu
		$menu->addSeparator();
		$menuitems = contact_menus();
		foreach($menuitems as $menuitem) {
			$submenu = new Menu($menuitem["label"], $menuitem["link"]);
			if (!empty($PGV_IMAGES["mygedview"]["small"]))
				$submenu->addIcon($PGV_IMAGE_DIR."/".$PGV_IMAGES["mygedview"]["small"]);
			$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_contact");
			if (!empty($menuitem["onclick"])) $submenu->addOnclick($menuitem["onclick"]);
			$menu->addSubmenu($submenu);
		}
		//-- add show/hide context_help
		$menu->addSeparator();
		if ($_SESSION["show_context_help"])
			$submenu = new Menu(i18n::translate('Hide Contextual Help'), PGV_SCRIPT_NAME.normalize_query_string($QUERY_STRING."&amp;show_context_help=no"));
		else
			$submenu = new Menu(i18n::translate('Show Contextual Help'), PGV_SCRIPT_NAME.normalize_query_string($QUERY_STRING."&amp;show_context_help=yes"));
		$submenu->addClass("submenuitem$ff", "submenuitem_hover$ff", "", "icon_small_menu_help");
		$menu->addSubmenu($submenu);
		return $menu;
	}

	/**
	* get the menu with links change to each theme
	* @return Menu the menu item
	*/
	static function getThemeMenu() {
		global $SEARCH_SPIDER, $ALLOW_THEME_DROPDOWN, $ALLOW_USER_THEMES, $THEME_DIR;

		$current=get_user_setting(PGV_USER_ID, 'theme');
		$all_themes=get_theme_names();
		if (!array_key_exists($current, $all_themes)) {
			$current=$THEME_DIR;		
		}

		if ($ALLOW_THEME_DROPDOWN && $ALLOW_USER_THEMES && !$SEARCH_SPIDER) {
			isset($_SERVER["QUERY_STRING"]) == true?$tqstring = "?".$_SERVER["QUERY_STRING"]:$tqstring = "";
			$frompage = PGV_SCRIPT_NAME.decode_url($tqstring);
			if (isset($_REQUEST['mod'])) {
				if (!strstr($frompage, "?")) {
					if (!strstr($frompage, "%3F")) ;
					else $frompage .= "?";
				}
				if (!strstr($frompage, "&mod") || !strstr($frompage, "?mod")) $frompage .= "&mod=".$_REQUEST['mod'];
			}
			if (substr($frompage,-1) == "?") $frompage = substr($frompage,0,-1);
			if (substr($frompage,-1) == "&") $frompage = substr($frompage,0,-1);
			// encode frompage address in other case we lost the all variables on theme change
			$frompage = base64_encode($frompage);
			$menu=new Menu(i18n::translate('Change Theme'));
			$menu->addClass('thememenuitem', 'thememenuitem_hover', 'themesubmenu', "icon_small_theme");
			foreach ($all_themes as $themename=>$themedir) {
				$submenu=new Menu($themename, encode_url("themechange.php?frompage={$frompage}&mytheme={$themedir}"));
				if ($themedir==$current) {
					$submenu->addClass('favsubmenuitem_selected', 'favsubmenuitem_hover');
				} else {
					$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
				}
				$menu->addSubMenu($submenu);
			}
			return $menu;
		} else {
			return new Menu('', '');
		}
	}
	/**
	* get the menu with links change to each color themes subcolor type
	* @return Menu the menu item
	*/
	static function getColorMenu() {
		global $SEARCH_SPIDER, $ALLOW_THEME_DROPDOWN, $ALLOW_USER_THEMES, $THEME_DIR;
		
		$current=get_user_setting(PGV_USER_ID, 'theme');
		$all_themes=get_theme_names();
		if (!array_key_exists($current, $all_themes)) {
			$current=$THEME_DIR;		
		}
		$filePath = $THEME_DIR . "css/";
		$string="";
		$fileCount=0;
		$dir = opendir($filePath); # Open the path
		while ($file = readdir($dir)) { 
  			if (!substr_compare($file, '.css', -4)) { # Look at only files with a .css extension
    		$len = strlen($file);
    		$file = substr($file,0,$len-4);  # Remove .css 
    		$colorList[] = $file;
    		sort ($colorList); # Sort array
    		$fileCount++;
  			}
		}
		if ($ALLOW_THEME_DROPDOWN && $ALLOW_USER_THEMES && !$SEARCH_SPIDER) {
			isset($_SERVER["QUERY_STRING"]) == true?$tqstring = "?".$_SERVER["QUERY_STRING"]:$tqstring = "";
			$frompage = PGV_SCRIPT_NAME.decode_url($tqstring);
			if (isset($_REQUEST['mod'])) {
				if (!strstr($frompage, "?")) {
					if (!strstr($frompage, "%3F")) ;
					else $frompage .= "?";
				}
				if (!strstr($frompage, "&mod") || !strstr($frompage, "?mod")) $frompage .= "&mod=".$_REQUEST['mod'];
			}
			if (substr($frompage,-1) == "?") $frompage = substr($frompage,0,-1);
			if (substr($frompage,-1) == "&") $frompage = substr($frompage,0,-1);
			// encode frompage address in other case we lost the all variables on theme change
			$frompage = base64_encode($frompage);
			$menu=new Menu(i18n::translate('Color Palette'));
			$menu->addClass('thememenuitem', 'thememenuitem_hover', 'themesubmenu', "icon_small_theme");
			foreach ($colorList as $colorChoice) {
				$submenu=new Menu($colorChoice, encode_url("colorchange.php?frompage={$frompage}&mycolor={$colorChoice}"));
				$menu->addSubMenu($submenu);
			}
			return $menu;
		} else {
			return new Menu('', '');
		}
	}
	/**
	* get the menu with links to change language
	* @return Menu the menu item
	*/
	static function getLanguageMenu() {
		global $QUERY_STRING, $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION;

		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";

		$menu=new Menu(i18n::translate('Change Language'), '#', 'down');
		$menu->addClass("langmenuitem$ff", "langmenuitem_hover$ff", "submenu$ff");

		foreach (i18n::installed_languages() as $lang=>$name) {
			$submenu=new Menu($name, PGV_SCRIPT_NAME.normalize_query_string($QUERY_STRING.'&amp;lang='.$lang));
			if ($lang==WT_LOCALE) {
				$submenu->addClass('favsubmenuitem_selected', 'favsubmenuitem_hover');
			} else {
				$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
			}
			$menu->addSubMenu($submenu);
		}
		if (count($menu->submenus)>1) {
			return $menu;
		} else {
			return new Menu('', '');
		}
	}
	/**
	* get the menu with links to the user/gedcom favorites
	* @return Menu the menu item
	*/
	static function getFavouritesMenu() {		// Don't break custom themes using the old name
		return self::getFavoritesMenu();
	}
	static function getFavoritesMenu() {
		global $REQUIRE_AUTHENTICATION, $GEDCOM, $QUERY_STRING, $PGV_IMAGE_DIR, $PGV_IMAGES, $TEXT_DIRECTION;
		global $SEARCH_SPIDER;
		global $controller; // Pages with a controller can be added to the favorites

		if (!empty($SEARCH_SPIDER)) {
			return false; // show no favorites, because they taint every page that is indexed.
		}

		if ($TEXT_DIRECTION=="rtl") $ff="_rtl"; else $ff="";

		if (PGV_USER_ID || !$REQUIRE_AUTHENTICATION) {
			$menu=new Menu(i18n::translate('Favorites'), '#', 'down');
			if (!empty($PGV_IMAGES['gedcom']['large'])) {
				$menu->addIcon($PGV_IMAGE_DIR.'/'.$PGV_IMAGES['gedcom']['large']);
			}
			$menu->addClass("menuitem$ff", "menuitem_hover$ff", "submenu$ff", "icon_large_gedcom");
//			$menu->print_menu = NULL;

			$userfavs=getUserFavorites(PGV_USER_NAME);
			$gedfavs=getUserFavorites($GEDCOM);

			// User favorites
			if ($userfavs || PGV_USER_ID) {
				$submenu=new Menu('<strong>'.i18n::translate('My Favorites').'</strong>');
				$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
				$menu->addSubMenu($submenu);
				while (PGV_USER_ID && isset($controller)) {
					// Get the right $gid from each supported controller type
					switch (get_class($controller)) {
					case 'IndividualController':
						$gid = $controller->pid;
						break;
					case 'FamilyController':
						$gid = $controller->famid;
						break;
					case 'MediaController':
						$gid = $controller->mid;
						break;
					case 'SourceController':
						$gid = $controller->sid;
						break;
					case 'RepositoryController':
						$gid = $controller->rid;
						break;
					default:
						break 2;
					}
					$submenu=new Menu('<em>'.i18n::translate('Add to My Favorites').'</em>', PGV_SCRIPT_NAME.normalize_query_string($QUERY_STRING.'&amp;action=addfav&amp;gid='.$gid));
					$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
					$menu->addSubMenu($submenu);
					break;
				}
				foreach ($userfavs as $fav) {
					$OLD_GEDCOM=$GEDCOM;
					$GEDCOM=$fav['file'];
					switch($fav['type']) {
					case 'URL':
						$submenu=new Menu(PrintReady($fav['title']), $fav['url']);
						$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
						$menu->addSubMenu($submenu);
						break;
					case 'INDI':
					case 'FAM':
					case 'SOUR':
					case 'OBJE':
						if (displayDetailsById($fav['gid'], $fav['type'])) {
							$obj=GedcomRecord::getInstance($fav['gid']);
							if ($obj) {
								$submenu=new Menu(PrintReady($obj->getFullName()), encode_url($obj->getLinkUrl()));
								$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
								$menu->addSubMenu($submenu);
							}
						}
						break;
					}
					$GEDCOM=$OLD_GEDCOM;
				}
				if ($gedfavs) {
					$menu->addSeparator();
				}
			}
			// Gedcom favorites
			if ($gedfavs) {
				$submenu=new Menu('<strong>'.i18n::translate('This GEDCOM\'s Favorites').'</strong>');
				$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
				$menu->addSubMenu($submenu);
				foreach ($gedfavs as $fav) {
					$OLD_GEDCOM=$GEDCOM;
					$GEDCOM=$fav['file'];
					switch($fav['type']) {
					case 'URL':
						$submenu=new Menu(PrintReady($fav['title']), $fav['url']);
						$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
						$menu->addSubMenu($submenu);
						break;
					case 'INDI':
					case 'FAM':
					case 'SOUR':
					case 'OBJE':
						if (displayDetailsById($fav['gid'], $fav['type'])) {
							$obj=GedcomRecord::getInstance($fav['gid']);
							if ($obj) {
								$submenu=new Menu(PrintReady($obj->getFullName()), encode_url($obj->getLinkUrl()));
								$submenu->addClass('favsubmenuitem', 'favsubmenuitem_hover');
								$menu->addSubMenu($submenu);
							}
						}
						break;
					}
					$GEDCOM=$OLD_GEDCOM;
				}
			}
		}
		return $menu;
	}
}

?>
