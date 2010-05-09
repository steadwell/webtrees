<?php
/**
 * UI for online updating of the GEDCOM config file.
 *
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
 * @package webtrees
 * @subpackage Admin
 * @version $Id$
 */

define('WT_SCRIPT_NAME', 'editconfig_gedcom.php');
require './includes/session.php';
require WT_ROOT.'includes/functions/functions_edit.php';

if (!WT_USER_GEDCOM_ADMIN) {
	header("Location: editgedcoms.php");
	exit;
}

/**
 * find the name of the first GEDCOM file in a zipfile
 * @param string $zipfile	the path and filename
 * @param boolean $extract  true = extract and return filename, false = return filename
 * @return string		the path and filename of the gedcom file
 */
function GetGEDFromZIP($zipfile, $extract=true) {
	GLOBAL $INDEX_DIRECTORY;

	require_once WT_ROOT.'library/pclzip.lib.php';
	$zip = new PclZip($zipfile);
	// if it's not a valid zip, just return the filename
	if (($list = $zip->listContent()) == 0) {
		return $zipfile;
	}

	// Determine the extract directory
	$slpos = strrpos($zipfile, "/");
	if (!$slpos) $slpos = strrpos($zipfile, "\\");
	if ($slpos) $path = substr($zipfile, 0, $slpos+1);
	else $path = $INDEX_DIRECTORY;
	// Scan the files and return the first .ged found
	foreach ($list as $key=>$listitem) {
		if (($listitem["status"]="ok") && (strstr(strtolower($listitem["filename"]), ".")==".ged")) {
			$filename = basename($listitem["filename"]);
			if ($extract == false) return $filename;

			// if the gedcom exists, save the old one. NOT to bak as it will be overwritten on import
			if (file_exists($path.$filename)) {
				if (file_exists($path.$filename.".old")) unlink($path.$filename.".old");
				copy($path.$filename, $path.$filename.".old");
				unlink($path.$filename);
			}
			if ($zip->extract(PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_PATH, $path, PCLZIP_OPT_BY_NAME, $listitem["filename"]) == 0) {
				print "ERROR cannot extract ZIP";
			}
			return $filename;
		}
	}
	return $zipfile;
}

$gedcom_config = $INDEX_DIRECTORY.WT_GEDCOM."_conf.php";
$gedcom_privacy = $INDEX_DIRECTORY.WT_GEDCOM."_priv.php";

$errors=false;
$error_msg='';

if (safe_POST('action')=='update') {

	$gedcom_title=$_POST["gedcom_title"];
	if (empty($gedcom_title)) {
		$gedcom_title=i18n::translate('Genealogy from [%s]', WT_GEDCOM);
	}
	set_gedcom_setting(WT_GED_ID, 'title', $gedcom_title);

	// Check that add/remove common surnames are separated by [,;] blank
	$_POST["NEW_COMMON_NAMES_REMOVE"] = preg_replace("/[,;]\b/", ", ", $_POST["NEW_COMMON_NAMES_REMOVE"]);
	$_POST["NEW_COMMON_NAMES_ADD"] = preg_replace("/[,;]\b/", ", ", $_POST["NEW_COMMON_NAMES_ADD"]);
	$COMMON_NAMES_THRESHOLD = $_POST["NEW_COMMON_NAMES_THRESHOLD"];
	$COMMON_NAMES_ADD = $_POST["NEW_COMMON_NAMES_ADD"];
	$COMMON_NAMES_REMOVE = $_POST["NEW_COMMON_NAMES_REMOVE"];

	$boolarray = array();
	$boolarray["yes"]="true";
	$boolarray["no"]="false";
	$boolarray[false]="false";
	$boolarray[true]="true";
	$configtext = file_get_contents($gedcom_config);

	$_POST["NEW_MEDIA_DIRECTORY"] = preg_replace('/\\\/', '/', $_POST["NEW_MEDIA_DIRECTORY"]);
	$ct = preg_match("'/$'", $_POST["NEW_MEDIA_DIRECTORY"]);
	if ($ct==0) $_POST["NEW_MEDIA_DIRECTORY"] .= "/";
	if (substr($_POST["NEW_MEDIA_DIRECTORY"], 0, 2)=="./") $_POST["NEW_MEDIA_DIRECTORY"] = substr($_POST["NEW_MEDIA_DIRECTORY"], 2);
	if (preg_match("/.*[a-zA-Z]{1}:.*/", $_POST["NEW_MEDIA_DIRECTORY"])>0) $errors = true;
	if (!isFileExternal($_POST["NEW_HOME_SITE_URL"])) $_POST["NEW_HOME_SITE_URL"] = "http://".$_POST["NEW_HOME_SITE_URL"];
	$_POST["NEW_PEDIGREE_ROOT_ID"] = trim($_POST["NEW_PEDIGREE_ROOT_ID"]);
	if ($_POST["NEW_DAYS_TO_SHOW_LIMIT"] < 1) $_POST["NEW_DAYS_TO_SHOW_LIMIT"] = 1;
	if ($_POST["NEW_DAYS_TO_SHOW_LIMIT"] > 30) $_POST["NEW_DAYS_TO_SHOW_LIMIT"] = 30;

	$configtext = preg_replace('/\$ABBREVIATE_CHART_LABELS\s*=\s*.*;/', "\$ABBREVIATE_CHART_LABELS = ".$boolarray[$_POST["NEW_ABBREVIATE_CHART_LABELS"]].";", $configtext);
	$configtext = preg_replace('/\$ADVANCED_NAME_FACTS\s*=\s*.*;/', "\$ADVANCED_NAME_FACTS = \"".$_POST["NEW_ADVANCED_NAME_FACTS"]."\";", $configtext);
	$configtext = preg_replace('/\$ADVANCED_PLAC_FACTS\s*=\s*.*;/', "\$ADVANCED_PLAC_FACTS = \"".$_POST["NEW_ADVANCED_PLAC_FACTS"]."\";", $configtext);
	$configtext = preg_replace('/\$USE_GEONAMES\s*=\s*.*;/', "\$USE_GEONAMES = ".$boolarray[$_POST["NEW_USE_GEONAMES"]].";", $configtext);
	$configtext = preg_replace('/\$ALLOW_EDIT_GEDCOM\s*=\s*.*;/', "\$ALLOW_EDIT_GEDCOM = ".$boolarray[$_POST["NEW_ALLOW_EDIT_GEDCOM"]].";", $configtext);
	$configtext = preg_replace('/\$ALLOW_THEME_DROPDOWN\s*=\s*.*;/', "\$ALLOW_THEME_DROPDOWN = ".$boolarray[$_POST["NEW_ALLOW_THEME_DROPDOWN"]].";", $configtext);
	$configtext = preg_replace('/\$AUTO_GENERATE_THUMBS\s*=\s*.*;/', "\$AUTO_GENERATE_THUMBS = ".$boolarray[$_POST["NEW_AUTO_GENERATE_THUMBS"]].";", $configtext);
	$configtext = preg_replace('/\$CALENDAR_FORMAT\s*=\s*".*";/', "\$CALENDAR_FORMAT = \"".$_POST["NEW_CALENDAR_FORMAT"]."\";", $configtext);
	$configtext = preg_replace('/\$CHART_BOX_TAGS\s*=\s*".*";/', "\$CHART_BOX_TAGS = \"".$_POST["NEW_CHART_BOX_TAGS"]."\";", $configtext);
	$configtext = preg_replace('/\$COMMON_NAMES_ADD\s*=\s*".*";/', "\$COMMON_NAMES_ADD = \"".$_POST["NEW_COMMON_NAMES_ADD"]."\";", $configtext);
	$configtext = preg_replace('/\$COMMON_NAMES_REMOVE\s*=\s*".*";/', "\$COMMON_NAMES_REMOVE = \"".$_POST["NEW_COMMON_NAMES_REMOVE"]."\";", $configtext);
	$configtext = preg_replace('/\$COMMON_NAMES_THRESHOLD\s*=\s*".*";/', "\$COMMON_NAMES_THRESHOLD = \"".$_POST["NEW_COMMON_NAMES_THRESHOLD"]."\";", $configtext);
	set_gedcom_setting(WT_GED_ID, 'CONTACT_USER_ID', $_POST["NEW_CONTACT_USER_ID"]);
	$configtext = preg_replace('/\$DAYS_TO_SHOW_LIMIT\s*=\s*".*";/', "\$DAYS_TO_SHOW_LIMIT = \"".$_POST["NEW_DAYS_TO_SHOW_LIMIT"]."\";", $configtext);
	$configtext = preg_replace('/\$DEFAULT_PEDIGREE_GENERATIONS\s*=\s*".*";/', "\$DEFAULT_PEDIGREE_GENERATIONS = \"".$_POST["NEW_DEFAULT_PEDIGREE_GENERATIONS"]."\";", $configtext);
	$configtext = preg_replace('/\$DISPLAY_JEWISH_GERESHAYIM\s*=\s*.*;/', "\$DISPLAY_JEWISH_GERESHAYIM = ".$boolarray[$_POST["NEW_DISPLAY_JEWISH_GERESHAYIM"]].";", $configtext);
	$configtext = preg_replace('/\$DISPLAY_JEWISH_THOUSANDS\s*=\s*.*;/', "\$DISPLAY_JEWISH_THOUSANDS = ".$boolarray[$_POST["NEW_DISPLAY_JEWISH_THOUSANDS"]].";", $configtext);
	$configtext = preg_replace('/\$EDIT_AUTOCLOSE\s*=\s*.*;/', "\$EDIT_AUTOCLOSE = ".$boolarray[$_POST["NEW_EDIT_AUTOCLOSE"]].";", $configtext);
	$configtext = preg_replace('/\$ENABLE_AUTOCOMPLETE\s*=\s*.*;/', "\$ENABLE_AUTOCOMPLETE = ".$boolarray[$_POST["NEW_ENABLE_AUTOCOMPLETE"]].";", $configtext);
	$configtext = preg_replace('/\$ENABLE_RSS\s*=\s*.*;/', "\$ENABLE_RSS = ".$boolarray[$_POST["NEW_ENABLE_RSS"]].";", $configtext);
	$configtext = preg_replace('/\$EXPAND_NOTES\s*=\s*.*;/', "\$EXPAND_NOTES = ".$boolarray[$_POST["NEW_EXPAND_NOTES"]].";", $configtext);
	$configtext = preg_replace('/\$EXPAND_RELATIVES_EVENTS\s*=\s*.*;/', "\$EXPAND_RELATIVES_EVENTS = ".$boolarray[$_POST["NEW_EXPAND_RELATIVES_EVENTS"]].";", $configtext);
	$configtext = preg_replace('/\$EXPAND_SOURCES\s*=\s*.*;/', "\$EXPAND_SOURCES = ".$boolarray[$_POST["NEW_EXPAND_SOURCES"]].";", $configtext);
	$configtext = preg_replace('/\$FAM_FACTS_ADD\s*=\s*".*";/', "\$FAM_FACTS_ADD = \"".$_POST["NEW_FAM_FACTS_ADD"]."\";", $configtext);
	$configtext = preg_replace('/\$FAM_FACTS_QUICK\s*=\s*".*";/', "\$FAM_FACTS_QUICK = \"".$_POST["NEW_FAM_FACTS_QUICK"]."\";", $configtext);
	$configtext = preg_replace('/\$FAM_FACTS_UNIQUE\s*=\s*".*";/', "\$FAM_FACTS_UNIQUE = \"".$_POST["NEW_FAM_FACTS_UNIQUE"]."\";", $configtext);
	$configtext = preg_replace('/\$FAM_ID_PREFIX\s*=\s*".*";/', "\$FAM_ID_PREFIX = \"".$_POST["NEW_FAM_ID_PREFIX"]."\";", $configtext);
	$configtext = preg_replace('/\$FAVICON\s*=\s*".*";/', "\$FAVICON = \"".$_POST["NEW_FAVICON"]."\";", $configtext);
	$configtext = preg_replace('/\$FULL_SOURCES\s*=\s*.*;/', "\$FULL_SOURCES = ".$boolarray[$_POST["NEW_FULL_SOURCES"]].";", $configtext);
	$configtext = preg_replace('/\$GEDCOM_DEFAULT_TAB\s*=\s*".*";/', "\$GEDCOM_DEFAULT_TAB = \"".$_POST["NEW_GEDCOM_DEFAULT_TAB"]."\";", $configtext);
	$configtext = preg_replace('/\$GEDCOM_ID_PREFIX\s*=\s*".*";/', "\$GEDCOM_ID_PREFIX = \"".$_POST["NEW_GEDCOM_ID_PREFIX"]."\";", $configtext);
	$configtext = preg_replace('/\$GENERATE_UIDS\s*=\s*.*;/', "\$GENERATE_UIDS = ".$boolarray[$_POST["NEW_GENERATE_UIDS"]].";", $configtext);
	$configtext = preg_replace('/\$HIDE_GEDCOM_ERRORS\s*=\s*.*;/', "\$HIDE_GEDCOM_ERRORS = ".$boolarray[$_POST["NEW_HIDE_GEDCOM_ERRORS"]].";", $configtext);
	$configtext = preg_replace('/\$HIDE_LIVE_PEOPLE\s*=\s*.*;/', "\$HIDE_LIVE_PEOPLE = ".$boolarray[$_POST["NEW_HIDE_LIVE_PEOPLE"]].";", $configtext);
	$configtext = preg_replace('/\$HOME_SITE_TEXT\s*=\s*".*";/', "\$HOME_SITE_TEXT = \"".$_POST["NEW_HOME_SITE_TEXT"]."\";", $configtext);
	$configtext = preg_replace('/\$HOME_SITE_URL\s*=\s*".*";/', "\$HOME_SITE_URL = \"".$_POST["NEW_HOME_SITE_URL"]."\";", $configtext);
	$configtext = preg_replace('/\$INDI_FACTS_ADD\s*=\s*".*";/', "\$INDI_FACTS_ADD = \"".$_POST["NEW_INDI_FACTS_ADD"]."\";", $configtext);
	$configtext = preg_replace('/\$INDI_FACTS_QUICK\s*=\s*".*";/', "\$INDI_FACTS_QUICK = \"".$_POST["NEW_INDI_FACTS_QUICK"]."\";", $configtext);
	$configtext = preg_replace('/\$INDI_FACTS_UNIQUE\s*=\s*".*";/', "\$INDI_FACTS_UNIQUE = \"".$_POST["NEW_INDI_FACTS_UNIQUE"]."\";", $configtext);
	$configtext = preg_replace('/\$LANGUAGE\s*=\s*".*";/', "\$LANGUAGE = \"".$_POST["GEDCOMLANG"]."\";", $configtext);
	$configtext = preg_replace('/\$LINK_ICONS\s*=\s*\".*\";/', "\$LINK_ICONS = \"".$_POST["NEW_LINK_ICONS"]."\";", $configtext);
	$configtext = preg_replace('/\$MAX_DESCENDANCY_GENERATIONS\s*=\s*".*";/', "\$MAX_DESCENDANCY_GENERATIONS = \"".$_POST["NEW_MAX_DESCENDANCY_GENERATIONS"]."\";", $configtext);
	$configtext = preg_replace('/\$MAX_PEDIGREE_GENERATIONS\s*=\s*".*";/', "\$MAX_PEDIGREE_GENERATIONS = \"".$_POST["NEW_MAX_PEDIGREE_GENERATIONS"]."\";", $configtext);
	$configtext = preg_replace('/\$MEDIA_DIRECTORY\s*=\s*".*";/', "\$MEDIA_DIRECTORY = \"".$_POST["NEW_MEDIA_DIRECTORY"]."\";", $configtext);
	$configtext = preg_replace('/\$MEDIA_DIRECTORY_LEVELS\s*=\s*".*";/', "\$MEDIA_DIRECTORY_LEVELS = \"".$_POST["NEW_MEDIA_DIRECTORY_LEVELS"]."\";", $configtext);
	$configtext = preg_replace('/\$MEDIA_EXTERNAL\s*=\s*.*;/', "\$MEDIA_EXTERNAL = ".$boolarray[$_POST["NEW_MEDIA_EXTERNAL"]].";", $configtext);
	$configtext = preg_replace('/\$MEDIA_ID_PREFIX\s*=\s*".*";/', "\$MEDIA_ID_PREFIX = \"".$_POST["NEW_MEDIA_ID_PREFIX"]."\";", $configtext);
	$configtext = preg_replace('/\$META_AUDIENCE\s*=\s*".*";/', "\$META_AUDIENCE = \"".$_POST["NEW_META_AUDIENCE"]."\";", $configtext);
	$configtext = preg_replace('/\$META_AUTHOR\s*=\s*".*";/', "\$META_AUTHOR = \"".$_POST["NEW_META_AUTHOR"]."\";", $configtext);
	$configtext = preg_replace('/\$META_COPYRIGHT\s*=\s*".*";/', "\$META_COPYRIGHT = \"".$_POST["NEW_META_COPYRIGHT"]."\";", $configtext);
	$configtext = preg_replace('/\$META_DESCRIPTION\s*=\s*".*";/', "\$META_DESCRIPTION = \"".$_POST["NEW_META_DESCRIPTION"]."\";", $configtext);
	$configtext = preg_replace('/\$META_KEYWORDS\s*=\s*".*";/', "\$META_KEYWORDS = \"".$_POST["NEW_META_KEYWORDS"]."\";", $configtext);
	$configtext = preg_replace('/\$META_PAGE_TOPIC\s*=\s*".*";/', "\$META_PAGE_TOPIC = \"".$_POST["NEW_META_PAGE_TOPIC"]."\";", $configtext);
	$configtext = preg_replace('/\$META_PAGE_TYPE\s*=\s*".*";/', "\$META_PAGE_TYPE = \"".$_POST["NEW_META_PAGE_TYPE"]."\";", $configtext);
	$configtext = preg_replace('/\$META_PUBLISHER\s*=\s*".*";/', "\$META_PUBLISHER = \"".$_POST["NEW_META_PUBLISHER"]."\";", $configtext);
	$configtext = preg_replace('/\$META_REVISIT\s*=\s*".*";/', "\$META_REVISIT = \"".$_POST["NEW_META_REVISIT"]."\";", $configtext);
	$configtext = preg_replace('/\$META_ROBOTS\s*=\s*".*";/', "\$META_ROBOTS = \"".$_POST["NEW_META_ROBOTS"]."\";", $configtext);
	$configtext = preg_replace('/\$META_TITLE\s*=\s*".*";/', "\$META_TITLE = \"".$_POST["NEW_META_TITLE"]."\";", $configtext);
	$configtext = preg_replace('/\$MULTI_MEDIA\s*=\s*.*;/', "\$MULTI_MEDIA = ".$boolarray[$_POST["NEW_MULTI_MEDIA"]].";", $configtext);
	$configtext = preg_replace('/\$NOTE_ID_PREFIX\s*=\s*".*";/', "\$NOTE_ID_PREFIX = \"".$_POST["NEW_NOTE_ID_PREFIX"]."\";", $configtext);
	$configtext = preg_replace('/\$PEDIGREE_FULL_DETAILS\s*=\s*.*;/', "\$PEDIGREE_FULL_DETAILS = ".$boolarray[$_POST["NEW_PEDIGREE_FULL_DETAILS"]].";", $configtext);
	$configtext = preg_replace('/\$PEDIGREE_SHOW_GENDER\s*=\s*.*;/', "\$PEDIGREE_SHOW_GENDER = ".$boolarray[$_POST["NEW_PEDIGREE_SHOW_GENDER"]].";", $configtext);
	$configtext = preg_replace('/\$PEDIGREE_LAYOUT\s*=\s*.*;/', "\$PEDIGREE_LAYOUT = ".$boolarray[$_POST["NEW_PEDIGREE_LAYOUT"]].";", $configtext);
	$configtext = preg_replace('/\$PEDIGREE_ROOT_ID\s*=\s*".*";/', "\$PEDIGREE_ROOT_ID = \"".$_POST["NEW_PEDIGREE_ROOT_ID"]."\";", $configtext);
	$configtext = preg_replace('/\$POSTAL_CODE\s*=\s*.*;/', "\$POSTAL_CODE = ".$boolarray[$_POST["NEW_POSTAL_CODE"]].";", $configtext);
	$configtext = preg_replace('/\$PREFER_LEVEL2_SOURCES\s*=\s*.*;/', "\$PREFER_LEVEL2_SOURCES = \"".$_POST["NEW_PREFER_LEVEL2_SOURCES"]."\";", $configtext);
	$configtext = preg_replace('/\$NO_UPDATE_CHAN\s*=\s*.*;/', "\$NO_UPDATE_CHAN = ".$boolarray[$_POST["NEW_NO_UPDATE_CHAN"]].";", $configtext);
	$configtext = preg_replace('/\$QUICK_REQUIRED_FACTS\s*=\s*".*";/', "\$QUICK_REQUIRED_FACTS = \"".$_POST["NEW_QUICK_REQUIRED_FACTS"]."\";", $configtext);
	$configtext = preg_replace('/\$QUICK_REQUIRED_FAMFACTS\s*=\s*".*";/', "\$QUICK_REQUIRED_FAMFACTS = \"".$_POST["NEW_QUICK_REQUIRED_FAMFACTS"]."\";", $configtext);
	$configtext = preg_replace('/\$REPO_FACTS_ADD\s*=\s*".*";/', "\$REPO_FACTS_ADD = \"".$_POST["NEW_REPO_FACTS_ADD"]."\";", $configtext);
	$configtext = preg_replace('/\$REPO_FACTS_QUICK\s*=\s*".*";/', "\$REPO_FACTS_QUICK = \"".$_POST["NEW_REPO_FACTS_QUICK"]."\";", $configtext);
	$configtext = preg_replace('/\$REPO_FACTS_UNIQUE\s*=\s*".*";/', "\$REPO_FACTS_UNIQUE = \"".$_POST["NEW_REPO_FACTS_UNIQUE"]."\";", $configtext);
	$configtext = preg_replace('/\$REPO_ID_PREFIX\s*=\s*".*";/', "\$REPO_ID_PREFIX = \"".$_POST["NEW_REPO_ID_PREFIX"]."\";", $configtext);
	$configtext = preg_replace('/\$REQUIRE_AUTHENTICATION\s*=\s*.*;/', "\$REQUIRE_AUTHENTICATION = ".$boolarray[$_POST["NEW_REQUIRE_AUTHENTICATION"]].";", $configtext);
	$configtext = preg_replace('/\$PAGE_AFTER_LOGIN\s*=\s*.*;/', "\$PAGE_AFTER_LOGIN = \"".$_POST["NEW_PAGE_AFTER_LOGIN"]."\";", $configtext);
	$configtext = preg_replace('/\$RSS_FORMAT\s*=\s*".*";/', "\$RSS_FORMAT = \"".$_POST["NEW_RSS_FORMAT"]."\";", $configtext);
	$configtext = preg_replace('/\$SHOW_AGE_DIFF\s*=\s*.*;/', "\$SHOW_AGE_DIFF = ".$boolarray[$_POST["NEW_SHOW_AGE_DIFF"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_CONTEXT_HELP\s*=\s*.*;/', "\$SHOW_CONTEXT_HELP = ".$boolarray[$_POST["NEW_SHOW_CONTEXT_HELP"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_COUNTER\s*=\s*.*;/', "\$SHOW_COUNTER = ".$boolarray[$_POST["NEW_SHOW_COUNTER"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_EMPTY_BOXES\s*=\s*.*;/', "\$SHOW_EMPTY_BOXES = ".$boolarray[$_POST["NEW_SHOW_EMPTY_BOXES"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_FACT_ICONS\s*=\s*.*;/', "\$SHOW_FACT_ICONS = ".$boolarray[$_POST["NEW_SHOW_FACT_ICONS"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_GEDCOM_RECORD\s*=\s*.*;/', "\$SHOW_GEDCOM_RECORD = ".$boolarray[$_POST["NEW_SHOW_GEDCOM_RECORD"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_HIGHLIGHT_IMAGES\s*=\s*.*;/', "\$SHOW_HIGHLIGHT_IMAGES = ".$boolarray[$_POST["NEW_SHOW_HIGHLIGHT_IMAGES"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_ID_NUMBERS\s*=\s*.*;/', "\$SHOW_ID_NUMBERS = ".$boolarray[$_POST["NEW_SHOW_ID_NUMBERS"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_LAST_CHANGE\s*=\s*.*;/', "\$SHOW_LAST_CHANGE = ".$boolarray[$_POST["NEW_SHOW_LAST_CHANGE"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_EST_LIST_DATES\s*=\s*.*;/', "\$SHOW_EST_LIST_DATES = ".$boolarray[$_POST["NEW_SHOW_EST_LIST_DATES"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_LDS_AT_GLANCE\s*=\s*.*;/', "\$SHOW_LDS_AT_GLANCE = ".$boolarray[$_POST["NEW_SHOW_LDS_AT_GLANCE"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_LEVEL2_NOTES\s*=\s*.*;/', "\$SHOW_LEVEL2_NOTES = ".$boolarray[$_POST["NEW_SHOW_LEVEL2_NOTES"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_MARRIED_NAMES\s*=\s*.*;/', "\$SHOW_MARRIED_NAMES = ".$boolarray[$_POST["NEW_SHOW_MARRIED_NAMES"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_MEDIA_DOWNLOAD\s*=\s*.*;/', "\$SHOW_MEDIA_DOWNLOAD = ".$boolarray[$_POST["NEW_SHOW_MEDIA_DOWNLOAD"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_MEDIA_FILENAME\s*=\s*.*;/', "\$SHOW_MEDIA_FILENAME = ".$boolarray[$_POST["NEW_SHOW_MEDIA_FILENAME"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_PARENTS_AGE\s*=\s*.*;/', "\$SHOW_PARENTS_AGE = ".$boolarray[$_POST["NEW_SHOW_PARENTS_AGE"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_PEDIGREE_PLACES\s*=\s*".*";/', "\$SHOW_PEDIGREE_PLACES = \"".$_POST["NEW_SHOW_PEDIGREE_PLACES"]."\";", $configtext);
	$configtext = preg_replace('/\$SHOW_LIST_PLACES\s*=\s*".*";/', "\$SHOW_LIST_PLACES = \"".$_POST["NEW_SHOW_LIST_PLACES"]."\";", $configtext);
	$configtext = preg_replace('/\$SHOW_REGISTER_CAUTION\s*=\s*.*;/', "\$SHOW_REGISTER_CAUTION = ".$boolarray[$_POST["NEW_SHOW_REGISTER_CAUTION"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_RELATIVES_EVENTS\s*=\s*.*;/', "\$SHOW_RELATIVES_EVENTS = \"".$_POST["NEW_SHOW_RELATIVES_EVENTS"]."\";", $configtext);
	$configtext = preg_replace('/\$SHOW_SPIDER_TAGLINE\s*=\s*.*;/', "\$SHOW_SPIDER_TAGLINE = ".$boolarray[$_POST["NEW_SHOW_SPIDER_TAGLINE"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_STATS\s*=\s*.*;/', "\$SHOW_STATS = ".$boolarray[$_POST["NEW_SHOW_STATS"]].";", $configtext);
	$configtext = preg_replace('/\$SOUR_FACTS_ADD\s*=\s*".*";/', "\$SOUR_FACTS_ADD = \"".$_POST["NEW_SOUR_FACTS_ADD"]."\";", $configtext);
	$configtext = preg_replace('/\$SOUR_FACTS_QUICK\s*=\s*".*";/', "\$SOUR_FACTS_QUICK = \"".$_POST["NEW_SOUR_FACTS_QUICK"]."\";", $configtext);
	$configtext = preg_replace('/\$SOUR_FACTS_UNIQUE\s*=\s*".*";/', "\$SOUR_FACTS_UNIQUE = \"".$_POST["NEW_SOUR_FACTS_UNIQUE"]."\";", $configtext);
	$configtext = preg_replace('/\$SOURCE_ID_PREFIX\s*=\s*".*";/', "\$SOURCE_ID_PREFIX = \"".$_POST["NEW_SOURCE_ID_PREFIX"]."\";", $configtext);
	$configtext = preg_replace('/\$SPLIT_PLACES\s*=\s*.*;/', "\$SPLIT_PLACES = ".$boolarray[$_POST["NEW_SPLIT_PLACES"]].";", $configtext);
	$configtext = preg_replace('/\$SUBLIST_TRIGGER_I\s*=\s*.*;/', "\$SUBLIST_TRIGGER_I = \"".$_POST["NEW_SUBLIST_TRIGGER_I"]."\";", $configtext);
	$configtext = preg_replace('/\$SUBLIST_TRIGGER_F\s*=\s*.*;/', "\$SUBLIST_TRIGGER_F = \"".$_POST["NEW_SUBLIST_TRIGGER_F"]."\";", $configtext);
	$configtext = preg_replace('/\$SURNAME_LIST_STYLE\s*=\s*.*;/', "\$SURNAME_LIST_STYLE = \"".$_POST["NEW_SURNAME_LIST_STYLE"]."\";", $configtext);
	$configtext = preg_replace('/\$SURNAME_TRADITION\s*=\s*.*;/', "\$SURNAME_TRADITION = \"".$_POST["NEW_SURNAME_TRADITION"]."\";", $configtext);
	$configtext = preg_replace('/\$THUMBNAIL_WIDTH\s*=\s*".*";/', "\$THUMBNAIL_WIDTH = \"".$_POST["NEW_THUMBNAIL_WIDTH"]."\";", $configtext);
	$configtext = preg_replace('/\$UNDERLINE_NAME_QUOTES\s*=\s*.*;/', "\$UNDERLINE_NAME_QUOTES = ".$boolarray[$_POST["NEW_UNDERLINE_NAME_QUOTES"]].";", $configtext);
	$configtext = preg_replace('/\$USE_RIN\s*=\s*.*;/', "\$USE_RIN = ".$boolarray[$_POST["NEW_USE_RIN"]].";", $configtext);
	$configtext = preg_replace('/\$USE_THUMBS_MAIN\s*=\s*.*;/', "\$USE_THUMBS_MAIN = ".$boolarray[$_POST["NEW_USE_THUMBS_MAIN"]].";", $configtext);
	$configtext = preg_replace('/\$USE_SILHOUETTE\s*=\s*.*;/', "\$USE_SILHOUETTE = ".$boolarray[$_POST["NEW_USE_SILHOUETTE"]].";", $configtext);
	$configtext = preg_replace('/\$USE_MEDIA_VIEWER\s*=\s*.*;/', "\$USE_MEDIA_VIEWER = ".$boolarray[$_POST["NEW_USE_MEDIA_VIEWER"]].";", $configtext);
	$configtext = preg_replace('/\$USE_MEDIA_FIREWALL\s*=\s*.*;/', "\$USE_MEDIA_FIREWALL = ".$boolarray[$_POST["NEW_USE_MEDIA_FIREWALL"]].";", $configtext);
	$configtext = preg_replace('/\$MEDIA_FIREWALL_THUMBS\s*=\s*.*;/', "\$MEDIA_FIREWALL_THUMBS = ".$boolarray[$_POST["NEW_MEDIA_FIREWALL_THUMBS"]].";", $configtext);
	$configtext = preg_replace('/\$SHOW_NO_WATERMARK\s*=\s*.*;/', "\$SHOW_NO_WATERMARK = ".$_POST["NEW_SHOW_NO_WATERMARK"].";", $configtext);
	$configtext = preg_replace('/\$WATERMARK_THUMB\s*=\s*.*;/', "\$WATERMARK_THUMB = ".$boolarray[$_POST["NEW_WATERMARK_THUMB"]].";", $configtext);
	$configtext = preg_replace('/\$SAVE_WATERMARK_THUMB\s*=\s*.*;/', "\$SAVE_WATERMARK_THUMB = ".$boolarray[$_POST["NEW_SAVE_WATERMARK_THUMB"]].";", $configtext);
	$configtext = preg_replace('/\$SAVE_WATERMARK_IMAGE\s*=\s*.*;/', "\$SAVE_WATERMARK_IMAGE = ".$boolarray[$_POST["NEW_SAVE_WATERMARK_IMAGE"]].";", $configtext);
	$configtext = preg_replace('/\$THEME_DIR\s*=\s*".*";/', "\$THEME_DIR = \"".trim($_POST["NEW_THEME_DIR"])."\";", $configtext);
	$configtext = preg_replace('/\$WEBTREES_EMAIL\s*=\s*".*";/', "\$WEBTREES_EMAIL = \"".trim($_POST["NEW_WEBTREES_EMAIL"])."\";", $configtext);
	set_gedcom_setting(WT_GED_ID, 'WEBMASTER_USER_ID', $_POST["NEW_WEBMASTER_USER_ID"]);
	$configtext = preg_replace('/\$WELCOME_TEXT_AUTH_MODE\s*=\s*".*";/', "\$WELCOME_TEXT_AUTH_MODE = \"".$_POST["NEW_WELCOME_TEXT_AUTH_MODE"]."\";", $configtext);
	$configtext = preg_replace('/\$WELCOME_TEXT_AUTH_MODE_4\s*=\s*".*";/', "\$WELCOME_TEXT_AUTH_MODE_4 = \"".$_POST["NEW_WELCOME_TEXT_AUTH_MODE_4"]."\";", $configtext);// new
	$configtext = preg_replace('/\$WELCOME_TEXT_CUST_HEAD\s*=\s*.*;/', "\$WELCOME_TEXT_CUST_HEAD = ".$boolarray[$_POST["NEW_WELCOME_TEXT_CUST_HEAD"]].";", $configtext);
	$configtext = preg_replace('/\$WORD_WRAPPED_NOTES\s*=\s*.*;/', "\$WORD_WRAPPED_NOTES = ".$boolarray[$_POST["NEW_WORD_WRAPPED_NOTES"]].";", $configtext);
	$configtext = preg_replace('/\$ZOOM_BOXES\s*=\s*\".*\";/', "\$ZOOM_BOXES = \"".$_POST["NEW_ZOOM_BOXES"]."\";", $configtext);
	if (!$_POST["NEW_MEDIA_FIREWALL_ROOTDIR"]) {
		$NEW_MEDIA_FIREWALL_ROOTDIR = $INDEX_DIRECTORY;
	} else {
		$_POST["NEW_MEDIA_FIREWALL_ROOTDIR"] = trim($_POST["NEW_MEDIA_FIREWALL_ROOTDIR"]);
		if (substr ($_POST["NEW_MEDIA_FIREWALL_ROOTDIR"], -1) != "/") $_POST["NEW_MEDIA_FIREWALL_ROOTDIR"] = $_POST["NEW_MEDIA_FIREWALL_ROOTDIR"] . "/";
		$NEW_MEDIA_FIREWALL_ROOTDIR = $_POST["NEW_MEDIA_FIREWALL_ROOTDIR"];
	}
	if (!is_dir($NEW_MEDIA_FIREWALL_ROOTDIR)) {
		$errors = true;
		$error_msg .= "<span class=\"error\">".i18n::translate('The Media Firewall root directory you requested does not exist.  You must create it first.')."</span><br />\n";
	}
	if (!$errors) {
		// create the media directory
		// if NEW_MEDIA_FIREWALL_ROOTDIR is the INDEX_DIRECTORY, PGV will have perms to create it
		// if PGV is unable to create the directory, tell the user to create it
		if (($_POST["NEW_USE_MEDIA_FIREWALL"]=='yes') || $USE_MEDIA_FIREWALL) {
			if (!is_dir($NEW_MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY)) {
				@mkdir($NEW_MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY, WT_PERM_EXE);
				if (!is_dir($NEW_MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY)) {
					$errors = true;
					$error_msg .= "<span class=\"error\">".i18n::translate('The protected media directory could not be created in the Media Firewall root directory.  Please create this directory and make it world-writable.')." ".$NEW_MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY."</span><br />\n";
				}
			}
		}
	}
	if (!$errors) {
		// create the thumbs dir to make sure we have write perms
		if (($_POST["NEW_USE_MEDIA_FIREWALL"]=='yes') || $USE_MEDIA_FIREWALL) {
			if (!is_dir($NEW_MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY."thumbs")) {
				@mkdir($NEW_MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY."thumbs", WT_PERM_EXE);
				if (!is_dir($NEW_MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY."thumbs")) {
					$errors = true;
					$error_msg .= "<span class=\"error\">".i18n::translate('The protected media directory in the Media Firewall root directory is not world writable. ')." ".$NEW_MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY."</span><br />\n";
				}
			}
		}
	}
	if (!$errors) {
		// copy the .htaccess file from INDEX_DIRECTORY to NEW_MEDIA_FIREWALL_ROOTDIR in case it is still in a web-accessible area
		if (($_POST["NEW_USE_MEDIA_FIREWALL"]=='yes') || $USE_MEDIA_FIREWALL) {
			if ( (file_exists($INDEX_DIRECTORY.".htaccess")) && (is_dir($NEW_MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY)) && (!file_exists($NEW_MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY.".htaccess")) ) {
				@copy($INDEX_DIRECTORY.".htaccess", $NEW_MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY.".htaccess");
				if (!file_exists($NEW_MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY.".htaccess")) {
					$errors = true;
					$error_msg .= "<span class=\"error\">".i18n::translate('The protected media directory in the Media Firewall root directory is not world writable. ')." ".$NEW_MEDIA_FIREWALL_ROOTDIR.$MEDIA_DIRECTORY."</span><br />\n";
				}
			}
		}
	}
	if (!$errors) {
		$configtext = preg_replace('/\$MEDIA_FIREWALL_ROOTDIR\s*=\s*".*";/', "\$MEDIA_FIREWALL_ROOTDIR = \"".$_POST["NEW_MEDIA_FIREWALL_ROOTDIR"]."\";", $configtext);
	}
	file_put_contents($gedcom_config, $configtext);

	if (($_POST["NEW_USE_MEDIA_FIREWALL"]=='yes') && !$USE_MEDIA_FIREWALL) {
		AddToLog("Media Firewall enabled", 'config');

		if (!$errors) {
			// create/modify an htaccess file in the main media directory
			$httext = "";
			if (file_exists($MEDIA_DIRECTORY.".htaccess")) {
				$httext = implode('', file($MEDIA_DIRECTORY.".htaccess"));
				// remove all PGV media firewall sections from the .htaccess
				$httext = preg_replace('/\n?^[#]*\s*BEGIN PGV MEDIA FIREWALL SECTION(.*\n){10}[#]*\s*END PGV MEDIA FIREWALL SECTION\s*[#]*\n?/m', "", $httext);
				// comment out any existing lines that set ErrorDocument 404
				$httext = preg_replace('/^(ErrorDocument\s*404(.*))\n?/', "#$1\n", $httext);
				$httext = preg_replace('/[^#](ErrorDocument\s*404(.*))\n?/', "\n#$1\n", $httext);
			}
			// add new PGV media firewall section to the end of the file
			$httext .= "\n######## BEGIN PGV MEDIA FIREWALL SECTION ##########";
			$httext .= "\n################## DO NOT MODIFY ###################";
			$httext .= "\n## THERE MUST BE EXACTLY 11 LINES IN THIS SECTION ##";
			$httext .= "\n<IfModule mod_rewrite.c>";
			$httext .= "\n\tRewriteEngine On";
			$httext .= "\n\tRewriteCond %{REQUEST_FILENAME} !-f";
			$httext .= "\n\tRewriteCond %{REQUEST_FILENAME} !-d";
			$httext .= "\n\tRewriteRule .* ".WT_SCRIPT_PATH."mediafirewall.php"." [L]";
			$httext .= "\n</IfModule>";
			$httext .= "\nErrorDocument\t404\t".WT_SCRIPT_PATH."mediafirewall.php";
			$httext .= "\n########## END PGV MEDIA FIREWALL SECTION ##########";

			$whichFile = $MEDIA_DIRECTORY.".htaccess";
			$fp = @fopen($whichFile, "wb");
			if (!$fp) {
				$errors = true;
				$error_msg .= "<span class=\"error\">".i18n::translate('E R R O R !!!<br />Could not write to file <i>%s</i>.  Please check it for proper Write permissions.', $whichFile)."</span><br />\n";
			} else {
				fwrite($fp, $httext);
				fclose($fp);
			}
		}

	} elseif (($_POST["NEW_USE_MEDIA_FIREWALL"]=='no') && $USE_MEDIA_FIREWALL) {
		AddToLog("Media Firewall disabled", 'config');

		if (file_exists($MEDIA_DIRECTORY.".htaccess")) {
			$httext = implode('', file($MEDIA_DIRECTORY.".htaccess"));
			// remove all PGV media firewall sections from the .htaccess
			$httext = preg_replace('/\n?^[#]*\s*BEGIN PGV MEDIA FIREWALL SECTION(.*\n){10}[#]*\s*END PGV MEDIA FIREWALL SECTION\s*[#]*\n?/m', "", $httext);
			// comment out any lines that set ErrorDocument 404
			$httext = preg_replace('/^(ErrorDocument\s*404(.*))\n?/', "#$1\n", $httext);
			$httext = preg_replace('/[^#](ErrorDocument\s*404(.*))\n?/', "\n#$1\n", $httext);
			$whichFile = $MEDIA_DIRECTORY.".htaccess";
			$fp = @fopen($whichFile, "wb");
			if (!$fp) {
				$errors = true;
				$error_msg .= "<span class=\"error\">".i18n::translate('E R R O R !!!<br />Could not write to file <i>%s</i>.  Please check it for proper Write permissions.', $whichFile)."</span><br />\n";
			} else {
				fwrite($fp, $httext);
				fclose($fp);
			}
		}

	}

	// Delete Upcoming Events cache
	if ($_POST["old_DAYS_TO_SHOW_LIMIT"] < $_POST["NEW_DAYS_TO_SHOW_LIMIT"]) {
		if (is_writable($INDEX_DIRECTORY) and file_exists($INDEX_DIRECTORY.$FILE."_upcoming.php")) {
			unlink ($INDEX_DIRECTORY.$FILE."_upcoming.php");
		}
	}

	//-- delete the cache files for the Home Page blocks
	require_once WT_ROOT.'includes/index_cache.php';
	clearCache();

	$logline = AddToLog("Gedcom configuration ".$INDEX_DIRECTORY.WT_GEDCOM."_conf.php"." updated", 'config');
	$gedcomconfname = WT_GEDCOM."_conf.php";
	if (!$errors) {
		$gednews = getUserNews(WT_GEDCOM);
		if (count($gednews)==0) {
			$news = array();
			$news["title"] = i18n::translate('Welcome to Your Genealogy');
			$news["username"] = WT_GEDCOM;
			$news["text"] = i18n::translate('The genealogy information on this website is powered by <a href="http://webtrees.net/" target="_blank">webtrees</a>.  This page provides an introduction and overview to this genealogy.<br /><br />To begin working with the data, choose one of the charts from the Charts menu, go to the Individual list, or search for a name or place.<br /><br />If you have trouble using the site, you can click on the Help icon to give you information on how to use the page that you are currently viewing.<br /><br />Thank you for visiting this site.');
			$news["date"] = client_time();
			addNews($news);
		}
		header("Location: editgedcoms.php");
		exit;
	}
}

print_header(i18n::translate('GEDCOM Configuration'));
?>
<script type="text/javascript">
//<![CDATA[
  jQuery.noConflict();
  jQuery(document).ready(function(){
  jQuery("#tabs").tabs();
  });
//]]>
</script>
<script language="JavaScript" type="text/javascript">
<!--
	function show_jewish() {
		var cal=document.getElementById('NEW_CALENDAR_FORMAT');
		if (cal.options[cal.selectedIndex].value.match(/jewish|hebrew/)) {
			document.getElementById('hebrew-cal' ).style.display='block';
		} else {
			document.getElementById('hebrew-cal' ).style.display='none';
		}
	}
	var pastefield;
	function paste_id(value) {
		pastefield.value=value;
	}
//-->
</script>

<form enctype="multipart/form-data" method="post" id="configform" name="configform" action="editconfig_gedcom.php">

<table class="facts_table <?php echo $TEXT_DIRECTION; ?>">
	<tr>
		<td colspan="2" class="facts_label">
			<?php
				echo "<h2>", i18n::translate('GEDCOM Configuration'), " - ";
				echo PrintReady(get_gedcom_setting(WT_GED_ID, 'title'));
				echo "</h2>";
				echo "<a href=\"editgedcoms.php\"><b>";
				echo i18n::translate('Return to the GEDCOM management menu');
				echo "</b></a><br /><br />";
			?>

			<script language="javascript" type="text/javascript">
			<!--

			/* Function that shows or hides Hebrew calendar options */
			function show_hebrew(){
				var calendar = document.getElementById("NEW_CALENDAR_FORMAT");
				if (calendar != undefined)
					show_jewish(calendar, "hebrew-cal");
			}

			//-->
			</script>
		</td>
	</tr>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="old_DAYS_TO_SHOW_LIMIT" value="<?php print $DAYS_TO_SHOW_LIMIT; ?>" />
<?php
	if (!empty($error_msg)) print "<br /><span class=\"error\">".$error_msg."</span><br />\n";
	$i = 0;
?>

<table class="center <?php echo $TEXT_DIRECTION ?> width90">
	<tr>
		<td colspan="2">
			<div id="tabs" class="width100">
				<ul>
					<li><a href="#file-options"><span><?php echo i18n::translate('GEDCOM Basics')?></span></a></li>
					<li><a href="#config-media"><span><?php echo i18n::translate('Multimedia')?></span></a></li>
					<li><a href="#access-options"><span><?php echo i18n::translate('Access & Privacy')?></span></a></li>
					<li><a href="#name-options"><span><?php echo i18n::translate('Names')?></span></a></li>
					<li><a href="#layout-options"><span><?php echo i18n::translate('Layout')?></span></a></li>
					<li><a href="#hide-show"><span><?php echo i18n::translate('Hide & Show')?></span></a></li>
					<li><a href="#edit-options"><span><?php echo i18n::translate('Edit Options')?></span></a></li>
					<li><a href="#user-options"><span><?php echo i18n::translate('User Options')?></span></a></li>
					<li><a href="#other-options"><span><?php echo i18n::translate('Contact & Site Options')?></span></a></li>
				</ul>
			<!-- GEDCOM BASICS -->
			<div id="file-options">
			  <table class="facts_table">
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('GEDCOM title'), help_link('gedcom_title'); ?>
					</td>
					<td class="optionbox"><input type="text" name="gedcom_title" dir="ltr" value="<?php echo htmlspecialchars(get_gedcom_setting(WT_GED_ID, 'title')); ?>" size="40" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Language'), help_link('LANGUAGE'); ?>
					</td>
					<td class="optionbox">
					<?php
						echo edit_field_language('GEDCOMLANG', $LANGUAGE, 'dir="ltr" tabindex="'.(++$i).'"');
					?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Default person for Pedigree and Descendancy charts'), help_link('PEDIGREE_ROOT_ID'); ?>
					</td>
				<td class="optionbox"><input type="text" name="NEW_PEDIGREE_ROOT_ID" id="NEW_PEDIGREE_ROOT_ID" value="<?php print $PEDIGREE_ROOT_ID; ?>" size="5" tabindex="<?php echo ++$i; ?>" />
						<?php
						print_findindi_link("NEW_PEDIGREE_ROOT_ID", "");
						if ($PEDIGREE_ROOT_ID) {
							$person=Person::getInstance($PEDIGREE_ROOT_ID);
							if ($person) {
								echo ' <span class="list_item">', $person->getFullName(), ' ', $person->format_first_major_fact(WT_EVENTS_BIRT, 1), '</span>';
							} else {
								echo ' <span class="error">', i18n::translate('Unable to find record with ID'), '</span>';
							}
						}
					?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Calendar format'), help_link('CALENDAR_FORMAT'); ?>
					</td>
					<td class="optionbox">
						<select id="NEW_CALENDAR_FORMAT" name="NEW_CALENDAR_FORMAT" tabindex="<?php echo ++$i; ?>" onchange="show_jewish();">
						<?php
						foreach (array(
							'none'=>i18n::translate('No calendar conversion'),
							'gregorian'=>i18n::translate('Gregorian'),
							'julian'=>i18n::translate('Julian'),
							'french'=>i18n::translate('French'),
							'jewish'=>i18n::translate('Jewish'),
							'jewish_and_gregorian'=>i18n::translate('Jewish and Gregorian'),
							'hebrew'=>i18n::translate('Hebrew'),
							'hebrew_and_gregorian'=>i18n::translate('Hebrew and Gregorian'),
							'hijri'=>i18n::translate('Hijri'),
							'arabic'=>i18n::translate('Arabic')
						) as $cal=>$name) {
							echo '<option value="', $cal, '"';
							if ($CALENDAR_FORMAT==$cal) {
								echo ' selected="selected"';
							}
							echo '>', $name, '</option>';
						}
						?>
					</select></td>
				</tr>
			  </table>
				 <div id="hebrew-cal" style="display: none">
				  <table class="facts_table">
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Display Hebrew Thousands'), help_link('DISPLAY_JEWISH_THOUSANDS'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_DISPLAY_JEWISH_THOUSANDS', $DISPLAY_JEWISH_THOUSANDS); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Display Hebrew Gershayim'), help_link('DISPLAY_JEWISH_GERESHAYIM'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_DISPLAY_JEWISH_GERESHAYIM', $DISPLAY_JEWISH_GERESHAYIM); ?>
						</td>
					</tr>
				  </table>
				 </div>
			  <table class="facts_table">
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Use RIN number instead of GEDCOM ID'), help_link('USE_RIN'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_USE_RIN', $USE_RIN, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Automatically create globally unique IDs'), help_link('GENERATE_GUID'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_GENERATE_UIDS', $GENERATE_UIDS, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Individual ID prefix'), help_link('GEDCOM_ID_PREFIX'); ?>
					</td>
					<td class="optionbox">
						<input type="text" name="NEW_GEDCOM_ID_PREFIX" dir="ltr" value="<?php print $GEDCOM_ID_PREFIX; ?>" size="5" tabindex="<?php echo ++$i; ?>" />
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Family ID prefix'), help_link('FAM_ID_PREFIX'); ?>
					</td>
					<td class="optionbox">
						<input type="text" name="NEW_FAM_ID_PREFIX" dir="ltr" value="<?php print $FAM_ID_PREFIX; ?>" size="5" tabindex="<?php echo ++$i; ?>" />
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Source ID prefix'), help_link('SOURCE_ID_PREFIX'); ?>
					</td>
					<td class="optionbox">
						<input type="text" name="NEW_SOURCE_ID_PREFIX" dir="ltr" value="<?php print $SOURCE_ID_PREFIX; ?>" size="5" tabindex="<?php echo ++$i; ?>" />
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Repository ID prefix'), help_link('REPO_ID_PREFIX'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_REPO_ID_PREFIX" dir="ltr" value="<?php print $REPO_ID_PREFIX; ?>" size="5" tabindex="<?php echo ++$i; ?>" />
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Media ID prefix'), help_link('MEDIA_ID_PREFIX'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_MEDIA_ID_PREFIX" dir="ltr" value="<?php print $MEDIA_ID_PREFIX; ?>" size="5" tabindex="<?php echo ++$i; ?>" />
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Note ID prefix'), help_link('NOTE_ID_PREFIX'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_NOTE_ID_PREFIX" dir="ltr" value="<?php print $NOTE_ID_PREFIX; ?>" size="5" tabindex="<?php echo ++$i; ?>" />
					</td>
				</tr>
			  </table>
			</div>
			<!--  MULTIMEDIA -->
			<div id="config-media">
				<table class="facts_table">
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Enable multimedia features'), help_link('MULTI_MEDIA'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_MULTI_MEDIA', $MULTI_MEDIA, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="subbar">
							<?php print i18n::translate('General'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Keep links'), help_link('MEDIA_EXTERNAL'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_MEDIA_EXTERNAL', $MEDIA_EXTERNAL, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('MultiMedia directory'), help_link('MEDIA_DIRECTORY'); ?>
						</td>
						<td class="optionbox"><input type="text" size="50" name="NEW_MEDIA_DIRECTORY" value="<?php print $MEDIA_DIRECTORY; ?>" dir="ltr" tabindex="<?php echo ++$i; ?>" />
						<?php
						if (preg_match("/.*[a-zA-Z]{1}:.*/", $MEDIA_DIRECTORY)>0) print "<span class=\"error\">".i18n::translate('Media path should not contain a drive letter; media may not be displayed.')."</span>\n";
						?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Multi-Media directory levels to keep'), help_link('MEDIA_DIRECTORY_LEVELS'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_MEDIA_DIRECTORY_LEVELS" value="<?php print $MEDIA_DIRECTORY_LEVELS; ?>" size="5" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Width of generated thumbnails'), help_link('THUMBNAIL_WIDTH'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_THUMBNAIL_WIDTH" value="<?php print $THUMBNAIL_WIDTH; ?>" size="5" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Automatically generated thumbnails'), help_link('AUTO_GENERATE_THUMBS'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_AUTO_GENERATE_THUMBS', $AUTO_GENERATE_THUMBS, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Use thumbnail'), help_link('USE_THUMBS_MAIN'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_USE_THUMBS_MAIN', $USE_THUMBS_MAIN, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Use silhouettes'), help_link('USE_SILHOUETTE'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_USE_SILHOUETTE', $USE_SILHOUETTE, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show highlight images in people boxes'), help_link('SHOW_HIGHLIGHT_IMAGES'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_HIGHLIGHT_IMAGES', $SHOW_HIGHLIGHT_IMAGES, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Use Media Viewer'), help_link('USE_MEDIA_VIEWER'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_USE_MEDIA_VIEWER', $USE_MEDIA_VIEWER, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show file name in Media Viewer'), help_link('SHOW_MEDIA_FILENAME'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_MEDIA_FILENAME', $SHOW_MEDIA_FILENAME, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show download link in Media Viewer'), help_link('SHOW_MEDIA_DOWNLOAD'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_MEDIA_DOWNLOAD', $SHOW_MEDIA_DOWNLOAD, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="subbar">
							<?php print i18n::translate('Media Firewall');?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Use Media Firewall'), help_link('USE_MEDIA_FIREWALL'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_USE_MEDIA_FIREWALL', $USE_MEDIA_FIREWALL, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Media Firewall Root Directory'), help_link('MEDIA_FIREWALL_ROOTDIR'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_MEDIA_FIREWALL_ROOTDIR" size="50" dir="ltr" value="<?php print ($MEDIA_FIREWALL_ROOTDIR == $INDEX_DIRECTORY) ? "" : $MEDIA_FIREWALL_ROOTDIR; ?>" tabindex="<?php echo ++$i; ?>" /><br />
						<?php echo i18n::translate('When this field is empty, the <b>%s</b> directory will be used.', $INDEX_DIRECTORY); ?></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Protect Thumbnails of Protected Images'), help_link('MEDIA_FIREWALL_THUMBS'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_MEDIA_FIREWALL_THUMBS', $MEDIA_FIREWALL_THUMBS, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Who can view non-watermarked images?'), help_link('SHOW_NO_WATERMARK'); ?>
						</td>
						<td class="optionbox">
							<select size="1" name="NEW_SHOW_NO_WATERMARK">
								<?php write_access_option($SHOW_NO_WATERMARK); ?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Add watermarks to thumbnails?'), help_link('WATERMARK_THUMB'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_WATERMARK_THUMB', $WATERMARK_THUMB, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Store watermarked full size images on server?'), help_link('SAVE_WATERMARK_IMAGE'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SAVE_WATERMARK_IMAGE', $SAVE_WATERMARK_IMAGE, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Store watermarked thumbnails on server?'), help_link('SAVE_WATERMARK_THUMB'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SAVE_WATERMARK_THUMB', $SAVE_WATERMARK_THUMB, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
				</table>
			</div>
			<!-- ACCESS & PRIVACY -->
			<div id="access-options">
			  <table class="facts_table">
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Enable Privacy'), help_link('HIDE_LIVE_PEOPLE'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_HIDE_LIVE_PEOPLE', $HIDE_LIVE_PEOPLE, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Require visitor authentication'), help_link('REQUIRE_AUTHENTICATION'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_REQUIRE_AUTHENTICATION', $REQUIRE_AUTHENTICATION, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Page to show after Login'), help_link('PAGE_AFTER_LOGIN'); ?>
					</td>
					<td class="optionbox"><select name="NEW_PAGE_AFTER_LOGIN" tabindex="<?php echo ++$i; ?>">
							<option value="welcome" <?php if ($PAGE_AFTER_LOGIN=='welcome') print " selected=\"selected\""; ?>><?php print i18n::translate('Home'); ?></option>
							<option value="mygedview" <?php if ($PAGE_AFTER_LOGIN=='mygedview') print " selected=\"selected\""; ?>><?php print i18n::translate('My Page'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Welcome text on Login page'), help_link('WELCOME_TEXT_AUTH_MODE'); ?>
					</td>
					<td class="optionbox"><select name="NEW_WELCOME_TEXT_AUTH_MODE" tabindex="<?php echo ++$i; ?>">
							<option value="0" <?php if ($WELCOME_TEXT_AUTH_MODE=='0') print "selected=\"selected\""; ?>><?php print i18n::translate('No predefined text'); ?></option>
							<option value="1" <?php if ($WELCOME_TEXT_AUTH_MODE=='1') print "selected=\"selected\""; ?>><?php print i18n::translate('Predefined text that states all users can request a user account'); ?></option>
							<option value="2" <?php if ($WELCOME_TEXT_AUTH_MODE=='2') print "selected=\"selected\""; ?>><?php print i18n::translate('Predefined text that states admin will decide on each request for a user account'); ?></option>
							<option value="3" <?php if ($WELCOME_TEXT_AUTH_MODE=='3') print "selected=\"selected\""; ?>><?php print i18n::translate('Predefined text that states only family members can request a user account'); ?></option>
							<option value="4" <?php if ($WELCOME_TEXT_AUTH_MODE=='4') print "selected=\"selected\""; ?>><?php print i18n::translate('Choose user defined welcome text typed below'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Standard header for custom Welcome text'), help_link('WELCOME_TEXT_AUTH_MODE_CUST_HEAD'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_WELCOME_TEXT_CUST_HEAD', $WELCOME_TEXT_CUST_HEAD, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Custom Welcome text'), help_link('WELCOME_TEXT_AUTH_MODE_CUST'); ?>
					</td>
					<td class="optionbox"><textarea name="NEW_WELCOME_TEXT_AUTH_MODE_4" rows="5" cols="60" dir="ltr" tabindex="<?php echo ++$i; ?>"><?php print  $WELCOME_TEXT_AUTH_MODE_4; ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Show Acceptable Use agreement on «Request new user account» page'), help_link('SHOW_REGISTER_CAUTION'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_SHOW_REGISTER_CAUTION', $SHOW_REGISTER_CAUTION, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
			  </table>
			</div>
			<!-- NAMES -->
			<div id="name-options">
				<table class="facts_table">
					<tr>
						<td class="subbar">
							<?php print i18n::translate('Names');?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show married names on Individual list'), help_link('SHOW_MARRIED_NAMES'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_MARRIED_NAMES', $SHOW_MARRIED_NAMES, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Underline names in quotes'), help_link('UNDERLINE_NAME_QUOTES'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_UNDERLINE_NAME_QUOTES', $UNDERLINE_NAME_QUOTES, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show ID numbers next to names'), help_link('SHOW_ID_NUMBERS'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_ID_NUMBERS', $SHOW_ID_NUMBERS, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="subbar">
							<?php print i18n::translate('Common Surnames');?>
						</td>
						</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Min. no. of occurrences to be a "Common Surname"'), help_link('COMMON_NAMES_THRESHOLD'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_COMMON_NAMES_THRESHOLD" value="<?php print $COMMON_NAMES_THRESHOLD; ?>" size="5" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Names to add to Common Surnames (comma separated)'), help_link('COMMON_NAMES_ADD'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_COMMON_NAMES_ADD" dir="ltr" value="<?php print $COMMON_NAMES_ADD; ?>" size="50" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Names to remove from Common Surnames (comma separated)'), help_link('COMMON_NAMES_REMOVE'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_COMMON_NAMES_REMOVE" dir="ltr" value="<?php print $COMMON_NAMES_REMOVE; ?>" size="50" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
				</table>
			</div>
			<!-- LAYOUT -->
			<div id="layout-options">
				<table class="facts_table">
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Pedigree generations'), help_link('DEFAULT_PEDIGREE_GENERATIONS'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_DEFAULT_PEDIGREE_GENERATIONS" value="<?php print $DEFAULT_PEDIGREE_GENERATIONS; ?>" size="5" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Maximum Pedigree generations'), help_link('MAX_PEDIGREE_GENERATIONS'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_MAX_PEDIGREE_GENERATIONS" value="<?php print $MAX_PEDIGREE_GENERATIONS; ?>" size="5" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Maximum Descendancy generations'), help_link('MAX_DESCENDANCY_GENERATIONS'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_MAX_DESCENDANCY_GENERATIONS" value="<?php print $MAX_DESCENDANCY_GENERATIONS; ?>" size="5" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Default Pedigree chart layout'), help_link('PEDIGREE_LAYOUT'); ?>
						</td>
						<td class="optionbox"><select name="NEW_PEDIGREE_LAYOUT" tabindex="<?php echo ++$i; ?>">
								<option value="yes" <?php if ($PEDIGREE_LAYOUT) print "selected=\"selected\""; ?>><?php print i18n::translate('Landscape'); ?></option>
								<option value="no" <?php if (!$PEDIGREE_LAYOUT) print "selected=\"selected\""; ?>><?php print i18n::translate('Portrait'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Place levels to show in person boxes'), help_link('SHOW_PEDIGREE_PLACES'); ?>
						</td>
						<td class="optionbox"><input type="text" size="5" name="NEW_SHOW_PEDIGREE_PLACES" value="<?php print $SHOW_PEDIGREE_PLACES; ?>" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Place levels to show on lists'), help_link('SHOW_LIST_PLACES'); ?>
						</td>
						<td class="optionbox"><input type="text" size="5" name="NEW_SHOW_LIST_PLACES" value="<?php print $SHOW_LIST_PLACES; ?>" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Zoom boxes on charts'), help_link('ZOOM_BOXES'); ?>
						</td>
						<td class="optionbox"><select name="NEW_ZOOM_BOXES" tabindex="<?php echo ++$i; ?>">
								<option value="disabled" <?php if ($ZOOM_BOXES=='disabled') print "selected=\"selected\""; ?>><?php print i18n::translate('Disabled'); ?></option>
								<option value="mouseover" <?php if ($ZOOM_BOXES=='mouseover') print "selected=\"selected\""; ?>><?php print i18n::translate('On Mouse Over'); ?></option>
								<option value="mousedown" <?php if ($ZOOM_BOXES=='mousedown') print "selected=\"selected\""; ?>><?php print i18n::translate('On Mouse Down'); ?></option>
								<option value="click" <?php if ($ZOOM_BOXES=='click') print "selected=\"selected\""; ?>><?php print i18n::translate('On Mouse Click'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('PopUp links on charts'), help_link('LINK_ICONS'); ?>
						</td>
						<td class="optionbox"><select name="NEW_LINK_ICONS" tabindex="<?php echo ++$i; ?>">
								<option value="disabled" <?php if ($LINK_ICONS=='disabled') print "selected=\"selected\""; ?>><?php print i18n::translate('Disabled'); ?></option>
								<option value="mouseover" <?php if ($LINK_ICONS=='mouseover') print "selected=\"selected\""; ?>><?php print i18n::translate('On Mouse Over'); ?></option>
								<option value="click" <?php if ($LINK_ICONS=='click') print "selected=\"selected\""; ?>><?php print i18n::translate('On Mouse Click'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Default tab to show on Individual page'), help_link('GEDCOM_DEFAULT_TAB'); ?>
						</td>
						<td class="optionbox">
						<?php echo edit_field_default_tab('NEW_GEDCOM_DEFAULT_TAB', $GEDCOM_DEFAULT_TAB, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Automatically expand list of events of close relatives'), help_link('EXPAND_RELATIVES_EVENTS'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_EXPAND_RELATIVES_EVENTS', $EXPAND_RELATIVES_EVENTS, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show events of close relatives on Individual page'), help_link('SHOW_RELATIVES_EVENTS'); ?>
						</td>
						<td class="optionbox">
							<input type="hidden" name="NEW_SHOW_RELATIVES_EVENTS" value="<?php echo $SHOW_RELATIVES_EVENTS; ?>" />
							<table>
				<?php
				$rel_events=array(
					array(null,         null,         '_DEAT_SPOU'),
					array('_BIRT_CHIL', '_MARR_CHIL', '_DEAT_CHIL'),
					array('_BIRT_GCHI', '_MARR_GCHI', '_DEAT_GCHI'),
					array('_BIRT_GGCH', '_MARR_GGCH', '_DEAT_GGCH'),
					array(null,         '_MARR_FATH', '_DEAT_FATH'),
					array(null,         '_MARR_FAMC', null),
					array(null,         '_MARR_MOTH', '_DEAT_MOTH'),
					array('_BIRT_SIBL', '_MARR_SIBL', '_DEAT_SIBL'),
					array('_BIRT_HSIB', '_MARR_HSIB', '_DEAT_HSIB'),
					array('_BIRT_NEPH', '_MARR_NEPH', '_DEAT_NEPH'),
					array(null,         null,         '_DEAT_GPAR'),
					array(null,         null,         '_DEAT_GGPA'),
					array('_BIRT_FSIB', '_MARR_FSIB', '_DEAT_FSIB'),
					array('_BIRT_MSIB', '_MARR_MSIB', '_DEAT_MSIB'),
					array('_BIRT_COUS', '_MARR_COUS', '_DEAT_COUS'),
					array('_FAMC_EMIG', null,         null),
					array('_FAMC_RESI', null,         null),
				);
				foreach ($rel_events as $row) {
					echo '<tr>';
					foreach ($row as $col) {
						echo '<td>';
						if (is_null($col)) {
							echo '&nbsp;';
						} else {
							echo "<input type=\"checkbox\" name=\"SHOW_RELATIVES_EVENTS_checkbox\" value=\"".$col."\"";
							if (strstr($SHOW_RELATIVES_EVENTS, $col)) {
								echo " checked=\"checked\"";
							}
							echo " onchange=\"var old=document.configform.NEW_SHOW_RELATIVES_EVENTS.value; if (this.checked) old+=','+this.value; else old=old.replace(/".$col."/g,''); old=old.replace(/[,]+/gi,','); old=old.replace(/^[,]/gi,''); old=old.replace(/[,]$/gi,''); document.configform.NEW_SHOW_RELATIVES_EVENTS.value=old\" /> ";
							echo translate_fact($col);
						}
						echo '</td>';
					}
					echo '</td>';
				}
				?>
							</table>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Postal Code position'), help_link('POSTAL_CODE'); ?>
						</td>
						<td class="optionbox"><select name="NEW_POSTAL_CODE" tabindex="<?php echo ++$i; ?>">
								<option value="yes" <?php if ($POSTAL_CODE) print "selected=\"selected\""; ?>><?php print ucfirst(i18n::translate('after')); ?></option>
								<option value="no" <?php if (!$POSTAL_CODE) print "selected=\"selected\""; ?>><?php print ucfirst(i18n::translate('before')); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Maximum number of surnames on individual list'), help_link('SUBLIST_TRIGGER_I'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_SUBLIST_TRIGGER_I" value="<?php print $SUBLIST_TRIGGER_I; ?>" size="5" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Maximum number of surnames on family list'), help_link('SUBLIST_TRIGGER_F'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_SUBLIST_TRIGGER_F" value="<?php print $SUBLIST_TRIGGER_F; ?>" size="5" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Surname list style'), help_link('SURNAME_LIST_STYLE'); ?>
						</td>
						<td class="optionbox"><select name="NEW_SURNAME_LIST_STYLE" tabindex="<?php echo ++$i; ?>">
								<option value="style2" <?php if ($SURNAME_LIST_STYLE=="style2") print "selected=\"selected\""; ?>><?php print i18n::translate('Table'); ?></option>
								<option value="style3" <?php if ($SURNAME_LIST_STYLE=="style3") print "selected=\"selected\""; ?>><?php print i18n::translate('Tagcloud'); ?></option>
							</select>
						</td>
					</tr>
				</table>
			</div>
			<!-- HIDE & SHOW -->
			<div id="hide-show">
				<table class="facts_table">
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Upcoming Events block day limit'), help_link('DAYS_TO_SHOW_LIMIT'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_DAYS_TO_SHOW_LIMIT" value="<?php print $DAYS_TO_SHOW_LIMIT; ?>" size="2" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>

					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show empty boxes on Pedigree charts'), help_link('SHOW_EMPTY_BOXES'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_EMPTY_BOXES', $SHOW_EMPTY_BOXES, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Abbreviate chart labels'), help_link('ABBREVIATE_CHART_LABELS'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_ABBREVIATE_CHART_LABELS', $ABBREVIATE_CHART_LABELS, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show Birth and Death details on charts'), help_link('PEDIGREE_FULL_DETAILS'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_PEDIGREE_FULL_DETAILS', $PEDIGREE_FULL_DETAILS, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show gender icon on charts'), help_link('PEDIGREE_SHOW_GENDER'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_PEDIGREE_SHOW_GENDER', $PEDIGREE_SHOW_GENDER, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show age of parents next to child\'s birthdate'), help_link('SHOW_PARENTS_AGE'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_PARENTS_AGE', $SHOW_PARENTS_AGE, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show LDS ordinance codes in chart boxes'), help_link('SHOW_LDS_AT_GLANCE'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_LDS_AT_GLANCE', $SHOW_LDS_AT_GLANCE, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Other facts to show in charts'), help_link('CHART_BOX_TAGS'); ?>
						</td>
						<td class="optionbox">
							<input type="text" size="50" name="NEW_CHART_BOX_TAGS" value="<?php print $CHART_BOX_TAGS; ?>" dir="ltr" tabindex="<?php echo ++$i; ?>" />
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Allow users to see raw GEDCOM records'), help_link('SHOW_GEDCOM_RECORD'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_GEDCOM_RECORD', $SHOW_GEDCOM_RECORD, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Hide GEDCOM errors'), help_link('HIDE_GEDCOM_ERRORS'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_HIDE_GEDCOM_ERRORS', $HIDE_GEDCOM_ERRORS, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Add spaces where notes were wrapped'), help_link('WORD_WRAPPED_NOTES'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_WORD_WRAPPED_NOTES', $WORD_WRAPPED_NOTES, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show Fact icons'), help_link('SHOW_FACT_ICONS'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_FACT_ICONS', $SHOW_FACT_ICONS, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Automatically expand sources'), help_link('EXPAND_SOURCES'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_EXPAND_SOURCES', $EXPAND_SOURCES, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Automatically expand notes'), help_link('EXPAND_NOTES'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_EXPAND_NOTES', $EXPAND_NOTES, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show all Notes and Source references on Notes and Sources tabs'), help_link('SHOW_LEVEL2_NOTES'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_LEVEL2_NOTES', $SHOW_LEVEL2_NOTES, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show Date Differences'), help_link('SHOW_AGE_DIFF'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_AGE_DIFF', $SHOW_AGE_DIFF, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Favorites icon'), help_link('FAVICON'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_FAVICON" value="<?php print $FAVICON; ?>" size="40" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show hit counters'), help_link('SHOW_COUNTER'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_COUNTER', $SHOW_COUNTER, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show spider tagline'), help_link('SHOW_SPIDER_TAGLINE'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_SPIDER_TAGLINE', $SHOW_SPIDER_TAGLINE, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show execution statistics'), help_link('SHOW_STATS'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_STATS', $SHOW_STATS, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show GEDCOM record last change date on lists'), help_link('SHOW_LAST_CHANGE'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_LAST_CHANGE', $SHOW_LAST_CHANGE, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Show estimated dates for birth and death'), help_link('SHOW_EST_LIST_DATES'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_SHOW_EST_LIST_DATES', $SHOW_EST_LIST_DATES, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
				</table>
			</div>
			<!-- EDIT -->
			<div id="edit-options">
			  <table class="facts_table">
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Enable online editing'), help_link('ALLOW_EDIT_GEDCOM'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_ALLOW_EDIT_GEDCOM', $ALLOW_EDIT_GEDCOM, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Enable Autocomplete'), help_link('ENABLE_AUTOCOMPLETE'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_ENABLE_AUTOCOMPLETE', $ENABLE_AUTOCOMPLETE, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Individual Add Facts'), help_link('INDI_FACTS_ADD'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_INDI_FACTS_ADD" value="<?php print $INDI_FACTS_ADD; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Unique Individual Facts'), help_link('INDI_FACTS_UNIQUE'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_INDI_FACTS_UNIQUE" value="<?php print $INDI_FACTS_UNIQUE; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Quick Individual Facts'), help_link('INDI_FACTS_QUICK'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_INDI_FACTS_QUICK" value="<?php print $INDI_FACTS_QUICK; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Family Add Facts'), help_link('FAM_FACTS_ADD'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_FAM_FACTS_ADD" value="<?php print $FAM_FACTS_ADD; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Unique Family Facts'), help_link('FAM_FACTS_UNIQUE'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_FAM_FACTS_UNIQUE" value="<?php print $FAM_FACTS_UNIQUE; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Quick Family Facts'), help_link('FAM_FACTS_QUICK'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_FAM_FACTS_QUICK" value="<?php print $FAM_FACTS_QUICK; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Source Add Facts'), help_link('SOUR_FACTS_ADD'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_SOUR_FACTS_ADD" value="<?php print $SOUR_FACTS_ADD; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Unique Source Facts'), help_link('SOUR_FACTS_UNIQUE'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_SOUR_FACTS_UNIQUE" value="<?php print $SOUR_FACTS_UNIQUE; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Quick Source Facts'), help_link('SOUR_FACTS_QUICK'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_SOUR_FACTS_QUICK" value="<?php print $SOUR_FACTS_QUICK; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Repository Add Facts'), help_link('REPO_FACTS_ADD'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_REPO_FACTS_ADD" value="<?php print $REPO_FACTS_ADD; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Unique Repository Facts'), help_link('REPO_FACTS_UNIQUE'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_REPO_FACTS_UNIQUE" value="<?php print $REPO_FACTS_UNIQUE; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Quick Repository Facts'), help_link('REPO_FACTS_QUICK'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_REPO_FACTS_QUICK" value="<?php print $REPO_FACTS_QUICK; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Autoclose edit window'), help_link('EDIT_AUTOCLOSE'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_EDIT_AUTOCLOSE', $EDIT_AUTOCLOSE, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Split places in Edit mode'), help_link('SPLIT_PLACES'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_SPLIT_PLACES', $SPLIT_PLACES, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Facts to always show on Quick Update'), help_link('QUICK_REQUIRED_FACTS'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_QUICK_REQUIRED_FACTS" value="<?php print $QUICK_REQUIRED_FACTS; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Facts for families to always show on Quick Update'), help_link('QUICK_REQUIRED_FAMFACTS'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_QUICK_REQUIRED_FAMFACTS" value="<?php print $QUICK_REQUIRED_FAMFACTS; ?>" size="40" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Surname tradition'), help_link('SURNAME_TRADITION'); ?>
					</td>
					<td class="optionbox"><select name="NEW_SURNAME_TRADITION" tabindex="<?php echo ++$i; ?>">
						<?php
							foreach (array(
								'paternal'=>i18n::translate_c('Surname tradition', 'Paternal'),
								'spanish'=>i18n::translate_c('Surname tradition', 'Spanish'),
								'portuguese'=>i18n::translate_c('Surname tradition', 'Portuguese'),
								'icelandic'=>i18n::translate_c('Surname tradition', 'Icelandic'),
								'polish'=>i18n::translate_c('Surname tradition', 'Polish'),
								'none'=>i18n::translate_c('Surname tradition', 'None')
							) as $value=>$desc) {
								print '<option value="'.$value.'"';
								if ($SURNAME_TRADITION==$value) print ' selected="selected"';
								print '>'.$desc.'</option>';
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Advanced name facts'), help_link('ADVANCED_NAME_FACTS'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_ADVANCED_NAME_FACTS" value="<?php print $ADVANCED_NAME_FACTS; ?>" size="40" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Advanced place name facts'), help_link('ADVANCED_PLAC_FACTS'); ?>
					</td>
					<td class="optionbox"><input type="text" name="NEW_ADVANCED_PLAC_FACTS" value="<?php print $ADVANCED_PLAC_FACTS; ?>" size="40" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Use GeoNames database'), help_link('USE_GEONAMES'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_USE_GEONAMES', $USE_GEONAMES, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Use full source citations'), help_link('FULL_SOURCES'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_FULL_SOURCES', $FULL_SOURCES, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Source type'), help_link('PREFER_LEVEL2_SOURCES'); ?>
					</td>
					<td class="optionbox"><select name="NEW_PREFER_LEVEL2_SOURCES" tabindex="<?php echo ++$i; ?>">
							<option value="0" <?php if ($PREFER_LEVEL2_SOURCES==='0') print " selected=\"selected\""; ?>><?php print i18n::translate('None'); ?></option>
							<option value="1" <?php if ($PREFER_LEVEL2_SOURCES==='1' || $PREFER_LEVEL2_SOURCES===true) print " selected=\"selected\""; ?>><?php print i18n::translate('Facts'); ?></option>
							<option value="2" <?php if ($PREFER_LEVEL2_SOURCES==='2' || $PREFER_LEVEL2_SOURCES===false) print " selected=\"selected\""; ?>><?php print i18n::translate('Record'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Do not update the CHAN (Last Change) record'), help_link('no_update_CHAN'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_NO_UPDATE_CHAN', $NO_UPDATE_CHAN, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
			  </table>
			</div>
			<!-- USER OPTIONS -->
			<div id="user-options">
			  <table class="facts_table">
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Show contextual <b>?</b> Help links'), help_link('SHOW_CONTEXT_HELP'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_SHOW_CONTEXT_HELP', $SHOW_CONTEXT_HELP, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Theme directory'), help_link('THEME_DIR'); ?>
					</td>
					<td class="optionbox">
						<select name="NEW_THEME_DIR" dir="ltr" tabindex="<?php echo ++$i; ?>">
							<?php
								foreach (get_theme_names() as $themename=>$themedir) {
									print "<option value=\"".$themedir."\"";
									if ($themedir == $THEME_DIR) print " selected=\"selected\"";
									print ">".$themename."</option>\n";
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="descriptionbox wrap width20">
						<?php echo i18n::translate('Display theme dropdown selector for theme changes'), help_link('ALLOW_THEME_DROPDOWN'); ?>
					</td>
					<td class="optionbox">
						<?php echo edit_field_yes_no('NEW_ALLOW_THEME_DROPDOWN', $ALLOW_THEME_DROPDOWN, 'tabindex="'.(++$i).'"'); ?>
					</td>
				</tr>
			 </table>
			</div>
			<!-- CONTACT & SITE -->
			<div id="other-options">
			  <table class="facts_table">
					<tr>
						<td class="subbar">
							<?php print i18n::translate('Contact Information'); ?>
						</td>
					</tr>
					<tr>
						<?php
						if (empty($WEBTREES_EMAIL)) {
							$WEBTREES_EMAIL = "webtrees-noreply@".preg_replace("/^www\./i", "", $_SERVER["SERVER_NAME"]);
						}
						?>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('webtrees reply address'), help_link('WEBTREES_EMAIL'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_WEBTREES_EMAIL" value="<?php print $WEBTREES_EMAIL; ?>" size="80" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Genealogy contact'), help_link('CONTACT_USER_ID'); ?>
						</td>
						<td class="optionbox"><select name="NEW_CONTACT_USER_ID" tabindex="<?php echo ++$i; ?>">
						<?php
							$CONTACT_USER_ID=get_gedcom_setting(WT_GED_ID, 'CONTACT_USER_ID');
							foreach (get_all_users() as $user_id=>$user_name) {
								if (get_user_setting($user_id, 'verified_by_admin')=="yes") {
									print "<option value=\"".$user_id."\"";
									if ($CONTACT_USER_ID==$user_id) print " selected=\"selected\"";
									print ">".getUserFullName($user_id)." - ".$user_name."</option>\n";
								}
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Support contact'), help_link('WEBMASTER_USER_ID'); ?>
						</td>
						<td class="optionbox"><select name="NEW_WEBMASTER_USER_ID" tabindex="<?php echo ++$i; ?>">
						<?php
							$WEBMASTER_USER_ID=get_gedcom_setting(WT_GED_ID, 'WEBMASTER_USER_ID');
							foreach (get_all_users() as $user_id=>$user_name) {
								if (userIsAdmin($user_id)) {
									print "<option value=\"".$user_id."\"";
									if ($WEBMASTER_USER_ID==$user_id) print " selected=\"selected\"";
									print ">".getUserFullName($user_id)." - ".$user_name."</option>\n";
								}
							}
						?>
						</select>
						</td>
					</tr>
					<tr>
						<td class="subbar">
							<?php print i18n::translate('Web Site and META Tag Settings'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Main WebSite URL'), help_link('HOME_SITE_URL'); ?>
						</td>
						<td class="optionbox"><input type="text" name="NEW_HOME_SITE_URL" value="<?php print $HOME_SITE_URL; ?>" size="50" dir="ltr" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Main WebSite text'), help_link('HOME_SITE_TEXT'); ?>
						</td>
						<td class="optionbox"><input type="text" dir="ltr" name="NEW_HOME_SITE_TEXT" value="<?php print htmlspecialchars($HOME_SITE_TEXT, ENT_COMPAT, 'UTF-8'); ?>" size="50" tabindex="<?php echo ++$i; ?>" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Author META tag'), help_link('META_AUTHOR'); ?>
						</td>
						<td class="optionbox"><input type="text" dir="ltr" name="NEW_META_AUTHOR" value="<?php print $META_AUTHOR; ?>" tabindex="<?php echo ++$i; ?>" /><br />
						<?php echo i18n::translate('Leave this field empty to use the full name of the Genealogy contact.'); ?></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Publisher META tag'), help_link('META_PUBLISHER'); ?>
						</td>
						<td class="optionbox"><input type="text" dir="ltr" name="NEW_META_PUBLISHER" value="<?php print $META_PUBLISHER; ?>" tabindex="<?php echo ++$i; ?>" /><br />
						<?php echo i18n::translate('Leave this field empty to use the full name of the Genealogy contact.'); ?></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Copyright META tag'), help_link('META_COPYRIGHT'); ?>
						</td>
						<td class="optionbox"><input type="text" dir="ltr" name="NEW_META_COPYRIGHT" value="<?php print $META_COPYRIGHT; ?>" tabindex="<?php echo ++$i; ?>" /><br />
						<?php echo i18n::translate('Leave this field empty to use the full name of the  Genealogy contact.'); ?></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Description META tag'), help_link('META_DESCRIPTION'); ?>
						</td>
						<td class="optionbox"><input type="text" dir="ltr" name="NEW_META_DESCRIPTION" value="<?php print $META_DESCRIPTION; ?>" tabindex="<?php echo ++$i; ?>" /><br />
						<?php print i18n::translate('Leave this field empty to use the title of the currently active database.'); ?></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Page-topic META tag'), help_link('META_PAGE_TOPIC'); ?>
						</td>
						<td class="optionbox"><input type="text" dir="ltr" name="NEW_META_PAGE_TOPIC" value="<?php print $META_PAGE_TOPIC; ?>" tabindex="<?php echo ++$i; ?>" /><br />
						<?php print i18n::translate('Leave this field empty to use the title of the currently active database.'); ?></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Audience META tag'), help_link('META_AUDIENCE'); ?>
						</td>
						<td class="optionbox"><input type="text" dir="ltr" name="NEW_META_AUDIENCE" value="<?php print $META_AUDIENCE; ?>" tabindex="<?php echo ++$i; ?>" /><br />
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Page-type META tag'), help_link('META_PAGE_TYPE'); ?>
						</td>
						<td class="optionbox"><input type="text" dir="ltr" name="NEW_META_PAGE_TYPE" value="<?php print $META_PAGE_TYPE; ?>" tabindex="<?php echo ++$i; ?>" /><br />
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Robots META tag'), help_link('META_ROBOTS'); ?>
						</td>
						<td class="optionbox"><input type="text" dir="ltr" name="NEW_META_ROBOTS" value="<?php print $META_ROBOTS; ?>" tabindex="<?php echo ++$i; ?>" /><br />
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('How often should crawlers revisit META tag'), help_link('META_REVISIT'); ?>
						</td>
						<td class="optionbox"><input type="text" dir="ltr" name="NEW_META_REVISIT" value="<?php print $META_REVISIT; ?>" tabindex="<?php echo ++$i; ?>" /><br />
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Keywords META tag'), help_link('META_KEYWORDS'); ?>
						</td>
						<td class="optionbox"><input type="text" dir="ltr" name="NEW_META_KEYWORDS" value="<?php print $META_KEYWORDS; ?>" tabindex="<?php echo ++$i; ?>" size="75" /><br />
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Add to TITLE header tag'), help_link('META_TITLE'); ?>
						</td>
						<td class="optionbox"><input type="text" dir="ltr" name="NEW_META_TITLE" value="<?php print $META_TITLE; ?>" tabindex="<?php echo ++$i; ?>" size="75" /></td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('Enable RSS'), help_link('ENABLE_RSS'); ?>
						</td>
						<td class="optionbox">
							<?php echo edit_field_yes_no('NEW_ENABLE_RSS', $ENABLE_RSS, 'tabindex="'.(++$i).'"'); ?>
						</td>
					</tr>
					<tr>
						<td class="descriptionbox wrap width20">
							<?php echo i18n::translate('RSS Format'), help_link('RSS_FORMAT'); ?>
						</td>
						<td class="optionbox"><select name="NEW_RSS_FORMAT" dir="ltr" tabindex="<?php echo ++$i; ?>">
								<option value="ATOM" <?php if ($RSS_FORMAT=="ATOM") print "selected=\"selected\""; ?>>ATOM 1.0</option>
								<!--option value="ATOM0.3" <?php if ($RSS_FORMAT=="ATOM0.3") print "selected=\"selected\""; ?>>ATOM 0.3</option-->
								<option value="RSS2.0" <?php if ($RSS_FORMAT=="RSS2.0") print "selected=\"selected\""; ?>>RSS 2.0</option>
								<!--option value="RSS0.91" <?php if ($RSS_FORMAT=="RSS0.91") print "selected=\"selected\""; ?>>RSS 0.91</option-->
								<option value="RSS1.0" <?php if ($RSS_FORMAT=="RSS1.0") print "selected=\"selected\""; ?>>RSS 1.0</option>
							</select>
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<table class="facts_table" border="0">
			<tr><td style="padding: 5px" class="topbottombar">
			<input type="submit" tabindex="<?php echo ++$i; ?>" value="<?php print i18n::translate('Save configuration'); ?>" onclick="closeHelp();" />
			&nbsp;&nbsp;
			<input type="reset" tabindex="<?php echo ++$i; ?>" value="<?php print i18n::translate('Reset'); ?>" /><br />
			</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
<br />
<?php if (!WT_GED_ID) { ?>
<script language="JavaScript" type="text/javascript">
	helpPopup('welcome_new_help');
</script>
<?php
}

// NOTE: Put the focus on the GEDCOM title field
?>
<script language="JavaScript" type="text/javascript">
document.configform.gedcom_title.focus();
</script>
<?php
print_footer();
?>
