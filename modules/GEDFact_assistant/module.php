<?php
/**
 * Classes and libraries for module system
 *
 * webtrees: Web based Family History software
 * Copyright (C) 2010 webtrees development team.
 *
 * Derived from PhpGedView
 * Copyright (C) 2009 John Finlay
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
 * @subpackage Modules
 * @version $Id$
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

require_once WT_ROOT.'includes/classes/class_module.php';

class GEDFact_assistant_WT_Module extends WT_Module {
	// Extend WT_Module
	public function getTitle() {
		return i18n::translate('Census assistant');
	}

	// Extend WT_Module
	public function getDescription() {
		return i18n::translate('The census assistant provides a simple way to enter census transcripts and link them to each person listed in the census.');
	}

	// Extend WT_Module
	public function modAction($mod_action) {
		switch($mod_action) {
		case '_CENS/census_3_find':
			// TODO: this file should be a method in this class
			require WT_ROOT.'modules/'.$this->getName().'/'.$mod_action.'.php';
			break;
		}
	}
}
