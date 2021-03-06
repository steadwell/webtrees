<?php
/**
 * Reorder media Items using drag and drop
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
 * @subpackage Module
 * @version $Id$
 * @author Brian Holland
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('WT_MEDIA_REORDER_PHP', '');

require_once WT_ROOT.'includes/functions/functions_print_facts.php';

	echo "<br /><b>".i18n::translate('Re-order media')."</b>";
	echo "&nbsp --- &nbsp;" . i18n::translate('Click a row, then drag-and-drop to re-order media ');

	global $MULTI_MEDIA, $MEDIA_EXTERNAL, $MEDIATYPE;
	global $WORD_WRAPPED_NOTES, $MEDIA_DIRECTORY, $WT_IMAGES, $TEXT_DIRECTION;
	global $is_media, $cntm1, $cntm2, $cntm3, $cntm4, $t, $mgedrec;
	global $edit, $tabno, $currtab;
	global $ids, $pid, $related, $level, $gedrec, $media_data, $order, $order1, $order2, $j;

	?>
	<form name="reorder_form" method="post" action="edit_interface.php">
		<input type="hidden" name="action" value="reorder_media_update" />
		<input type="hidden" name="pid" value="<?php echo $pid; ?>" />
		<input type="hidden" name="currtab" value="<?php echo $currtab; ?>" />
<!-- <input type="hidden" name="option" value="bybirth" /> -->

		<p><center>
		<button type="submit" title="<?php echo i18n::translate('Saves the sorted media to the database'); ?>"><?php echo i18n::translate('Save'); ?></button>
		<button type="submit" title="<?php echo i18n::translate('Reset to the original order'); ?>" onclick="document.reorder_form.action.value='reset_media_update'; document.reorder_form.submit();"><?php echo i18n::translate('Reset'); ?></button>
		<button type="submit" title="<?php echo i18n::translate('Quit and return'); ?>" onclick="window.close();"><?php echo i18n::translate('Cancel'); ?></button>
		</center>
<ul id="reorder_media_list">
	<?php
	$gedrec = find_gedcom_record($pid, WT_GED_ID, true);

	//related=true means show related items
	$related="true";

	//-- find all of the related ids
	$ids = array($pid);
	if ($related) {
		$ct = preg_match_all("/1 FAMS @(.*)@/", $gedrec, $match, PREG_SET_ORDER);
		for ($i=0; $i<$ct; $i++) {
			$ids[] = trim($match[$i][1]);
		}
	}

	//-- If  they exist, get a list of the sorted current objects in the indi gedcom record  -  (1 _WT_OBJE_SORT @xxx@ .... etc) ----------
	$sort_current_objes = array();
	if ($level>0) $sort_regexp = "/".$level." _WT_OBJE_SORT @(.*)@/";
	else $sort_regexp = "/_WT_OBJE_SORT @(.*)@/";
	$sort_ct = preg_match_all($sort_regexp, $gedrec, $sort_match, PREG_SET_ORDER);
	for ($i=0; $i<$sort_ct; $i++) {
		if (!isset($sort_current_objes[$sort_match[$i][1]])) $sort_current_objes[$sort_match[$i][1]] = 1;
		else $sort_current_objes[$sort_match[$i][1]]++;
		$sort_obje_links[$sort_match[$i][1]][] = $sort_match[$i][0];
	}
	// -----------------------------------------------------------------------------------------------

	// create ORDER BY list from Gedcom sorted records list  ---------------------------
	$orderbylist = 'ORDER BY '; // initialize
	foreach ($sort_match as $media_id) {
		$orderbylist .= "m_media='$media_id[1]' DESC, ";
	}
	$orderbylist = rtrim($orderbylist, ', ');
	//  print_r($orderbylist);
	// -----------------------------------------------------------------------------------------------

	//-- get a list of the current objects in the record
	$current_objes = array();
	if ($level>0) $regexp = "/".$level." OBJE @(.*)@/";
	else $regexp = "/OBJE @(.*)@/";
	$ct = preg_match_all($regexp, $gedrec, $match, PREG_SET_ORDER);
	for ($i=0; $i<$ct; $i++) {
		if (!isset($current_objes[$match[$i][1]])) $current_objes[$match[$i][1]] = 1;
		else $current_objes[$match[$i][1]]++;
		$obje_links[$match[$i][1]][] = $match[$i][0];
	}

	$media_found = false;

	$sqlmm = "SELECT DISTINCT ";
	$sqlmm .= "m_media, m_ext, m_file, m_titl, m_gedfile, m_gedrec, mm_gid, mm_gedrec FROM `##media`, `##media_mapping` WHERE ";
	$sqlmm .= "mm_gid IN (";
	$i=0;
	$vars=array();
	foreach ($ids as $key=>$media_id) {
		if ($i>0) $sqlmm .= ",";
		$sqlmm .= "?";
		$vars[]=$media_id;
		$i++;
	}
	$sqlmm .= ") AND mm_gedfile=? AND mm_media=m_media AND mm_gedfile=m_gedfile ";
	$vars[]=WT_GED_ID;
	//-- for family and source page only show level 1 obje references
	if ($level>0) {
		$sqlmm .= "AND mm_gedrec LIKE ?";
		$vars[]="{$level} OBJE%";
	}


	if ($sort_ct>0) {
		$sqlmm .= $orderbylist;
	} else {
		$sqlmm .= " ORDER BY mm_gid DESC ";
	}

	$rows=WT_DB::prepare($sqlmm)->execute($vars)->fetchAll(PDO::FETCH_ASSOC);

	$foundObjs = array();
	foreach ($rows as $rowm) {
		if (isset($foundObjs[$rowm['m_media']])) {
			if (isset($current_objes[$rowm['m_media']])) $current_objes[$rowm['m_media']]--;
			continue;
		}
		// NOTE: Determine the size of the mediafile
		$imgwidth = 300+40;
		$imgheight = 300+150;
		if (preg_match("'://'", $rowm["m_file"])) {
			if (in_array($rowm["m_ext"], $MEDIATYPE)) {
				$imgwidth = 400+40;
				$imgheight = 500+150;
			} else {
				$imgwidth = 800+40;
				$imgheight = 400+150;
			}
		}
		else if (file_exists(filename_decode(check_media_depth($rowm["m_file"], "NOTRUNC")))) {
			$imgsize = findImageSize(check_media_depth($rowm["m_file"], "NOTRUNC"));
			$imgwidth = $imgsize[0]+40;
			$imgheight = $imgsize[1]+150;
		}
		$rows = array();
		$rows['normal'] = $rowm;
		if (isset($current_objes[$rowm['m_media']])) $current_objes[$rowm['m_media']]--;
		foreach ($rows as $rtype => $rowm) {
			$res = media_reorder_row($rtype, $rowm, $pid);
			$media_found = $media_found || $res;
			$foundObjs[$rowm['m_media']] = true;
			$j++;
		}
	}
	?>
</ul>
<?php
?>
	<script type="text/javascript" language="javascript">
	// <![CDATA[
		new Effect.BlindDown('reorder_media_list', {duration: .5});
		Sortable.create('reorder_media_list',
			{
				scroll:window,
				onUpdate : function() {
					inputs = $('reorder_media_list').getElementsByTagName('input');
					for (var i = 0; i < inputs.length; i++) {
						inputs[i].value = i;
					}
				}
			}
		);
	// ]]>
	</script>
	<center>
	<?php
	if (WT_USER_IS_ADMIN) {
		echo "<table width=97%><tr><td class=\"descriptionbox ", $TEXT_DIRECTION, " wrap width25\">";
		echo i18n::translate('Admin Option'), help_link('no_update_CHAN'), "</td><td class=\"optionbox ", $TEXT_DIRECTION, " wrap\">";
		if ($NO_UPDATE_CHAN) {
			echo "<input type=\"checkbox\" checked=\"checked\" name=\"preserve_last_changed\" />";
		} else {
			echo "<input type=\"checkbox\" name=\"preserve_last_changed\" />";
		}
		echo i18n::translate('Do not update the CHAN (Last Change) record'), "<br />";
		$event = new Event(get_sub_record(1, "1 CHAN", $gedrec));
		echo format_fact_date($event, false, true);
		echo "</td></tr></table><br />";
	}
	?>
	<button type="submit" title="<?php echo i18n::translate('Saves the sorted media to the database'); ?>"><?php echo i18n::translate('Save'); ?></button>
	<button type="submit" title="<?php echo i18n::translate('Reset to the original order'); ?>" onclick="document.reorder_form.action.value='reset_media_update'; document.reorder_form.submit();"><?php echo i18n::translate('Reset'); ?></button>
	<button type="submit" title="<?php echo i18n::translate('Quit and return'); ?>" onclick="window.close();"><?php echo i18n::translate('Cancel'); ?></button>
	</center></p>
	</form>
