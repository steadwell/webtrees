<?php
/**
* Returns data for autocompletion
*
* phpGedView: Genealogy Viewer
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
* @package PhpGedView
* @subpackage Edit
* @version $Id$
*/

require './config.php';
header("Content-Type: text/html; charset=$CHARACTER_SET");

// We have finished writing to $_SESSION, so release the lock
session_write_close();

//-- args
$FILTER = @$_GET["q"];
if (has_utf8($FILTER)) {
	$FILTER = UTF8_strtoupper($FILTER);
}
$FILTER = $DBCONN->escapeSimple($FILTER);

$OPTION = @$_GET["option"];

$field = @$_GET["field"];
if (!function_exists("autocomplete_{$field}")) {
	die("Bad arg: field={$field}");
}

//-- database query
define('PGV_AUTOCOMPLETE_LIMIT', 500);
eval("\$data = autocomplete_".$field."();");
if (empty($data)) {
	die();
}

//-- sort
$data = array_unique($data);
uasort($data, "stringsort");

//-- output
foreach ($data as $k=>$v) {
	echo "$v|$k\n";
}
exit;

/**
* returns INDIviduals matching filter
* @return Array of string
*/
function autocomplete_INDI() {
	global $TBLPREFIX, $FILTER, $OPTION, $pgv_lang, $MAX_ALIVE_AGE;

	// when adding ASSOciate $OPTION may contain :
	// current INDI/FAM [, current event date]
	if ($OPTION) {
		list($pid, $event_date) = explode("|", $OPTION."|");
		$record=GedcomRecord::getInstance($pid); // INDI or FAM
		$tmp=new GedcomDate($event_date);
		$event_jd=$tmp->JD();
		// INDI
		$indi_birth_jd = 0;
		if ($record && $record->getType()=="INDI") {
			$indi_birth_jd=$record->getEstimatedBirthDate()->minJD();
		}
		// HUSB & WIFE
		$husb_birth_jd = 0;
		$wife_birth_jd = 0;
		if ($record && $record->getType()=="FAM") {
			$husb=$record->getHusband();
			if ($husb) {
				$husb_birth_jd = $husb->getEstimatedBirthDate()->minJD();
			}
			$wife=$record->getWife();
			if ($wife) {
				$wife_birth_jd = $wife->getEstimatedBirthDate()->minJD();
			}
		}
	}

	$sql=
		"SELECT 'INDI' AS type, i_id AS xref, i_file AS ged_id, i_gedcom AS gedrec, i_isdead, i_sex".
		" FROM {$TBLPREFIX}individuals, {$TBLPREFIX}name".
		" WHERE n_sort ".PGV_DB_LIKE." '%".UTF8_strtoupper($FILTER)."%'".
		" AND i_id=n_id AND i_file=n_file AND i_file=".PGV_GED_ID.
		" ORDER BY n_sort";
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data=array();
	while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$person=Person::getInstance($row);
		if ($person->canDisplayName()) {
			// filter ASSOciate
			if ($OPTION && $event_jd) {
				// no self-ASSOciate
				if ($pid && $person->getXref()==$pid) {
					continue;
				}
				// filter by birth date
				$person_birth_jd=$person->getEstimatedBirthDate()->minJD();
				if ($person_birth_jd) {
					// born after event or not a contemporary
					if ($event_jd && $person_birth_jd>$event_jd) {
						continue;
					} elseif ($indi_birth_jd && abs($indi_birth_jd-$person_birth_jd)>$MAX_ALIVE_AGE*365) {
						continue;
					} elseif ($husb_birth_jd && $wife_birth_jd && abs($husb_birth_jd-$person_birth_jd)>$MAX_ALIVE_AGE*365 && abs($wife_birth_jd-$person_birth_jd)>$MAX_ALIVE_AGE*365) {
						continue;
					} elseif ($husb_birth_jd && abs($husb_birth_jd-$person_birth_jd)>$MAX_ALIVE_AGE*365) {
						continue;
					} elseif ($wife_birth_jd && abs($wife_birth_jd-$person_birth_jd)>$MAX_ALIVE_AGE*365) {
						continue;
					}
				}
				// filter by death date
				$person_death_jd=$person->getEstimatedDeathDate()->MaxJD();
				if ($person_death_jd) {
					// dead before event or not a contemporary
					if ($event_jd && $person_death_jd<$event_jd) {
						continue;
					} elseif ($indi_birth_jd && $person_death_jd<$indi_birth_jd) {
						continue;
					} elseif ($husb_birth_jd && $wife_birth_jd && $person_death_jd<$husb_birth_jd && $person_death_jd<$wife_birth_jd) {
						continue;
					}	elseif ($husb_birth_jd && $person_death_jd<$husb_birth_jd) {
						continue;
					} elseif ($wife_birth_jd && $person_death_jd<$wife_birth_jd) {
						continue;
					}
				}
			}
			// display
			$data[$person->getXref()]=check_NN($person->getFullName());
			if ($OPTION && $event_date && $person->getBirthDate()->isOK()) {
				$data[$person->getXref()].=" <span class=\"age\">(".$pgv_lang["age"]." ".$person->getBirthDate()->MinDate()->getAge(false, $event_jd).")</span>";
			} else {
				$data[$person->getXref()].=" <u>".ltrim($person->getBirthYear(), "0")."-".ltrim($person->getDeathYear(), "0")."</u>";
			}
		}
	}
	$res->free();
	return $data;
}

/**
* returns FAMilies matching filter
* @return Array of string
*/
function autocomplete_FAM() {
	global $TBLPREFIX, $DBCONN, $FILTER;

	//-- search for INDI names
	$ids = array();
	foreach (autocomplete_INDI() as $k=>$v) {
		$ids[] = "'".$DBCONN->escapeSimple($k)."'";
	}

	if (empty($ids)) {
		//-- no match : search for FAM id
		$where = "f_id ".PGV_DB_LIKE." '%{$FILTER}%'";
	} else {
		//-- search for spouses
		$where = "(f_husb IN (".implode(',', $ids).") OR f_wife IN (".implode(',', $ids).") )";
	}

	$sql="SELECT 'FAM' AS type, f_id AS xref, f_file AS ged_id, f_gedcom AS gedrec, f_husb, f_wife, f_chil, f_numchil FROM {$TBLPREFIX}families WHERE {$where} AND f_file=".PGV_GED_ID;
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$family = Family::getInstance($row);
		if ($family->canDisplayName()) {
			$data[$row["xref"]] =
				$family->getFullName().
				" <u>".
				ltrim($family->getMarriageYear(), "0").
				"</u>";
		}
	}
	$res->free();
	return $data;
}

/**
* returns NOTEs (Shared) matching filter
* @return Array of string
*/
function autocomplete_NOTE() {
	global $TBLPREFIX, $FILTER;

	$sql="SELECT o_type AS type, o_id AS xref, o_file AS ged_id, o_gedcom AS gedrec FROM {$TBLPREFIX}other WHERE o_gedcom ".PGV_DB_LIKE." '%{$FILTER}%' AND o_type='NOTE' AND o_file=".PGV_GED_ID;
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$note = Note::getInstance($row);
		if ($note->canDisplayName()) {
			$data[$row["xref"]] = $note->getFullName();
		}
	}
	$res->free();
	return $data;
}

/**
* returns SOURces matching filter
* @return Array of string
*/
function autocomplete_SOUR() {
	global $TBLPREFIX, $FILTER;

	$sql="SELECT 'SOUR' AS type, s_id AS xref, s_file AS ged_id, s_gedcom AS gedrec FROM {$TBLPREFIX}sources WHERE (s_name ".PGV_DB_LIKE." '%{$FILTER}%' OR s_id ".PGV_DB_LIKE." '%{$FILTER}%') AND s_file=".PGV_GED_ID." ORDER BY s_name";
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$source = Source::getInstance($row);
		if ($source->canDisplayName()) {
			$data[$row["xref"]] = $source->getFullName();
		}
	}
	$res->free();
	return $data;
}

/**
* returns SOUR:TITL matching filter
* @return Array of string
*/
function autocomplete_SOUR_TITL() {
	global $TBLPREFIX, $FILTER;

	$sql="SELECT 'SOUR' AS type, s_id AS xref, s_file AS ged_id, s_gedcom AS gedrec FROM {$TBLPREFIX}sources WHERE s_name ".PGV_DB_LIKE." '%{$FILTER}%' AND s_file=".PGV_GED_ID." ORDER BY s_name";
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$source = Source::getInstance($row);
		if ($source->canDisplayName()) {
			$data[] = $source->getFullName();
		}
	}
	$res->free();
	return $data;
}

/**
* returns INDI_BURI_CEME matching filter
* @return Array of string
*/
function autocomplete_INDI_BURI_CEME() {
	global $TBLPREFIX, $FILTER, $OPTION;

	$sql = "SELECT 'INDI' AS type, i_id AS xref, i_file AS ged_id, i_gedcom AS gedrec, i_isdead, i_sex".
				" FROM {$TBLPREFIX}individuals".
				" WHERE (i_gedcom ".PGV_DB_LIKE." '%1 BURI%2 CEME %{$FILTER}%')".
				" AND i_file=".PGV_GED_ID;
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$person = Person::getInstance($row);
		if ($person->canDisplayDetails()) {
			$i = 1;
			do {
				$srec = get_sub_record("BURI", 1, $person->gedrec, $i++);
				$ceme = get_gedcom_value("CEME", 2, $srec);
				if (stripos($ceme, $FILTER)!==false || empty($FILTER)) {
					$data[] = $ceme;
				}
			} while ($srec);
		}
	}
	$res->free();
	return $data;
}

/**
* returns INDI:SOUR:PAGE matching filter
* @return Array of string
*/
function autocomplete_INDI_SOUR_PAGE() {
	global $TBLPREFIX, $FILTER, $OPTION;

	$sql=
		"SELECT 'INDI' AS type, i_id AS xref, i_file AS ged_id, i_gedcom AS gedrec, i_isdead, i_sex FROM {$TBLPREFIX}individuals".
		" WHERE (i_gedcom ".PGV_DB_LIKE." '%1 SOUR @{$OPTION}@%2 PAGE %{$FILTER}%'".
		" OR     i_gedcom ".PGV_DB_LIKE." '%2 SOUR @{$OPTION}@%3 PAGE %{$FILTER}%')".
		" AND i_file=".PGV_GED_ID;
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$person = Person::getInstance($row);
		if ($person->canDisplayDetails()) {
			// a single INDI may have multiple level 1 and level 2 sources
			for ($level=1; $level<=2; $level++) {
				$i = 1;
				do {
					$srec = get_sub_record("SOUR @{$OPTION}@", $level, $person->gedrec, $i++);
					$page = get_gedcom_value("PAGE", $level+1, $srec);
					if (stripos($page, $FILTER)!==false || empty($FILTER)) {
						$data[] = $page;
					}
				} while ($srec);
			}
		}
	}
	$res->free();
	return $data;
}

/**
* returns FAM:SOUR:PAGE matching filter
* @return Array of string
*/
function autocomplete_FAM_SOUR_PAGE() {
	global $TBLPREFIX, $FILTER, $OPTION;

	$sql=
		"SELECT 'FAM' AS type, f_id AS xref, f_file AS ged_id, f_gedcom AS gedrec, f_husb, f_wife, f_chil, f_numchil FROM {$TBLPREFIX}families".
		" WHERE (f_gedcom ".PGV_DB_LIKE." '%1 SOUR @{$OPTION}@%2 PAGE %{$FILTER}%'".
		" OR     f_gedcom ".PGV_DB_LIKE." '%2 SOUR @{$OPTION}@%3 PAGE %{$FILTER}%')".
		" AND f_file=".PGV_GED_ID;
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$family = Family::getInstance($row);
		if ($family->canDisplayDetails()) {
			// a single FAM may have multiple level 1 and level 2 sources
			for ($level=1; $level<=2; $level++) {
				$i = 1;
				do {
					$srec = get_sub_record("SOUR @{$OPTION}@", $level, $family->gedrec, $i++);
					$page = get_gedcom_value("PAGE", $level+1, $srec);
					if (stripos($page, $FILTER)!==false || empty($FILTER)) {
						$data[] = $page;
					}
				} while ($srec);
			}
		}
	}
	$res->free();
	return $data;
}

/**
* returns SOUR:PAGE matching filter
* @return Array of string
*/
function autocomplete_SOUR_PAGE() {
	return array_merge(
		autocomplete_INDI_SOUR_PAGE(),
		autocomplete_FAM_SOUR_PAGE());
}

/**
* returns REPOsitories matching filter
* @return Array of string
*/
function autocomplete_REPO() {
	global $TBLPREFIX, $FILTER;

	$sql = "SELECT o_type AS type, o_id AS xref, o_file AS ged_id, o_gedcom AS gedrec".
				" FROM {$TBLPREFIX}other".
				" WHERE (o_gedcom ".PGV_DB_LIKE." '%1 NAME %".$FILTER."%\n%'".
				" OR o_id ".PGV_DB_LIKE." '%".$FILTER."%')".
				" AND o_file=".PGV_GED_ID.
				" AND o_type='REPO'";
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$repository = Repository::getInstance($row);
		if ($repository->canDisplayName()) {
			$data[$row["xref"]] = $repository->getFullName();
		}
	}
	$res->free();
	return $data;
}

/**
* returns REPO:NAME matching filter
* @return Array of string
*/
function autocomplete_REPO_NAME() {
	global $TBLPREFIX, $FILTER;

	$sql = "SELECT o_type AS type, o_id AS xref, o_file AS ged_id, o_gedcom AS gedrec".
				" FROM {$TBLPREFIX}other".
				" WHERE o_gedcom ".PGV_DB_LIKE." '%1 NAME %".$FILTER."%\n%'".
				" AND o_file=".PGV_GED_ID.
				" AND o_type='REPO'".
				" LIMIT ".PGV_AUTOCOMPLETE_LIMIT;
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$repository = Repository::getInstance($row);
		if ($repository->canDisplayName()) {
			$data[] = $repository->getFullName();
		}
	}
	$res->free();
	return $data;
}

/**
* returns OBJEcts matching filter
* @return Array of string
*/
function autocomplete_OBJE() {
	global $TBLPREFIX, $FILTER;

	$sql="SELECT m_media FROM {$TBLPREFIX}media WHERE (m_titl ".PGV_DB_LIKE." '%{$FILTER}%' OR m_media ".PGV_DB_LIKE." '%{$FILTER}%') AND m_gedfile=".PGV_GED_ID;
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data = array();
	while ($row =& $res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$media = Media::getInstance($row["m_media"]);
		if ($media && $media->canDisplayDetails()) {
			$data[$row["m_media"]] =
				"<img alt=\"".
				$media->getXref().
				"\" src=\"".
				$media->getThumbnail().
				"\" width=\"40\" /> ".
				$media->getFullName();
		}
	}
	$res->free();
	return $data;
}

/**
* returns INDI FAM SOUR NOTE REPO OBJE matching filter
* @return Array of string
*/
function autocomplete_IFSRO() {
	global $GEDCOM_ID_PREFIX, $FAM_ID_PREFIX, $SOURCE_ID_PREFIX, $NOTE_ID_PREFIX, $REPO_ID_PREFIX, $MEDIA_ID_PREFIX, $FILTER;

	// is input text a gedcom xref ?
	$prefix = strtoupper(substr($FILTER, 0, 1));
	if (ctype_digit(substr($FILTER, 1))) {
		if ($prefix == $GEDCOM_ID_PREFIX)
			return autocomplete_INDI();
		if ($prefix == $FAM_ID_PREFIX)
			return autocomplete_FAM();
		if ($prefix == $SOURCE_ID_PREFIX)
			return autocomplete_SOUR();
		if ($prefix == $NOTE_ID_PREFIX)
			return autocomplete_NOTE();
		if ($prefix == $REPO_ID_PREFIX)
			return autocomplete_REPO();
		if ($prefix == $MEDIA_ID_PREFIX)
			return autocomplete_OBJE();
	}
	return array_merge(
		autocomplete_INDI(),
		autocomplete_FAM(),
		autocomplete_SOUR(),
		autocomplete_NOTE(),
		autocomplete_REPO(),
		autocomplete_OBJE());
}

/**
* returns SURNames matching filter
* @return Array of string
*/
function autocomplete_SURN() {
	global $TBLPREFIX, $FILTER;

	$sql="SELECT DISTINCT n_surname FROM {$TBLPREFIX}name WHERE n_surname ".PGV_DB_LIKE." '%{$FILTER}%' AND n_file=".PGV_GED_ID;
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data=array();
	while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$data[]=$row['n_surname'];
	}
	return $data;
}

/**
* returns GIVenNames matching filter
* @return Array of string
*/
function autocomplete_GIVN() {
	global $TBLPREFIX, $FILTER;

	$sql="SELECT DISTINCT n_givn FROM {$TBLPREFIX}name WHERE n_givn ".PGV_DB_LIKE." '%{$FILTER}%' AND n_file=".PGV_GED_ID;
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data=array();
	while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$givn=$row['n_givn'];
		list($givn) = explode("/", $givn);
		list($givn) = explode(",", $givn);
		list($givn) = explode("*", $givn);
		list($givn) = explode(" ", $givn);
		if ($givn) {
			$data[]=$row['n_givn'];
		}
	}
	return $data;
}

/**
* returns NAMEs matching filter
* @return Array of string
*/
function autocomplete_NAME() {
	global $TBLPREFIX, $FILTER;

	$sql="SELECT DISTINCT n_givn FROM {$TBLPREFIX}name WHERE n_givn ".PGV_DB_LIKE." '%{$FILTER}%' AND n_file=".PGV_GED_ID;
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$data=array();
	while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$givn=$row['n_givn'];
		list($givn) = explode("/", $givn);
		list($givn) = explode(",", $givn);
		list($givn) = explode("*", $givn);
		list($givn) = explode(" ", $givn);
		if ($givn) {
			$data[]=$row['n_givn'];
		}
	}
	$sql="SELECT DISTINCT n_surname FROM {$TBLPREFIX}name WHERE n_surname ".PGV_DB_LIKE." '%{$FILTER}%' AND n_file=".PGV_GED_ID;
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	while ($row=$res->fetchRow(DB_FETCHMODE_ASSOC)) {
		$data[]=$row['n_surname'];
	}
	return $data;
}

/**
* returns PLACes matching filter
* @return Array of string City, County, State/Province, Country
*/
function autocomplete_PLAC() {
	global $TBLPREFIX, $USE_GEONAMES, $lang_short_cut, $LANGUAGE, $FILTER, $OPTION;

	$sql="SELECT p_id, p_place, p_parent_id FROM {$TBLPREFIX}places WHERE p_place ".PGV_DB_LIKE." '%{$FILTER}%' AND p_file=".PGV_GED_ID;
	$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
	$res=dbquery($sql);

	$place = array();
	$parent = array();
	do {
		while ($row =& $res->fetchRow()) {
			$place[$row[0]] = $row[1];
			$parent[$row[0]] = $row[2];
		}
		$res->free();
		//-- search for missing parents
		$missing = array();
		foreach($parent as $k=>$v) {
			if ($v && !isset($place[$v])) {
				$missing[] = $v;
			}
		}
		if (count($missing)==0) {
			break;
		}
		$sql="SELECT p_id, p_place, p_parent_id FROM {$TBLPREFIX}places WHERE p_id IN (".implode(',', $missing).") AND p_file=".PGV_GED_ID;
		$sql=PGV_DB::limit_query($sql, PGV_AUTOCOMPLETE_LIMIT);
		$res=dbquery($sql);
	} while (true);

	//-- build place list
	$place = array_reverse($place, true);
	$data = array();
	do {
		$repeat = false;
		foreach($place as $k=>$v) {
			if ($parent[$k]==0) {
				$data[$k] = $v;
			} else {
				if (isset($data[$parent[$k]])) {
					$data[$k] = $v.", ".$data[$parent[$k]];
				} else {
					$repeat = true;
				}
			}
		}
	} while ($repeat);

	//-- filter
	function place_ok($v) {
		global $FILTER;
		return (stripos($v, $FILTER)!==false);
	}
	$data = array_filter($data, "place_ok");

	//-- no match => perform a geoNames query if enabled
	if (empty($data) && $USE_GEONAMES) {
		$url = "http://ws5.geonames.org/searchJSON".
					"?name_startsWith=".urlencode($FILTER).
					"&lang=".$lang_short_cut[$LANGUAGE].
					"&fcode=CMTY&fcode=ADM4&fcode=PPL&fcode=PPLA&fcode=PPLC".
					"&style=full";
		// try to use curl when file_get_contents not allowed
		if (ini_get('allow_url_fopen')) {
			$json = file_get_contents($url);
		} elseif (function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$json = curl_exec($ch);
			curl_close($ch);
		} else {
			return $data;
		}
		$places = json_decode($json, true);
		if ($places["geonames"]) {
			foreach ($places["geonames"] as $k => $place) {
				$data[] = $place["name"].", ".
									$place["adminName2"].", ".
									$place["adminName1"].", ".
									$place["countryName"];
			}
		}
	}

	// split ?
	if ($OPTION=="split") {
		foreach ($data as $k=>$v) {
			list($data[$k]) = explode(",", $v);
		}
		$data = array_filter($data, "place_ok");
	}

	return $data;
}

?>
