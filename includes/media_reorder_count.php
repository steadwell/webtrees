<?php
/**
 * Provides media count for reorder media Items using drag and drop
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PHPGedView Development Team.  All rights reserved.
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
 * @package PhpGedView
 * @subpackage Module
 * @version $Id$
 * @author Brian Holland
 */
?>

<?php

// Find if indi and family associated media exists and then count them ( $tot_med_ct)  ===================================================
	// Check indi gedcom items
		$gedrec = find_gedcom_record($pid);
		$level=0;
		if ($level>0) $regexp = "/".$level." OBJE @(.*)@/";
		else $regexp = "/OBJE @(.*)@/";
		// $regexp = "/OBJE @(.*)@/";
		$ct_indi = preg_match_all($regexp, $gedrec, $match, PREG_SET_ORDER);
		for($i=0; $i<$ct_indi; $i++) {
			if (!isset($current_objes[$match[$i][1]])) $current_objes[$match[$i][1]] = 1;
			else $current_objes[$match[$i][1]]++;
			$obje_links[$match[$i][1]][] = $match[$i][0];
		}
	//-- Test if indi is related
	$ct = preg_match_all("/1 FAMS @(.*)@/", $gedrec, $match, PREG_SET_ORDER);
	if ($ct>0) {
		// find all the related ids
		$related=true;
		if ($related) {
			$ct = preg_match_all("/1 FAMS @(.*)@/", $gedrec, $match, PREG_SET_ORDER);
			for($i=0; $i<$ct; $i++) {
				$ids[] = trim($match[$i][1]);
			}
		}
		// Use database to get details of indi related items ---------------------------------------------
			$sqlmm = "SELECT DISTINCT ";
			// $sqlmm = "SELECT ";
			$sqlmm .= "m_media, m_ext, m_file, m_titl, m_gedfile, m_gedrec, mm_gid, mm_gedrec FROM ".$TBLPREFIX."media, ".$TBLPREFIX."media_mapping where ";
			$sqlmm .= "mm_gid IN (";
			$i=0;
			foreach($ids as $key=>$id) {
				if ($i>0) $sqlmm .= ",";
				$sqlmm .= "'".$DBCONN->escapeSimple($id)."'";
				$i++;
			}
			$sqlmm .= ") AND mm_gedfile = '".$GEDCOMS[$GEDCOM]["id"]."' AND mm_media=m_media AND mm_gedfile=m_gedfile ";
			//-- for family and source page only show level 1 obje references----------------------------------------
			$level=0;
			if ($level>0) {
				$sqlmm .= "AND mm_gedrec LIKE '$level OBJE%'";
			}
			// Order by -------------------------------------------------------
			$sqlmm .= " ORDER BY mm_gid DESC ";
			// Perform DB Query -----------------------
			$resmm = dbquery($sqlmm);
			// Get related media item count
			$ct_db = $resmm->numRows();
	//else if indi not related
	}else{
		// Get related media item count
		$ct_db = 0;
	}
	
	// Gedcom media count --------------------------------
	if (isset($current_objes)) {
		$ct_objs = count($current_objes);
	}else{
		$ct_objs = 0;
	}
//	$foundObjs = array();
	//Total Media count
	$tot_med_ct = ($ct_db + $ct_objs);

// Debug --------------------------------------------
//	echo "<br />";
//	print_r($current_objes);
//	echo "<br />";
//	echo "<br />";
//	echo "Objes count = " . $ct_objs . "<br />";
//	echo "Db count = " . $ct_db . "<br />";
//	echo "Indi count = " . $ct_indi . "<br /><br />";
//	echo "Total Media count = " . $tot_med_ct;
// =====================================================================================

?>