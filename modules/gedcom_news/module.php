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
	WT_DB::updateSchema('./modules/gedcom_news/db_schema/', 'NB_SCHEMA_VERSION', 1);
} catch (PDOException $ex) {
	// The schema update scripts should never fail.  If they do, there is no clean recovery.
	die($ex);
}

class gedcom_news_WT_Module extends WT_Module implements WT_Module_Block {
	// Extend class WT_Module
	public function getTitle() {
		return i18n::translate('GEDCOM News');
	}

	// Extend class WT_Module
	public function getDescription() {
		return i18n::translate('The GEDCOM News block shows the visitor news releases or articles posted by an admin user.<br /><br />The News block is a good place to announce a significant database update, a family reunion, or the birth of a child.');
	}

	// Implement class WT_Module_Block
	public function getBlock($block_id, $template=true, $cfg=null) {
		global $WT_IMAGES, $TEXT_DIRECTION, $ctype;

		switch (safe_GET('action')) {
		case 'deletenews':
			$news_id=safe_GET('news_id');
			if ($news_id) {
				deleteNews($news_id);
			}
			break;
		}

		if (isset($_REQUEST['gedcom_news_archive'])) {
			$limit='nolimit';
			$flag=0;
		} else {
			$flag=get_block_setting($block_id, 'flag', 0);
			if ($flag==0) {
				$limit='nolimit';
			} else {
				$limit=get_block_setting($block_id, 'limit', 'nolimit');
			}
		}
		if ($cfg) {
			foreach (array('limit', 'flag') as $name) {
				if (array_key_exists($name, $cfg)) {
					$$name=$cfg[$name];
				}
			}
		}

		$usernews = getUserNews(WT_GEDCOM);

		$id=$this->getName().$block_id;
		$title='';
		if ($ctype=="gedcom" && WT_USER_GEDCOM_ADMIN || $ctype=="user" && WT_USER_ID) {
			if ($ctype=="gedcom") {
				$name = WT_GEDCOM;
			} else {
				$name = WT_USER_NAME;
			}
			$title.="<a href=\"javascript: configure block\" onclick=\"window.open('index_edit.php?action=configure&amp;ctype={$ctype}&amp;block_id={$block_id}', '_blank', 'top=50,left=50,width=600,height=350,scrollbars=1,resizable=1'); return false;\">";
			$title.="<img class=\"adminicon\" src=\"".$WT_IMAGES["admin"]."\" width=\"15\" height=\"15\" border=\"0\" alt=\"".i18n::translate('Configure')."\" /></a>";
		}
		$title .= i18n::translate('News');
		if (WT_USER_GEDCOM_ADMIN) {
			$title .= help_link('index_gedcom_news_adm');
		} else {
			$title .= help_link('index_gedcom_news');
		}
		$content = "";
		if (count($usernews) == 0)
		{
			$content .= i18n::translate('No News articles have been submitted.').'<br />';
		}
		$c = 0;
		$td = time();
		foreach ($usernews as $news)
		{
			if ($limit=='count') {
				if ($c >= $flag) {
					break;
				}
				$c++;
			}
			if ($limit=='date') {
				if (floor(($td - $news['date']) / 86400) > $flag) {
					break;
				}
			}
			$content .= "<div class=\"news_box\" id=\"{$news['anchor']}\">";

			// Look for $GLOBALS substitutions in the News title
			$newsTitle = embed_globals($news['title']);
			$content .= "<div class=\"news_title\">".PrintReady($newsTitle)."</div>";
			$content .= "<div class=\"news_date\">".format_timestamp($news['date'])."</div>";
			if ($news["text"]==strip_tags($news["text"])) {
				// No HTML?
				$news["text"]=nl2br($news["text"]);
			}
			$content .= embed_globals($news["text"]);
			// Print Admin options for this News item
			if (WT_USER_GEDCOM_ADMIN) {
				$content .= "<hr size=\"1\" />"
				."<a href=\"javascript:;\" onclick=\"editnews('".$news['id']."'); return false;\">".i18n::translate('Edit')."</a> | "
				."<a href=\"index.php?action=deletenews&amp;news_id=".$news['id']."&amp;ctype={$ctype}\" onclick=\"return confirm('".i18n::translate('Are you sure you want to delete this News entry?')."');\">".i18n::translate('Delete')."</a><br />";
			}
			$content .= "</div>";
		}
		$printedAddLink = false;
		if (WT_USER_GEDCOM_ADMIN) {
			$content .= "<a href=\"javascript:;\" onclick=\"addnews('".WT_GEDURL."'); return false;\">".i18n::translate('Add a News article')."</a>";
			$printedAddLink = true;
		}
		if ($limit=='date' || $limit=='count') {
			if ($printedAddLink) $content .= "&nbsp;&nbsp;|&nbsp;&nbsp;";
			$content .= "<a href=\"index.php?gedcom_news_archive=yes&amp;ctype={$ctype}\">".i18n::translate('View archive')."</a>";
			$content .= help_link('gedcom_news_archive').'<br />';
		}

		if ($template) {
			require WT_THEME_DIR.'templates/block_main_temp.php';
		} else {
			return $content;
		}
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
			set_block_setting($block_id, 'limit', safe_POST('limit'));
			set_block_setting($block_id, 'flag',  safe_POST('flag'));
			echo WT_JS_START, 'window.opener.location.href=window.opener.location.href;window.close();', WT_JS_END;
			exit;
		}

		require_once WT_ROOT.'includes/functions/functions_edit.php';


		// Limit Type
		$limit=get_block_setting($block_id, 'limit', 'nolimit');
		echo
			'<tr><td class="descriptionbox wrap width33">',
			i18n::translate('Limit display by:'), help_link('gedcom_news_limit'),
			'</td><td class="optionbox"><select name="limit"><option value="nolimit"',
			($limit == 'nolimit'?' selected="selected"':'').">",
			i18n::translate('No limit')."</option>",
			'<option value="date"'.($limit == 'date'?' selected="selected"':'').">".i18n::translate('Age of item')."</option>",
			'<option value="count"'.($limit == 'count'?' selected="selected"':'').">".i18n::translate('Number of items')."</option>",
			'</select></td></tr>';

		// Flag to look for
		$flag=get_block_setting($block_id, 'flag', 0);
		echo '<tr><td class="descriptionbox wrap width33">';
		echo i18n::translate('Limit:'), help_link('gedcom_news_flag');
		echo '</td><td class="optionbox"><input type="text" name="flag" size="4" maxlength="4" value="'.$flag.'" /></td></tr>';
	}
}
