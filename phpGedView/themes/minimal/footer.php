<?php
/**
 * Footer for Minimal theme
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2009  PGV Development Team.  All rights reserved.
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
 * @package webtrees
 * @subpackage Themes
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

echo "</div><!-- closing div id=\"content\" -->";
echo "<div id=\"footer\" class=\"$TEXT_DIRECTION\">";
echo "\n\t<br /><div align=\"center\" style=\"width:99%;\">";
echo contact_links();
echo '<a href="', PGV_PHPGEDVIEW_URL, '" target="_blank" alt="', PGV_PHPGEDVIEW, PGV_USER_IS_ADMIN? (" - " .PGV_VERSION_TEXT): "" , '" title="', PGV_PHPGEDVIEW , PGV_USER_IS_ADMIN? (" - " .PGV_VERSION_TEXT): "", '"><span style="font-size:150%; color:#888888;">', PGV_PHPGEDVIEW, '</span></a>';
echo "\n\t<br />";
echo '<a href="', PGV_SCRIPT_NAME, '?view=preview&amp;', get_query_string(), '">', i18n::translate('Printer-friendly Version'), '</a>';
echo help_link('preview');
echo "<br />";
if ($SHOW_STATS || PGV_DEBUG) {
	echo execution_stats();
}
if (exists_pending_change()) {
	echo "<br />", i18n::translate('Changes have been made to this GEDCOM.'), " <a href=\"javascript:;\" onclick=\"window.open('edit_changes.php', '_blank', 'width=600, height=500, resizable=1, scrollbars=1'); return false;\">", i18n::translate('Accept / Reject Changes'), "</a>\n";
}
echo "</div>";
echo "</div> <!-- close div id=\"footer\" -->\n";
?>
