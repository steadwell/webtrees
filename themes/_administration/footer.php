<?php
/**
 * Footer for webtrees theme
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

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

echo '</div>';
// closing div id=\"content\"
echo '<div id="admin_footer">';
	echo
		'<div align="center"">',
			'<br />',
			'<a href="', WT_WEBTREES_URL, ' target="_blank">',
			'<img src="', $WT_IMAGES['webtrees'], '" width="100" border="0" alt="', WT_WEBTREES, WT_USER_IS_ADMIN? (" - " .WT_VERSION_TEXT): "", '"',
				'title="', WT_WEBTREES, WT_USER_IS_ADMIN? (" - " .WT_VERSION_TEXT): "" , '" /></a><br />',
			'<br />';

			if ($SHOW_STATS || WT_DEBUG) {
						echo execution_stats();
			}
	echo '</div>',
'</div>'; // close div id=\"footer\"
?>