<?php
/**
 * Classes and libraries for module system
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2010 John Finlay
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @version $Id$
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

require_once WT_ROOT.'includes/classes/class_module.php';

// Create tables, if not already present
try {
	WT_DB::updateSchema('./modules/gedcom_favorites/db_schema/', 'FV_SCHEMA_VERSION', 1);
} catch (PDOException $ex) {
	// The schema update scripts should never fail.  If they do, there is no clean recovery.
	die($ex);
}

class gedcom_favorites_WT_Module extends WT_Module implements WT_Module_Block {
	// Extend class WT_Module
	public function getTitle() {
		return i18n::translate('GEDCOM Favorites');
	}

	// Extend class WT_Module
	public function getDescription() {
		return i18n::translate('The GEDCOM Favorites block gives the administrator the ability to designate individuals from the database so that their information is easily accessible to all.  This is a way to highlight people who are important in your family history.');
	}

	// Implement class WT_Module_Block
	public function getBlock($block_id, $template=true, $cfg=null) {
		global $WT_IMAGES, $ctype, $TEXT_DIRECTION;
		global $show_full, $PEDIGREE_FULL_DETAILS, $BROWSERTYPE, $ENABLE_AUTOCOMPLETE;

		$action=safe_GET('action');
		switch ($action) {
		case 'deletefav':
			$fv_id=safe_GET('fv_id');
			if ($fv_id) {
				self::deleteFavorite($fv_id);
			}
			unset($_GET['action']);
			break;
		case 'addfav':
			$gid     =safe_GET('gid');
			$favnote =safe_GET('favnote');
			$favtype =safe_GET('favtype');
			$url     =safe_GET('url', WT_REGEX_URL);
			$favtitle=safe_GET('favtitle');

			if ($gid) {
				$indirec = find_gedcom_record($gid, WT_GED_ID);
				$ct = preg_match('/0 @(.*)@ (.*)/', $indirec, $match);
				if ($indirec && $ct>0) {
					$favorite = array();
					if (empty($favtype)) {
						if ($ctype=='user') $favtype = 'user';
						else $favtype = 'gedcom';
					}
					if ($favtype=='gedcom') {
						$favtype = WT_GEDCOM;
					}
					else $favtype=WT_GEDCOM;
					$favorite['username'] = $favtype;
					$favorite['gid'] = $gid;
					$favorite['type'] = trim($match[2]);
					$favorite['file'] = WT_GEDCOM;
					$favorite['url'] = '';
					$favorite['note'] = $favnote;
					$favorite['title'] = '';
					self::addFavorite($favorite);
				}
			}
			$url=safe_GET('url');
			if ($url) {
				if (empty($favtitle)) $favtitle = $url;
				$favorite = array();
				if (!isset($favtype)) {
					if ($ctype=='user') $favtype = 'user';
					else $favtype = 'gedcom';
				}
				if ($favtype=='gedcom') {
					$favtype = $GEDCOM;
				}
				else $favtype=WT_GEDCOM;
				$favorite['username'] = $favtype;
				$favorite['gid'] = '';
				$favorite['type'] = 'URL';
				$favorite['file'] = $GEDCOM;
				$favorite['url'] = $url;
				$favorite['note'] = $favnote;
				$favorite['title'] = $favtitle;
				self::addFavorite($favorite);
			}
			unset($_GET['action']);
			break;
		}

		$block=get_block_setting($block_id, 'block', false);
		if ($cfg) {
			foreach (array('block') as $name) {
				if (array_key_exists($name, $cfg)) {
					$$name=$cfg[$name];
				}
			}
		}

		// Override GEDCOM configuration temporarily
		if (isset($show_full)) $saveShowFull = $show_full;
		$savePedigreeFullDetails = $PEDIGREE_FULL_DETAILS;
		$show_full = 1;
		$PEDIGREE_FULL_DETAILS = 1;

		$userfavs = self::getUserFavorites(WT_GEDCOM);
		if (!is_array($userfavs)) $userfavs = array();

		$id=$this->getName().$block_id;
		$title=i18n::translate('This GEDCOM\'s Favorites').help_link('index_favorites');
		if ($TEXT_DIRECTION=="rtl") $title .= getRLM();
		$title .= "(".count($userfavs).")";
		if ($TEXT_DIRECTION=="rtl") $title .= getRLM();

		if (WT_USER_IS_ADMIN && $ENABLE_AUTOCOMPLETE) {
			$content = '<script type="text/javascript" src="js/jquery/jquery.min.js"></script>
			<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>
			<script type="text/javascript" src="js/jquery/jquery.ajaxQueue.js"></script>
			<script type="text/javascript">
			jQuery.noConflict(); // @see http://docs.jquery.com/Using_jQuery_with_Other_Libraries/
			jQuery(document).ready(function($) {
				$("input[name^=gid]").autocomplete("autocomplete.php", {
					extraParams: {field:"IFSRO"},
					formatItem: function(row, i) {
						return row[0] + " (" + row[1] + ")";
					},
					formatResult: function(row) {
						return row[1];
					},
					width: 400,
					minChars: 2
				});
			});
			</script>';
		} else $content = '';

		if ($block) {
			$style = 2; // 1 means "regular box", 2 means "wide box"
			$tableWidth = ($BROWSERTYPE=="msie") ? "95%" : "99%"; // IE needs to have room for vertical scroll bar inside the box
			$cellSpacing = "1px";
		} else {
			$style = 2;
			$tableWidth = "99%";
			$cellSpacing = "3px";
		}
		if (count($userfavs)==0) {
			if (WT_USER_GEDCOM_ADMIN) {
				$content .= i18n::translate('You have not selected any favorites.<br /><br />To add an individual, a family, or a source to your favorites, click on the <b>Add a new favorite</b> link to reveal some fields where you can enter or search for an ID number.  Instead of an ID number, you can enter a URL and a title.');
			} else {
				$content .= i18n::translate('At this moment there are no selected Favorites.	The admin can add Favorites to display at startup.');
			}
		} else {
			$content .= "<table width=\"{$tableWidth}\" style=\"border:none\" cellspacing=\"{$cellSpacing}\" class=\"center $TEXT_DIRECTION\">";
			foreach ($userfavs as $key=>$favorite) {
				if (isset($favorite["id"])) $key=$favorite["id"];
				$removeFavourite = "<a class=\"font9\" href=\"index.php?ctype=$ctype&amp;action=deletefav&amp;fv_id={$key}\" onclick=\"return confirm('".i18n::translate('Are you sure you want to remove this item from your list of Favorites?')."');\">".i18n::translate('Remove')."</a><br />";
				$content .= "<tr><td>";
				if ($favorite["type"]=="URL") {
					$content .= "<div id=\"boxurl".$key.".0\" class=\"person_box\">";
					if ($ctype=="user" || WT_USER_GEDCOM_ADMIN) $content .= $removeFavourite;
					$content .= "<a href=\"".$favorite["url"]."\"><b>".PrintReady($favorite["title"])."</b></a>";
					$content .= "<br />".PrintReady($favorite["note"]);
					$content .= "</div>";
				} else {
					$record=GedcomRecord::getInstance($favorite['gid']);
					if ($record && $record->canDisplayDetails()) {
						if ($favorite["type"]=="INDI") {
							$indirec = find_person_record($favorite["gid"], WT_GED_ID);
							$content .= "<div id=\"box".$favorite["gid"].".0\" class=\"person_box";
							if (strpos($indirec, "\n1 SEX F")!==false) $content .= "F";
							elseif (strpos($indirec, "\n1 SEX M")!==false) $content .= "";
							else $content .= "NN";
							$content .= "\">";
							if ($ctype=="user" || WT_USER_GEDCOM_ADMIN) $content .= $removeFavourite;
							ob_start();
							print_pedigree_person($favorite["gid"], $style, 1, $key);
							$content .= ob_get_clean();
							$content .= PrintReady($favorite["note"]);
							$content .= "</div>";
						} else {
							$record=GedcomRecord::getInstance($favorite['gid']);
							$content .= "<div id=\"box".$favorite["gid"].".0\" class=\"person_box\">";
							if ($ctype=="user" || WT_USER_GEDCOM_ADMIN) $content .= $removeFavourite;
							if ($record) {
								$content.=$record->format_list('span');
							} else {
								$content.=i18n::translate('No such ID exists in this GEDCOM file.');
							}
							$content .= "<br />".PrintReady($favorite["note"]);
							$content .= "</div>";
						}
					}
				}
				$content .= "</td></tr>";
			}
			$content .= "</table>";
		}
		if (WT_USER_GEDCOM_ADMIN) {
		$content .= '
			<script language="JavaScript" type="text/javascript">
			<!--
			var pastefield;
			function paste_id(value) {
				pastefield.value=value;
			}
			-->
			</script>
			<br />
			';
			$uniqueID = floor(microtime() * 1000000);
			$content .= "<b><a href=\"javascript://".i18n::translate('Add a new favorite')." \" onclick=\"expand_layer('add_ged_fav{$uniqueID}'); return false;\"><img id=\"add_ged_fav_img\" src=\"".$WT_IMAGES["plus"]."\" border=\"0\" alt=\"\" />&nbsp;".i18n::translate('Add a new favorite')."</a></b>";
			$content .= help_link('index_add_favorites');
			$content .= "<br /><div id=\"add_ged_fav{$uniqueID}\" style=\"display: none;\">";
			$content .= "<form name=\"addgfavform\" method=\"get\" action=\"index.php\">";
			$content .= "<input type=\"hidden\" name=\"action\" value=\"addfav\" />";
			$content .= "<input type=\"hidden\" name=\"ctype\" value=\"$ctype\" />";
			$content .= "<input type=\"hidden\" name=\"favtype\" value=\"gedcom\" />";
			$content .= "<input type=\"hidden\" name=\"ged\" value=\"".WT_GEDCOM."\" />";
			$content .= "<table width=\"{$tableWidth}\" style=\"border:none\" cellspacing=\"{$cellSpacing}\" class=\"center {$TEXT_DIRECTION}\">";
			$content .= "<tr><td>".i18n::translate('Enter a Person, Family, or Source ID')." <br />";
			$content .= "<input class=\"pedigree_form\" type=\"text\" name=\"gid\" id=\"gid{$uniqueID}\" size=\"5\" value=\"\" />";

			$content .= print_findindi_link("gid{$uniqueID}",'',true);
			$content .= print_findfamily_link("gid{$uniqueID}",'',true);
			$content .= print_findsource_link("gid{$uniqueID}",'',true);
			$content .= print_findrepository_link("gid{$uniqueID}",'',true);
			$content .= print_findnote_link("gid{$uniqueID}",'',true);
			$content .= print_findmedia_link("gid{$uniqueID}",'1','',true);

			$content .= "<br />".i18n::translate('OR<br />Enter a URL and a title');
			$content .= "<table><tr><td>".translate_fact('URL')."</td><td><input type=\"text\" name=\"url\" size=\"40\" value=\"\" /></td></tr>";
			$content .= "<tr><td>".i18n::translate('Title:')."</td><td><input type=\"text\" name=\"favtitle\" size=\"40\" value=\"\" /></td></tr></table>";
			if ($block) $content .= "</td></tr><tr><td><br />";
			else $content .= "</td><td>";
			$content .= "".i18n::translate('Enter an optional note about this favorite');
			$content .= "<br /><textarea name=\"favnote\" rows=\"6\" cols=\"50\"></textarea>";
			$content .= "</td></tr></table>";
			$content .= "<br /><input type=\"submit\" value=\"".i18n::translate('Add')."\" style=\"font-size: 8pt; \" />";
			$content .= "</form></div>";
		}

		if ($template) {
			if ($block) {
				require WT_THEME_DIR.'templates/block_small_temp.php';
			} else {
				require WT_THEME_DIR.'templates/block_main_temp.php';
			}
		} else {
			return $content;
		}
		// Restore GEDCOM configuration
		unset($show_full);
		if (isset($saveShowFull)) $show_full = $saveShowFull;
		$PEDIGREE_FULL_DETAILS = $savePedigreeFullDetails;
	}

	// Implement class WT_Module_Block
	public function loadAjax() {
		return false;
	}

	// Implement class WT_Module_Block
	public function isUserBlock() {
		return false;
	}

	// Implement class WT_Module_Block
	public function isGedcomBlock() {
		return true;
	}

	// Implement class WT_Module_Block
	public function configureBlock($block_id) {
		if (safe_POST_bool('save')) {
			set_block_setting($block_id, 'block',  safe_POST_bool('block'));
			echo WT_JS_START, 'window.opener.location.href=window.opener.location.href;window.close();', WT_JS_END;
			exit;
		}

		require_once WT_ROOT.'includes/functions/functions_edit.php';

		$block=get_block_setting($block_id, 'block', false);
		echo '<tr><td class="descriptionbox wrap width33">';
		echo /* I18N: label for a yes/no option */ i18n::translate('Add a scrollbar when block contents grow');
		echo '</td><td class="optionbox">';
		echo edit_field_yes_no('block', $block);
		echo '</td></tr>';
	}

	/**
	 * deleteFavorite
	 * deletes a favorite in the database
	 * @param int $fv_id the id of the favorite to delete
	 */
	public static function deleteFavorite($fv_id) {
		return (bool)
			WT_DB::prepare("DELETE FROM `##favorites` WHERE fv_id=?")
			->execute(array($fv_id));
	}

	/**
	 * stores a new favorite in the database
	 * @param array $favorite the favorite array of the favorite to add
	 */
	public static function addFavorite($favorite) {
		// -- make sure a favorite is added
		if (empty($favorite["gid"]) && empty($favorite["url"]))
			return false;

		//-- make sure this is not a duplicate entry
		$sql = "SELECT 1 FROM `##favorites` WHERE";
		if (!empty($favorite["gid"])) {
			$sql.=" fv_gid=?";
			$vars=array($favorite["gid"]);
		} else {
			$sql.=" fv_url=?";
			$vars=array($favorite["url"]);
		}
		$sql.=" AND fv_file=? AND fv_username=?";
		$vars[]=$favorite["file"];
		$vars[]=$favorite["username"];

		if (WT_DB::prepare($sql)->execute($vars)->fetchOne()) {
			return false;
		}

		//-- add the favorite to the database
		return (bool)
			WT_DB::prepare("INSERT INTO `##favorites` (fv_username, fv_gid, fv_type, fv_file, fv_url, fv_title, fv_note) VALUES (?, ? ,? ,? ,? ,? ,?)")
				->execute(array($favorite["username"], $favorite["gid"], $favorite["type"], $favorite["file"], $favorite["url"], $favorite["title"], $favorite["note"]));
	}

	/**
	 * Get a user's favorites
	 * Return an array of a users messages
	 * @param string $username the username to get the favorites for
	 */
	public static function getUserFavorites($username) {
		$rows=
			WT_DB::prepare("SELECT * FROM `##favorites` WHERE fv_username=?")
			->execute(array($username))
			->fetchAll();

		$favorites = array();
		foreach ($rows as $row) {
			if (get_id_from_gedcom($row->fv_file)) { // If gedcom exists
				$favorites[]=array(
					"id"=>$row->fv_id,
					"username"=>$row->fv_username,
					"gid"=>$row->fv_gid,
					"type"=>$row->fv_type,
					"file"=>$row->fv_file,
					"title"=>$row->fv_title,
					"note"=>$row->fv_note,
					"url"=>$row->fv_url
				);
			}
		}
		return $favorites;
	}
}
