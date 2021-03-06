<?php
/**
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2002 to 2010  PGV Development Team.  All rights reserved.
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
 * @author Dparker
 * @package webtrees
 * @subpackage Admin
 * @version $Id$
 */

define('WT_SCRIPT_NAME', 'dir_editor.php');
require './includes/session.php';
require WT_ROOT.'includes/functions/functions_edit.php';

if (!WT_USER_IS_ADMIN) {
	header('Location: '.WT_SERVER_NAME.WT_SCRIPT_PATH.'login.php?url='.WT_SCRIPT_NAME);
	exit;
}

$INDEX_DIRECTORY=get_site_setting('INDEX_DIRECTORY');

function full_rmdir($dir) {
	if (!is_writable($dir)) {
		if (!@chmod($dir, WT_PERM_EXE)) {
			return FALSE;
		}
	}

	$d = dir($dir);
	while (FALSE !== ($entry = $d->read())) {
		if ($entry == '.' || $entry == '..') {
			continue;
		}
		$entry = $dir . '/' . $entry;
		if (is_dir($entry)) {
			if (!full_rmdir($entry)) {
				return FALSE;
			}
			continue;
		}
		if (!@unlink($entry)) {
			$d->close();
			return FALSE;
		}
	}

	$d->close();
	rmdir($dir);
	return TRUE;
}

// Vars
$ajaxdeleted = false;
$locked_by_context = array('index.php', 'config.ini.php');

// If we are storing the media in the data directory (this is the
// default for the media firewall), then don't delete it.
if (
	$USE_MEDIA_FIREWALL &&
	$MEDIA_FIREWALL_ROOTDIR==$INDEX_DIRECTORY &&
	(substr($MEDIA_DIRECTORY, 0, 1)!='.')
) {
	$locked_by_context[]=trim($MEDIA_DIRECTORY, '/');
}

print_header(i18n::translate('Cleanup data directory'));
echo
	'<p class="center">',
	'<input type="button" value="', i18n::translate('Return to Administration page'), '" onclick="javascript:window.location=\'admin.php\'" />',
	'</p>',
	'<h2 class="center">', i18n::translate('Cleanup data directory'), '</h2>',
	'<p>',
	i18n::translate('To delete a file or subdirectory from the data directory drag it to the wastebasket or select its checkbox.  Click the Delete button to permanently remove the indicated files.'),
	'</p><p>',
	i18n::translate('Files marked with %s are required for proper operation and cannot be removed.', '<img src="./images/RESN_confidential.gif" alt="" />'),
	'</p>';

//post back
if (isset($_REQUEST['to_delete'])) {
	echo '<div class="error">', i18n::translate('Deleted files:'), '</div>';
	foreach ($_REQUEST['to_delete'] as $k=>$v) {
		if (is_dir($INDEX_DIRECTORY.$v)) {
			full_rmdir($INDEX_DIRECTORY.$v);
		} elseif (file_exists($INDEX_DIRECTORY.$v)) {
			unlink($INDEX_DIRECTORY.$v);
		}
		echo '<div class="error">', $v, '</div>';
	}
}

echo '<form name="delete_form" method="post" action=""><table><tr><td>';

$dir=dir($INDEX_DIRECTORY);
$entries=array();
while (false !== ($entry=$dir->read())) {
	$entries[]=$entry;
}
sort($entries);
foreach ($entries as $entry) {
	if ($entry[0] != '.') {
		echo '<div class="facts_value" name="', $entry, '">';
		if (in_array($entry, $locked_by_context)) {
			echo '<img src="./images/RESN_confidential.gif" alt="" /> <span class="name2">', $entry, '</span>';
		} else {
			echo '<input type="checkbox" name="to_delete[]" value="', $entry, '" />', $entry;
		}
		echo '</div>';
	}
}
$dir->close();
echo
	'</td><td valign="top" id="trash" class="facts_value02">',
	'<div style="margin-bottom:2px;">',
	'<table><tr><td>',
	'<img src="', $WT_IMAGES['trashcan'], '" align="left" alt="" />',
	'</td>',
	'<td valign="top"><ul id="trashlist">',
	'</ul></td></tr></table>',
	'</div>',
	WT_JS_START,
	'function ul_clear() {',
	' var elements=document.getElementsByName("to_delete[]");',
	' for (i=0; i<elements.length; i++) {',
	'  elements[i].checked=false;',
	' }',
	'}',
	'function removeAll() {',
	' var elements=document.getElementsByName("to_delete[]");',
	' for (i=0; i<elements.length; i++) {',
	'  elements[i].checked=true;',
	' }',
	'	document.delete_form.submit();',
	'}',
	WT_JS_END,
	'<button type="submit">', i18n::translate('Delete'), '</button>',
	'<button type="button" onclick="ul_clear(); return false;">', i18n::translate('Cancel'), '</button><br /><br />',
	'<button type="button" onclick="removeAll(); return false;">', i18n::translate('Remove all nonessential files'), '</button>',
	'</td></tr></table></form>';

print_footer();
