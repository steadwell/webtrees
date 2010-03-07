<?php
/**
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2008 Greg Roach
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
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('PGV_FUNCTIONS_EL_PHP', '');

////////////////////////////////////////////////////////////////////////////////
// Localise a date.
////////////////////////////////////////////////////////////////////////////////
function date_localisation_el(&$q1, &$d1, &$q2, &$d2, &$q3) {
	global $pgv_lang;
	static $NOMINATIVE_MONTHS=null;
	static $GENITIVE_MONTHS=null;

	if (is_null($NOMINATIVE_MONTHS)) {
		$NOMINATIVE_MONTHS=array(i18n::translate('January'), i18n::translate('February'), i18n::translate('March'), i18n::translate('April'), i18n::translate('May'), i18n::translate('June'), i18n::translate('July'), i18n::translate('August'), i18n::translate('September'), i18n::translate('October'), i18n::translate('November'), i18n::translate('December'));
		$GENITIVE_MONTHS=array('Ιανουαρίου', 'Φεβρουαρίου', 'Μαρτίου', 'Απριλίου', 'Μαΐου', 'Ιουνίου', 'Ιουλίου', 'Αυγούστου', 'Σεπτεμβρίου', 'Οκτωβρίου', 'Νοεμβρίου', 'Δεκεμβρίου');
	}

	// Months with a day number are genitive, regardless of qualifier
	for ($i=0; $i<12; ++$i) {
		$d1=preg_replace("/(\d+ ){$NOMINATIVE_MONTHS[$i]}/", "$1{$GENITIVE_MONTHS[$i]}", $d1);
		$d2=preg_replace("/(\d+ ){$NOMINATIVE_MONTHS[$i]}/", "$1{$GENITIVE_MONTHS[$i]}", $d2);
	}

	// The qualifiers are simple translations
	if (isset($pgv_lang[$q1]))
		$q1=$pgv_lang[$q1];
	if (isset($pgv_lang[$q2]))
		$q2=$pgv_lang[$q2];
}
?>
