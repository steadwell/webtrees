<?php
/**
 * PGV to webtrees transfer wizard
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
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
 * @version $Id: pgv_to_wt.php 9030 2010-07-07 21:54:31Z greg $
 */

define('WT_SCRIPT_NAME', 'pgv_to_wt.php');
require './includes/session.php';
require WT_ROOT.'includes/functions/functions_edit.php';

// We can only import into an empty system, so deny access if we have already created a gedcom or added users.
if (WT_GED_ID || get_user_count()>1) {
	header('Location: index.php');
	exit;
}

// Must be logged in as an admin
if (!WT_USER_IS_ADMIN) {
	header('Location: login.php?url='.WT_SCRIPT_NAME);
	exit;
}

print_header(i18n::translate('PhpGedView to webtrees transfer wizard'));

echo
	'<style type="text/css">
		#container {width: 70%; margin:15px auto; border: 1px solid gray; padding: 10px;}
		#container dl {margin:0 0 40px 25px;}
		#container dt {display:inline; width: 320px; font-weight:normal;}
		#container dd {color: #81A9CB; margin-bottom:20px;font-weight:bold;}
		#container p {color: #81A9CB; font-size: 14px; font-style: italic; font-weight:bold; padding: 0 5px 5px; align: top;
		h2 {color: #81A9CB;}
		.good {color: green;}
		.bad {color: red; font-weight: bold;}
		.indifferent {color: blue;}
	</style>';

$error='';
$warning='';
$PGV_PATH=safe_POST('PGV_PATH');

if ($PGV_PATH) {
	if (!is_dir($PGV_PATH) || !is_readable($PGV_PATH.'/config.php')) {
		$error=i18n::translate('The specified directory does not contain an installation of PhpGedView');
	} else {
		// Load the configuration settings
		$config_php=file_get_contents($PGV_PATH.'/config.php');
		// The easiest way to do this is to exec() the file - but not lines containing require or PHP tags
		$config_php=preg_replace(
			array(
				'/^\s*(include|require).*/',
				'/.*<\?php.*/',
				'/.*\?>.*/'
			), '', $config_php
		);
		eval($config_php);
		// $INDEX_DIRECTORY can be either absolute or relative to the PhpGedView root.
		if (preg_match('/^(\/|\\|[A-Z]:)/', $INDEX_DIRECTORY)) {
			$INDEX_DIRECTORY=realpath($INDEX_DIRECTORY);
		} else {
			$INDEX_DIRECTORY=realpath($PGV_PATH.'/'.$INDEX_DIRECTORY);
		}
		$wt_config=parse_ini_file(WT_ROOT.'data/config.ini.php');
		if ($DBHOST!=$wt_config['dbhost'] || $DBHOST!=$wt_config['dbhost']) {
			$error=i18n::translate('PhpGedView must use the same database as <b>webtrees</b>');
			unset($wt_config);
		} else {
			unset($wt_config);
			try {
				$PGV_SCHEMA_VERSION=WT_DB::prepare(
					"SELECT site_setting_value FROM {$DBNAME}.{$TBLPREFIX}site_setting WHERE site_setting_name='PGV_SCHEMA_VERSION'"
				)->fetchOne();
				if ($PGV_SCHEMA_VERSION<10) {
					$error=i18n::translate('The version of %s is too old', 'PhpGedView');
				} elseif ($PGV_SCHEMA_VERSION>14) {
					$error=i18n::translate('The version of %s is too new', 'PhpGedView');
				} else {
					$IS_ADMIN=WT_DB::prepare(
						"SELECT setting_value FROM {$DBNAME}.{$TBLPREFIX}user_setting JOIN {$DBNAME}.{$TBLPREFIX}user USING (user_id) WHERE setting_name='canadmin' AND user_name=?"
					)->execute(array(WT_USER_NAME))->fetchOne();
					if (!$IS_ADMIN) {
						$error='Your username must exist in PhpGedView as an administrator';
					}
				}
			} catch (PDOException $ex) {
				$error=i18n::translate('The PhpGedView database configuration settings are bad: '.$ex);
			}
		}
	}
}

if ($error || empty($PGV_PATH)) {
	// Prompt for location of PhpGedView installation
	echo '<div id="container">';
	echo 
		'<h2>',
		i18n::translate('PhpGedView to <b>webtrees</b> transfer wizard'),
		help_link('PGV_WIZARD'),
		'</h2>';
	if ($error) {
		echo '<p class="bad">', $error, '</p>';
	}
	echo
		'<form action="', WT_SCRIPT_NAME, '" method="post">',
		'<p>', i18n::translate('Where is your PhpGedView installation?'), '</p>',
		'<dl>',
		'<dt>',i18n::translate('Installation directory'), '</dt>',
		'<dd><input type="text" name="PGV_PATH" size="40" value="'.htmlspecialchars($PGV_PATH).'"><dd>',
		'</dl>';
	// Finish
	echo '<div class="center"><input type="submit" value="'.i18n::translate('next').'"></div>';
	echo '</form>';
	echo '</div>';
	exit;
}

////////////////////////////////////////////////////////////////////////////////

echo '<p>config.php => wt_site_setting ...</p>'; flush();
// TODO May need to set 'DATA_DIRECTORY' to $INDEX_DIRECTORY when dealing with media??
@set_site_setting('STORE_MESSAGES',                  $PGV_STORE_MESSAGES);
@set_site_setting('SMTP_SIMPLE_MAIL',                $PGV_SIMPLE_MAIL);
@set_site_setting('USE_REGISTRATION_MODULE',         $USE_REGISTRATION_MODULE);
@set_site_setting('REQUIRE_ADMIN_AUTH_REGISTRATION', $REQUIRE_ADMIN_AUTH_REGISTRATION);
@set_site_setting('ALLOW_USER_THEMES',               $ALLOW_USER_THEMES);
@set_site_setting('ALLOW_CHANGE_GEDCOM',             $ALLOW_CHANGE_GEDCOM);
// Don't copy $LOGFILE_CREATE - it is no longer used
// Don't copy $LOG_LANG_ERROR - it is no longer used
@set_site_setting('SESSION_SAVE_PATH',               $PGV_SESSION_SAVE_PATH);
@set_site_setting('SESSION_TIME',                    $PGV_SESSION_TIME);
// Don't copy $SERVER_URL - it will not be applicable!
// Don't copy $LOGIN_URL - it will not be applicable!
// $MAX_VIEWS and $MAX_VIEW_TIME are no longer used
// Don't copy $MEMORY_LIMIT - use the value from setup.php
// Don't copy $COMMIT_COMMAND - it will not be applicable!
@set_site_setting('SMTP_ACTIVE',                     $PGV_SMTP_ACTIVE);
@set_site_setting('SMTP_HOST',                       $PGV_SMTP_HOST);
@set_site_setting('SMTP_HELO',                       $PGV_SMTP_HELO);
@set_site_setting('SMTP_PORT',                       $PGV_SMTP_PORT);
@set_site_setting('SMTP_AUTH',                       $PGV_SMTP_AUTH);
@set_site_setting('SMTP_AUTH_USER',                  $PGV_SMTP_AUTH_USER);
@set_site_setting('SMTP_AUTH_PASS',                  $PGV_SMTP_AUTH_PASS);
@set_site_setting('SMTP_SSL',                        $PGV_SMTP_SSL);
@set_site_setting('SMTP_FROM_NAME',                  $PGV_SMTP_FROM_NAME);


////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_site_setting => wt_site_setting ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##site_setting` (setting_name, setting_value)".
	" SELECT site_setting_name, site_setting_value FROM {$DBNAME}.{$TBLPREFIX}site_setting".
	" WHERE site_setting_name IN ('DEFAULT_GEDCOM', 'LAST_CHANGE_EMAIL')"
)->execute();
	
////////////////////////////////////////////////////////////////////////////////

if ($PGV_SCHEMA_VERSION>=12) {
	echo '<p>pgv_gedcom => wt_gedcom ...</p>'; flush();
	WT_DB::prepare(
		"INSERT INTO `##gedcom` (gedcom_id, gedcom_name)".
		" SELECT gedcom_id, gedcom_name FROM {$DBNAME}.{$TBLPREFIX}gedcom"
	)->execute();

	echo '<p>pgv_gedcom_setting => wt_gedcom_setting ...</p>'; flush();
	WT_DB::prepare(
		"INSERT INTO `##gedcom_setting` (gedcom_id, setting_name, setting_value)".
		" SELECT gedcom_id, setting_name, setting_value FROM {$DBNAME}.{$TBLPREFIX}gedcom_setting"
	)->execute();

	echo '<p>pgv_user => wt_user ...</p>'; flush();
	WT_DB::prepare(
		"INSERT IGNORE INTO `##user` (user_id, user_name, real_name, email, password)".
		" SELECT user_id, user_name, CONCAT(us1.setting_value, ' ', us2.setting_value), us3.setting_value, password FROM {$DBNAME}.{$TBLPREFIX}user".
		" JOIN {$DBNAME}.{$TBLPREFIX}user_setting us1 USING (user_id)".
		" JOIN {$DBNAME}.{$TBLPREFIX}user_setting us2 USING (user_id)".
		" JOIN {$DBNAME}.{$TBLPREFIX}user_setting us3 USING (user_id)".
		" WHERE us1.setting_name='firstname'".
		" AND us2.setting_name='lastname'".
		" AND us3.setting_name='email'"
	)->execute();

	echo '<p>pgv_user_setting => wt_user_setting ...</p>'; flush();
	WT_DB::prepare(
		"INSERT IGNORE INTO `##user_setting` (user_id, setting_name, setting_value)".
		" SELECT user_id, setting_name, setting_value FROM {$DBNAME}.{$TBLPREFIX}user_setting".
		" WHERE setting_name NOT IN ('email', 'firstname', 'lastname')"
	)->execute();

	echo '<p>pgv_user_gedcom_setting => wt_user_gedcom_setting ...</p>'; flush();
	WT_DB::prepare(
		"INSERT INTO `##user_gedcom_setting` (user_id, gedcom_id, setting_name, setting_value)".
		" SELECT user_id, gedcom_id, setting_name, setting_value FROM {$DBNAME}.{$TBLPREFIX}user_gedcom_setting"
	)->execute();

} else {
	// Copied from PGV's db_schema_11_12
	if (file_exists("{$INDEX_DIRECTORY}gedcoms.php")) {
		require_once "{$INDEX_DIRECTORY}gedcoms.php";
		if (isset($GEDCOMS) && is_array($GEDCOMS)) {
			foreach ($GEDCOMS as $array) {
				try {
					self::prepare("INSERT INTO `##gedcom` (gedcom_id, gedcom_name) VALUES (?,?)")
						->execute(array($array['id'], $array['gedcom']));
				} catch (PDOException $ex) {
					// Ignore duplicates
				}
				// insert gedcom
				foreach ($array as $key=>$value) {
					if ($key!='id' && $key!='gedcom' && $key!='commonsurnames') {
						try {
							self::prepare("INSERT INTO `##gedcom_setting` (gedcom_id, setting_name, setting_value) VALUES (?,?, ?)")
								->execute(array($array['id'], $key, $value));
						} catch (PDOException $ex) {
							// Ignore duplicates
						}
					}
				}
			}
		}
	}
	
	// Migrate the data from pgv_users into pgv_user/pgv_user_setting/pgv_user_gedcom_setting
	try {
		self::exec("INSERT INTO `##user` (user_name, password) SELECT u_username, u_password FROM {$TBLPREFIX}users");
	} catch (PDOException $ex) {
		// This could only fail if;
		// a) we've already done it (upgrade)
		// b) it doesn't exist (new install)
	}
	
	try {
		self::exec(
			"INSERT INTO `##user_setting` (user_id, setting_name, setting_value)".
			"	SELECT user_id, 'firstname', u_firstname".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'lastname', u_lastname".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'canadmin', u_canadmin".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'email', u_email".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'verified', u_verified".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'verified_by_admin', u_verified_by_admin".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'language', u_language".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'pwrequested', u_pwrequested".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'reg_timestamp', u_reg_timestamp".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'reg_hashcode', u_reg_hashcode".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'theme', u_theme".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'loggedin', u_loggedin".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'sessiontime', u_sessiontime".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'contactmethod', u_contactmethod".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'visibleonline', u_visibleonline".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'editaccount', u_editaccount".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'defaulttab', u_defaulttab".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'comment', u_comment".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'comment_exp', u_comment_exp".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'sync_gedcom', u_sync_gedcom".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'relationship_privacy', u_relationship_privacy".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'max_relation_path', u_max_relation_path".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)".
			" UNION ALL".
			"	SELECT user_id, 'auto_accept', u_auto_accept".
			" FROM {$TBLPREFIX}users".
			" JOIN {$TBLPREFIX}user ON (user_name=u_username)"
		);
	} catch (PDOException $ex) {
		// This could only fail if;
		// a) we've already done it (upgrade)
		// b) it doesn't exist (new install)
	}
	
	try {
		$user_gedcom_settings=
			self::prepare(
				"SELECT user_id, u_gedcomid, u_rootid, u_canedit".
				" FROM {$TBLPREFIX}users".
				" JOIN {$TBLPREFIX}user ON (user_name=u_username)"
			)->fetchAll();
		foreach ($user_gedcom_settings as $setting) {
			@$array=unserialize($setting->u_gedcomid);
			if (is_array($array)) {
				foreach ($array as $gedcom=>$value) {
					$id=get_id_from_gedcom($gedcom);
					if ($id) {
						// Allow for old/invalid gedcom values in array
						set_user_gedcom_setting($setting->user_id, $id, 'gedcomid', $value);
					}
				}
			}
			@$array=unserialize($setting->u_rootid);
			if (is_array($array)) {
				foreach ($array as $gedcom=>$value) {
					$id=get_id_from_gedcom($gedcom);
					if ($id) {
						// Allow for old/invalid gedcom values in array
					 	set_user_gedcom_setting($setting->user_id, $id, 'rootid', $value);
					}
				}
			}
			@$array=unserialize($setting->u_canedit);
			if (is_array($array)) {
				foreach ($array as $gedcom=>$value) {
					$id=get_id_from_gedcom($gedcom);
					if ($id) {
						// Allow for old/invalid gedcom values in array
					 	set_user_gedcom_setting($setting->user_id, $id, 'canedit', $value);
					}
				}
			}
		}
	
		// TODO: Uncomment this lines before the next release
		//self::exec("DROP TABLE {$TBLPREFIX}users");
	
	} catch (PDOException $ex) {
		// This could only fail if;
		// a) we've already done it (upgrade)
		// b) it doesn't exist (new install)
	}
}

define('PGV_PHPGEDVIEW', true);
define('PGV_PRIV_PUBLIC', WT_PRIV_PUBLIC);
define('PGV_PRIV_USER', WT_PRIV_USER);
define('PGV_PRIV_NONE', WT_PRIV_NONE);
define('PGV_PRIV_HIDE', WT_PRIV_HIDE);
$PRIV_PUBLIC=WT_PRIV_PUBLIC;
$PRIV_USER=WT_PRIV_USER;
$PRIV_NONE=WT_PRIV_NONE;
$PRIV_HIDE=WT_PRIV_HIDE;
foreach (get_all_gedcoms() as $ged_id=>$gedcom) {
	$config=get_gedcom_setting($ged_id, 'config');
	$config=str_replace('${INDEX_DIRECTORY}', $INDEX_DIRECTORY.'/', $config);
	if (is_readable($config)) {
		require $config;
	}
	$privacy=get_gedcom_setting($ged_id, 'config');
	$privacy=str_replace('${INDEX_DIRECTORY}', $INDEX_DIRECTORY.'/', $privacy);
	if (is_readable($privacy)) {
		require $privacy;
	}

	@set_gedcom_setting($ged_id, 'ABBREVIATE_CHART_LABELS',      $ABBREVIATE_CHART_LABELS);
	@set_gedcom_setting($ged_id, 'ADVANCED_NAME_FACTS',          $ADVANCED_NAME_FACTS);
	@set_gedcom_setting($ged_id, 'ADVANCED_PLAC_FACTS',          $ADVANCED_PLAC_FACTS);
	@set_gedcom_setting($ged_id, 'ALLOW_EDIT_GEDCOM',            $ALLOW_EDIT_GEDCOM);
	@set_gedcom_setting($ged_id, 'ALLOW_THEME_DROPDOWN',         $ALLOW_THEME_DROPDOWN);
	@set_gedcom_setting($ged_id, 'AUTO_GENERATE_THUMBS',         $AUTO_GENERATE_THUMBS);
	@set_gedcom_setting($ged_id, 'CALENDAR_FORMAT',              $CALENDAR_FORMAT);
	@set_gedcom_setting($ged_id, 'CHART_BOX_TAGS',               $CHART_BOX_TAGS);
	@set_gedcom_setting($ged_id, 'CHECK_MARRIAGE_RELATIONS',     $CHECK_MARRIAGE_RELATIONS);
	@set_gedcom_setting($ged_id, 'COMMON_NAMES_ADD',             $COMMON_NAMES_ADD);
	@set_gedcom_setting($ged_id, 'COMMON_NAMES_REMOVE',          $COMMON_NAMES_REMOVE);
	@set_gedcom_setting($ged_id, 'COMMON_NAMES_THRESHOLD',       $COMMON_NAMES_THRESHOLD);
	@set_gedcom_setting($ged_id, 'CONTACT_USER_ID',              WT_USER_ID);
	@set_gedcom_setting($ged_id, 'DEFAULT_PEDIGREE_GENERATIONS', $DEFAULT_PEDIGREE_GENERATIONS);
	@set_gedcom_setting($ged_id, 'DISPLAY_JEWISH_GERESHAYIM',    $DISPLAY_JEWISH_GERESHAYIM);
	@set_gedcom_setting($ged_id, 'DISPLAY_JEWISH_THOUSANDS',     $DISPLAY_JEWISH_THOUSANDS);
	@set_gedcom_setting($ged_id, 'ENABLE_AUTOCOMPLETE',          $ENABLE_AUTOCOMPLETE);
	@set_gedcom_setting($ged_id, 'EXPAND_NOTES',                 $EXPAND_NOTES);
	@set_gedcom_setting($ged_id, 'EXPAND_RELATIVES_EVENTS',      $EXPAND_RELATIVES_EVENTS);
	@set_gedcom_setting($ged_id, 'EXPAND_SOURCES',               $EXPAND_SOURCES);
	@set_gedcom_setting($ged_id, 'FAM_FACTS_ADD',                $FAM_FACTS_ADD);
	@set_gedcom_setting($ged_id, 'FAM_FACTS_QUICK',              $FAM_FACTS_QUICK);
	@set_gedcom_setting($ged_id, 'FAM_FACTS_UNIQUE',             $FAM_FACTS_UNIQUE);
	@set_gedcom_setting($ged_id, 'FAM_ID_PREFIX',                $FAM_ID_PREFIX);
	@set_gedcom_setting($ged_id, 'FAVICON',                      $FAVICON);
	@set_gedcom_setting($ged_id, 'FULL_SOURCES',                 $FULL_SOURCES);
	@set_gedcom_setting($ged_id, 'GEDCOM_DEFAULT_TAB',           $GEDCOM_DEFAULT_TAB);
	@set_gedcom_setting($ged_id, 'GEDCOM_ID_PREFIX',             $GEDCOM_ID_PREFIX);
	@set_gedcom_setting($ged_id, 'GENERATE_UIDS',                $GENERATE_UIDS);
	@set_gedcom_setting($ged_id, 'HIDE_GEDCOM_ERRORS',           $HIDE_GEDCOM_ERRORS);
	@set_gedcom_setting($ged_id, 'HIDE_LIVE_PEOPLE',             $HIDE_LIVE_PEOPLE);
	@set_gedcom_setting($ged_id, 'HOME_SITE_TEXT',               $HOME_SITE_TEXT);
	@set_gedcom_setting($ged_id, 'HOME_SITE_URL',                $HOME_SITE_URL);
	@set_gedcom_setting($ged_id, 'INDI_FACTS_ADD',               $INDI_FACTS_ADD);
	@set_gedcom_setting($ged_id, 'INDI_FACTS_QUICK',             $INDI_FACTS_QUICK);
	@set_gedcom_setting($ged_id, 'INDI_FACTS_UNIQUE',            $INDI_FACTS_UNIQUE);
	@set_gedcom_setting($ged_id, 'LANGUAGE',                     WT_LOCALE);
	@set_gedcom_setting($ged_id, 'LINK_ICONS',                   $LINK_ICONS);
	@set_gedcom_setting($ged_id, 'MAX_ALIVE_AGE',                $MAX_ALIVE_AGE);
	@set_gedcom_setting($ged_id, 'MAX_DESCENDANCY_GENERATIONS',  $MAX_DESCENDANCY_GENERATIONS);
	@set_gedcom_setting($ged_id, 'MAX_PEDIGREE_GENERATIONS',     $MAX_PEDIGREE_GENERATIONS);
	@set_gedcom_setting($ged_id, 'MAX_RELATION_PATH_LENGTH',     $MAX_RELATION_PATH_LENGTH);
	@set_gedcom_setting($ged_id, 'MEDIA_DIRECTORY',              'media/');
	@set_gedcom_setting($ged_id, 'MEDIA_DIRECTORY_LEVELS',       $MEDIA_DIRECTORY_LEVELS);
	@set_gedcom_setting($ged_id, 'MEDIA_EXTERNAL',               $MEDIA_EXTERNAL);
	@set_gedcom_setting($ged_id, 'MEDIA_FIREWALL_ROOTDIR',       $MEDIA_FIREWALL_ROOTDIR);
	@set_gedcom_setting($ged_id, 'MEDIA_FIREWALL_THUMBS',        $MEDIA_FIREWALL_THUMBS);
	@set_gedcom_setting($ged_id, 'MEDIA_ID_PREFIX',              $MEDIA_ID_PREFIX);
	@set_gedcom_setting($ged_id, 'META_DESCRIPTION',             $META_DESCRIPTION);
	@set_gedcom_setting($ged_id, 'META_ROBOTS',                  $META_ROBOTS);
	@set_gedcom_setting($ged_id, 'META_TITLE',                   $META_TITLE);
	@set_gedcom_setting($ged_id, 'MULTI_MEDIA',                  $MULTI_MEDIA);
	@set_gedcom_setting($ged_id, 'NOTE_FACTS_ADD',               $NOTE_FACTS_ADD);
	@set_gedcom_setting($ged_id, 'NOTE_FACTS_QUICK',             $NOTE_FACTS_QUICK);
	@set_gedcom_setting($ged_id, 'NOTE_FACTS_UNIQUE',            $NOTE_FACTS_UNIQUE);
	@set_gedcom_setting($ged_id, 'NOTE_ID_PREFIX',               'N');
	@set_gedcom_setting($ged_id, 'NO_UPDATE_CHAN',               $NO_UPDATE_CHAN);
	@set_gedcom_setting($ged_id, 'PAGE_AFTER_LOGIN',             'mypage');
	@set_gedcom_setting($ged_id, 'PEDIGREE_FULL_DETAILS',        $PEDIGREE_FULL_DETAILS);
	@set_gedcom_setting($ged_id, 'PEDIGREE_LAYOUT',              $PEDIGREE_LAYOUT);
	@set_gedcom_setting($ged_id, 'PEDIGREE_ROOT_ID',             $PEDIGREE_ROOT_ID);
	@set_gedcom_setting($ged_id, 'PEDIGREE_SHOW_GENDER',         $PEDIGREE_SHOW_GENDER);
	@set_gedcom_setting($ged_id, 'POSTAL_CODE',                  $POSTAL_CODE);
	@set_gedcom_setting($ged_id, 'PREFER_LEVEL2_SOURCES',        $PREFER_LEVEL2_SOURCES);
	@set_gedcom_setting($ged_id, 'QUICK_REQUIRED_FACTS',         $QUICK_REQUIRED_FACTS);
	@set_gedcom_setting($ged_id, 'QUICK_REQUIRED_FAMFACTS',      $QUICK_REQUIRED_FAMFACTS);
	@set_gedcom_setting($ged_id, 'REPO_FACTS_ADD',               $REPO_FACTS_ADD);
	@set_gedcom_setting($ged_id, 'REPO_FACTS_QUICK',             $REPO_FACTS_QUICK);
	@set_gedcom_setting($ged_id, 'REPO_FACTS_UNIQUE',            $REPO_FACTS_UNIQUE);
	@set_gedcom_setting($ged_id, 'REPO_ID_PREFIX',               $REPO_ID_PREFIX);
	@set_gedcom_setting($ged_id, 'REQUIRE_AUTHENTICATION',       $REQUIRE_AUTHENTICATION);
	@set_gedcom_setting($ged_id, 'SAVE_WATERMARK_IMAGE',         $SAVE_WATERMARK_IMAGE);
	@set_gedcom_setting($ged_id, 'SAVE_WATERMARK_THUMB',         $SAVE_WATERMARK_THUMB);
	@set_gedcom_setting($ged_id, 'SEARCH_FACTS_DEFAULT',         $SEARCH_FACTS_DEFAULT);
	@set_gedcom_setting($ged_id, 'SHOW_AGE_DIFF',                $SHOW_AGE_DIFF);
	@set_gedcom_setting($ged_id, 'SHOW_CONTEXT_HELP',            $SHOW_CONTEXT_HELP);
	@set_gedcom_setting($ged_id, 'SHOW_COUNTER',                 $SHOW_COUNTER);
	@set_gedcom_setting($ged_id, 'SHOW_DEAD_PEOPLE',             $SHOW_DEAD_PEOPLE);
	@set_gedcom_setting($ged_id, 'SHOW_EMPTY_BOXES',             $SHOW_EMPTY_BOXES);
	@set_gedcom_setting($ged_id, 'SHOW_EST_LIST_DATES',          $SHOW_EST_LIST_DATES);
	@set_gedcom_setting($ged_id, 'SHOW_FACT_ICONS',              $SHOW_FACT_ICONS);
	@set_gedcom_setting($ged_id, 'SHOW_GEDCOM_RECORD',           $SHOW_GEDCOM_RECORD);
	@set_gedcom_setting($ged_id, 'SHOW_HIGHLIGHT_IMAGES',        $SHOW_HIGHLIGHT_IMAGES);
	@set_gedcom_setting($ged_id, 'SHOW_LDS_AT_GLANCE',           $SHOW_LDS_AT_GLANCE);
	@set_gedcom_setting($ged_id, 'SHOW_LEVEL2_NOTES',            $SHOW_LEVEL2_NOTES);
	@set_gedcom_setting($ged_id, 'SHOW_LIST_PLACES',             $SHOW_LIST_PLACES);
	@set_gedcom_setting($ged_id, 'SHOW_LIVING_NAMES',            $SHOW_LIVING_NAMES);
	@set_gedcom_setting($ged_id, 'SHOW_MARRIED_NAMES',           $SHOW_MARRIED_NAMES);
	@set_gedcom_setting($ged_id, 'SHOW_MEDIA_DOWNLOAD',          $SHOW_MEDIA_DOWNLOAD);
	@set_gedcom_setting($ged_id, 'SHOW_MEDIA_FILENAME',          $SHOW_MEDIA_FILENAME);
	@set_gedcom_setting($ged_id, 'SHOW_MULTISITE_SEARCH',        $SHOW_MULTISITE_SEARCH);
	@set_gedcom_setting($ged_id, 'SHOW_PARENTS_AGE',             $SHOW_PARENTS_AGE);
	@set_gedcom_setting($ged_id, 'SHOW_PEDIGREE_PLACES',         $SHOW_PEDIGREE_PLACES);
	@set_gedcom_setting($ged_id, 'SHOW_PRIVATE_RELATIONSHIPS',   $SHOW_PRIVATE_RELATIONSHIPS);
	@set_gedcom_setting($ged_id, 'SHOW_REGISTER_CAUTION',        $SHOW_REGISTER_CAUTION);
	@set_gedcom_setting($ged_id, 'SHOW_RELATIVES_EVENTS',        $SHOW_RELATIVES_EVENTS);
	@set_gedcom_setting($ged_id, 'SHOW_SPIDER_TAGLINE',          $SHOW_SPIDER_TAGLINE);
	@set_gedcom_setting($ged_id, 'SHOW_STATS',                   $SHOW_STATS);
	@set_gedcom_setting($ged_id, 'SOURCE_ID_PREFIX',             $SOURCE_ID_PREFIX);
	@set_gedcom_setting($ged_id, 'SOUR_FACTS_ADD',               $SOUR_FACTS_ADD);
	@set_gedcom_setting($ged_id, 'SOUR_FACTS_QUICK',             $SOUR_FACTS_QUICK);
	@set_gedcom_setting($ged_id, 'SOUR_FACTS_UNIQUE',            $SOUR_FACTS_UNIQUE);
	@set_gedcom_setting($ged_id, 'SPLIT_PLACES',                 $SPLIT_PLACES);
	@set_gedcom_setting($ged_id, 'SUBLIST_TRIGGER_F',            $SUBLIST_TRIGGER_F);
	@set_gedcom_setting($ged_id, 'SUBLIST_TRIGGER_I',            $SUBLIST_TRIGGER_I);
	@set_gedcom_setting($ged_id, 'SURNAME_LIST_STYLE',           $SURNAME_LIST_STYLE);
	@set_gedcom_setting($ged_id, 'SURNAME_TRADITION',            $SURNAME_TRADITION);
	@set_gedcom_setting($ged_id, 'THEME_DIR',                    'themes/webtrees/');
	@set_gedcom_setting($ged_id, 'THUMBNAIL_WIDTH',              $THUMBNAIL_WIDTH);
	@set_gedcom_setting($ged_id, 'UNDERLINE_NAME_QUOTES',        $UNDERLINE_NAME_QUOTES);
	@set_gedcom_setting($ged_id, 'USE_GEONAMES',                 $USE_GEONAMES);
	@set_gedcom_setting($ged_id, 'USE_MEDIA_FIREWALL',           $USE_MEDIA_FIREWALL);
	@set_gedcom_setting($ged_id, 'USE_MEDIA_VIEWER',             $USE_MEDIA_VIEWER);
	@set_gedcom_setting($ged_id, 'USE_RIN',                      $USE_RIN);
	@set_gedcom_setting($ged_id, 'USE_SILHOUETTE',               $USE_SILHOUETTE);
	@set_gedcom_setting($ged_id, 'USE_THUMBS_MAIN',              $USE_THUMBS_MAIN);
	@set_gedcom_setting($ged_id, 'WATERMARK_THUMB',              $WATERMARK_THUMB);
	@set_gedcom_setting($ged_id, 'WEBMASTER_USER_ID',            WT_USER_ID);
	@set_gedcom_setting($ged_id, 'WELCOME_TEXT_AUTH_MODE',       $WELCOME_TEXT_AUTH_MODE);
	@set_gedcom_setting($ged_id, 'WELCOME_TEXT_CUST_HEAD',       $WELCOME_TEXT_CUST_HEAD);
	@set_gedcom_setting($ged_id, 'WORD_WRAPPED_NOTES',           $WORD_WRAPPED_NOTES);
	@set_gedcom_setting($ged_id, 'ZOOM_BOXES',                   $ZOOM_BOXES);

	// TODO import whatever privacy settings as are compatible with the new system

	set_gedcom_setting($ged_id, 'config',   null);
	set_gedcom_setting($ged_id, 'privacy',  null);
	set_gedcom_setting($ged_id, 'path',     null);
	set_gedcom_setting($ged_id, 'pgv_ver',  null);
	set_gedcom_setting($ged_id, 'imported', 1);
}

////////////////////////////////////////////////////////////////////////////////

if ($PGV_SCHEMA_VERSION>=13) {
	echo '<p>pgv_hit_counter => wt_hit_counter ...</p>'; flush();
	WT_DB::prepare(
		"REPLACE INTO `##hit_counter` (gedcom_id, page_name, page_parameter, page_count)".
		" SELECT gedcom_id, page_name, page_parameter, page_count FROM {$DBNAME}.{$TBLPREFIX}hit_counter"
	)->execute();
} else {
	// Copied from PGV's db_schema_12_13
	$statement=PGV_DB::prepare("REPLACE INTO {$TBLPREFIX}hit_counter (gedcom_id, page_name, page_parameter, page_count) VALUES (?, ?, ?, ?)");

	foreach (get_all_gedcoms() as $ged_id=>$ged_name) {
		// Caution these files might be quite large...
		$file=$INDEX_DIRECTORY.$ged_name.'pgv_counters.txt';
		echo '<p>', $file, ' => wt_hit_counter ...</p>'; flush();
		if (file_exists($file)) {
			foreach (file($file) as $line) {
				if (preg_match('/(@('.PGV_REGEX_XREF.')@ )?(\d+)/', $line, $match)) {
					if ($match[2]) {
						$page_name='individual.php';
						$page_parameter=$match[2];
					} else {
						$page_name='index.php';
						$page_parameter='gedcom:'.$ged_id;
					}
					try {
						$statement->execute(array($ged_id, $page_name, $page_parameter, $match[3]));
					} catch (PDOException $ex) {
						// Primary key violation?  Ignore?
					}
				}
			}
		}
	}
}

////////////////////////////////////////////////////////////////////////////////

if ($PGV_SCHEMA_VERSION>=14) {
	echo '<p>pgv_ip_address => wt_ip_address ...</p>'; flush();
	WT_DB::prepare(
		"INSERT IGNORE INTO `##ip_address` (ip_address, category, comment)".
		" SELECT ip_address, category, comment FROM {$DBNAME}.{$TBLPREFIX}ip_address"
	)->execute();
} else {
	// Copied from PGV's db_schema_13_14
	$statement=PGV_DB::prepare("REPLACE INTO `##ip_address` (ip_address, category, comment) VALUES (?, ?, ?)");
	echo '<p>banned.php => wt_ip_address ...</p>'; flush();
	if (is_readable($INDEX_DIRECTORY.'/banned.php')) {
		@require $INDEX_DIRECTORY.'/banned.php';
		if (!empty($banned) && is_array($banned)) {
			foreach ($banned as $value) {
				try {
					if (is_array($value)) {
						// New format: array(ip, comment)
						$statement->execute(array($value[0], 'banned', $value[1]));
					} else {
						// Old format: string(ip)
						$statement->execute(array($value, 'banned', ''));
					}
				} catch (PDOException $ex) {
					echo $ex, '<br/>';
				}
			}
		}
	}
	echo '<p>search_engines.php => wt_ip_address ...</p>'; flush();
	if (is_readable($INDEX_DIRECTORY.'/search_engines.php')) {
		@require $INDEX_DIRECTORY.'/search_engines.php';
		if (!empty($search_engines) && is_array($search_engines)) {
			foreach ($search_engines as $value) {
				try {
					if (is_array($value)) {
						// New format: array(ip, comment)
						$statement->execute(array($value[0], 'search-engine', $value[1]));
					} else {
						// Old format: string(ip)
						$statement->execute(array($value, 'search-engine', ''));
					}
				} catch (PDOException $ex) {
					echo $ex, '<br/>';
				}
			}
		}
	}
}

////////////////////////////////////////////////////////////////////////////////

foreach (get_all_gedcoms() as $ged_id=>$gedcom) {
	WT_Module::setDefaultAccess($ged_id);
}

echo '<p>pgv_site_setting => wt_module_setting ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##module_setting` (module_name, setting_name, setting_value)".
	" SELECT 'googlemap', site_setting_name, site_setting_value FROM {$DBNAME}.{$TBLPREFIX}site_setting".
	" WHERE site_setting_name LIKE 'GM_%'"
)->execute();
WT_DB::prepare(
	"REPLACE INTO `##module_setting` (module_name, setting_name, setting_value)".
	" SELECT 'lightbox', site_setting_name, site_setting_value FROM {$DBNAME}.{$TBLPREFIX}site_setting".
	" WHERE site_setting_name LIKE 'LB_%'"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_favorites => wt_favorites ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##favorites` (fv_id, fv_username, fv_gid, fv_type, fv_file, fv_url, fv_title, fv_note)".
	" SELECT fv_id, fv_username, fv_gid, fv_type, fv_file, fv_url, fv_title, fv_note FROM {$DBNAME}.{$TBLPREFIX}favorites"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_news => wt_news ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##news` (n_id, n_username, n_date, n_title, n_text)".
	" SELECT n_id, n_username, n_date, n_title, n_text FROM {$DBNAME}.{$TBLPREFIX}news"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_dates => wt_dates ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##dates` (d_day, d_month, d_year, d_julianday1, d_julianday2, d_fact, d_gid, d_File, d_type)".
	" SELECT d_day, d_month, d_year, d_julianday1, d_julianday2, d_fact, d_gid, d_File, d_type FROM {$DBNAME}.{$TBLPREFIX}dates"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_families => wt_families ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##families` (f_id, f_file, f_husb, f_wife, f_gedcom, f_numchil)".
	" SELECT f_id, f_file, f_husb, f_wife, f_gedcom, f_numchil FROM {$DBNAME}.{$TBLPREFIX}families"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_individuals => wt_individuals ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##individuals` (i_id, i_file, i_rin, i_isdead, i_sex, i_gedcom)".
	" SELECT i_id, i_file, i_rin, i_isdead, i_sex, i_gedcom FROM {$DBNAME}.{$TBLPREFIX}individuals"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_link => wt_link ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##link` (l_file, l_from, l_type, l_to)".
	" SELECT l_file, l_from, l_type, l_to FROM {$DBNAME}.{$TBLPREFIX}link"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_media => wt_media ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##media` (m_id, m_media, m_ext, m_titl, m_file, m_gedfile, m_gedrec)".
	" SELECT m_id, m_media, m_ext, m_titl, m_file, m_gedfile, m_gedrec FROM {$DBNAME}.{$TBLPREFIX}media"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_media_mapping => wt_media_mapping ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##media_mapping` (mm_id, mm_media, mm_gid, mm_order, mm_gedfile, mm_gedrec)".
	" SELECT mm_id, mm_media, mm_gid, mm_order, mm_gedfile, mm_gedrec FROM {$DBNAME}.{$TBLPREFIX}media_mapping"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_name => wt_name ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##name` (n_file, n_id, n_num, n_type, n_sort, n_full, n_list, n_surname, n_surn, n_givn, n_soundex_givn_std, n_soundex_surn_std, n_soundex_givn_dm, n_soundex_surn_dm)".
	" SELECT n_file, n_id, n_num, n_type, n_sort, n_full, n_list, n_surname, n_surn, n_givn, n_soundex_givn_std, n_soundex_surn_std, n_soundex_givn_dm, n_soundex_surn_dm FROM {$DBNAME}.{$TBLPREFIX}name"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_other => wt_other ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##other` (o_id, o_file, o_type, o_gedcom)".
	" SELECT o_id, o_file, o_type, o_gedcom FROM {$DBNAME}.{$TBLPREFIX}other"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_placelinks => wt_placelinks ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##placelinks` (pl_p_id, pl_gid, pl_file)".
	" SELECT pl_p_id, pl_gid, pl_file FROM {$DBNAME}.{$TBLPREFIX}placelinks"
)->execute();

////////////////////////////////////////////////////////////////////////////////

try {
	echo '<p>pgv_placelocation => wt_placelocation ...</p>'; flush();
	WT_DB::prepare(
		"REPLACE INTO `##placelocation` (pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon)".
		" SELECT pl_id, pl_parent_id, pl_level, pl_place, pl_long, pl_lati, pl_zoom, pl_icon FROM {$DBNAME}.{$TBLPREFIX}placelocation"
	)->execute();
} catch (PDOexception $ex) {
	// This table will only exist if the gm module is installed in PGV
}

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_places => wt_places ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##places` (p_id, p_place, p_level, p_parent_id, p_file, p_std_soundex, p_dm_soundex)".
	" SELECT p_id, p_place, p_level, p_parent_id, p_file, p_std_soundex, p_dm_soundex FROM {$DBNAME}.{$TBLPREFIX}places"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_remotelinks => wt_remotelinks ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##remotelinks` (r_gid, r_linkid, r_file)".
	" SELECT r_gid, r_linkid, r_file FROM {$DBNAME}.{$TBLPREFIX}remotelinks"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_sources => wt_sources ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##sources` (s_id, s_file, s_name, s_gedcom, s_dbid)".
	" SELECT s_id, s_file, s_name, s_gedcom, s_dbid FROM {$DBNAME}.{$TBLPREFIX}sources"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>pgv_messages => wt_message ...</p>'; flush();
WT_DB::prepare(
	"REPLACE INTO `##message` (message_id, sender, ip_address, user_id, subject, body, created)".
	" SELECT m_id, m_from, '127.0.0.1', user_id, m_subject, m_body, m_created FROM {$DBNAME}.{$TBLPREFIX}messages JOIN {$DBNAME}.{$TBLPREFIX}user ON (m_to=user_name)"
)->execute();

////////////////////////////////////////////////////////////////////////////////

echo '<p>Done!</p>';