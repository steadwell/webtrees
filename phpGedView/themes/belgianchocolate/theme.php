<?php
/**
 * Belgianchocolate theme
 *
 * PhpGedView: Genealogy Viewer
 * Copyright (C) 2010  PGV Development Team.  All rights reserved.
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
 * @subpackage Themes/Colors
 * @version $Id: theme.php 7170 2010-03-05 21:35:28Z rob $
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$theme_name       = "Belgian Chocolate";
$SHARED_THEME_DIR = "themes/colors/"; 
define('SHARED_THEME_DIR', "themes/colors/");
$stylesheet       = $SHARED_THEME_DIR . "css/belgianchocolate.css"; 
$print_stylesheet = $SHARED_THEME_DIR . "css/belgianchocolate.css";
include('themes/colors/theme.inc');

