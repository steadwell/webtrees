<?php
// British English definitions file for PhpGedView.
// Based on differences from US English 11 Jan 2010.
//
// Copyright (C) 2010 Greg Roach.
//
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//
// $Id$

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$pgv_lang["gedcheck_sync"]="Edits made to the database are not synchronised to the file #GLOBALS[ged]#.  The file contents may be out-of-date.  You can synchronise it with the database now by performing an <b><a \"#GLOBALS[ged_link]#\">export</a></b>.";
$pgv_lang["unsync_warning"]="This GEDCOM file is <em>not</em> synchronised with the database.  It may not contain the latest version of your data.  To re-import from the database rather than the file, you should download and re-upload.";
