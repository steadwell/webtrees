<?php
// Individual Page
//
// Display all of the information about an individual
//
// webtrees: Web based Family History software
// Copyright (C) 2011 webtrees development team.
//
// Derived from PhpGedView
// Copyright (C) 2002 to 2010  PGV Development Team.  All rights reserved.
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

define('WT_SCRIPT_NAME', 'individual.php');
require './includes/session.php';

$showFull = ($PEDIGREE_FULL_DETAILS) ? 1 : 0;

// -- array of GEDCOM elements that will be found but should not be displayed
$nonfacts = array("FAMS", "FAMC", "MAY", "BLOB", "CHIL", "HUSB", "WIFE", "RFN", "_WT_OBJE_SORT", "");

$nonfamfacts = array(/*"NCHI",*/ "UID", "");

$controller=new WT_Controller_Individual();
$controller->init();

// tell tabs that use jquery that it is already loaded
define('WT_JQUERY_LOADED', 1);

// We have finished writing session data, so release the lock
Zend_Session::writeClose();

print_header($controller->getPageTitle());

if (!$controller->indi) {
	echo "<b>", WT_I18N::translate('Unable to find record with ID'), "</b><br /><br />";
	print_footer();
	exit;
}
else if (!$controller->indi->canDisplayName()) {
	print_privacy_error();
	print_footer();
	exit;
}
$linkToID=$controller->pid; // -- Tell addmedia.php what to link to


?>
<script type="text/javascript" src="js/jquery/jquery/jquery.cookie.js"></script>
<?php echo WT_JS_START;?>
// javascript function to open a window with the raw gedcom in it
function show_gedcom_record(shownew) {
	fromfile="";
	if (shownew=="yes") fromfile='&fromfile=1';
	var recwin = window.open("gedrecord.php?pid=<?php echo $controller->pid; ?>"+fromfile, "_blank", "top=50,left=50,width=600,height=400,scrollbars=1,scrollable=1,resizable=1");
}
<?php if (WT_USER_CAN_EDIT) { ?>
function showchanges() {
	window.location = '<?php echo $controller->indi->getRawUrl(); ?>&show_changes=yes';
}
<?php } ?>

var tabCache = new Array();
var pinned = false;

jQuery(document).ready(function() {
	// TODO: change images directory when the common images will be deleted.
	jQuery('#tabs').tabs({ spinner: '<img src=\"images/loading.gif\" height=\"18\" border=\"0\" alt=\"\" />' });
	jQuery("#tabs").tabs({ cache: true });
	var $tabs = jQuery('#tabs');
	jQuery('#tabs').bind('tabsshow', function(event, ui) {
		var selectedTab = ui.tab.name;
		tabCache[selectedTab] = true;

	<?php
	foreach ($controller->tabs as $tab) {
		echo $tab->getJSCallback()."\n";
	}
	?>
	});
});

jQuery(document).ready(function(){

	//function to reset page to top
	jQuery('a.goToTop').click(function() {
		jQuery('html').animate({scrollTop : 0},'slow');
	});
		
	// Variables
	var objMain = jQuery('#main');

	// Show sidebar
	function showSidebar(){
		objMain.addClass('use-sidebar');
		jQuery.cookie('sidebar-pref', 'use-sidebar', { expires: 30 });
	}

	// Hide sidebar
	function hideSidebar(){
		objMain.removeClass('use-sidebar');
		jQuery.cookie('sidebar-pref', null, { expires: 30 });
	}

	// Sidebar separator
	var objSeparator = jQuery('#separator');

	objSeparator.click(function(e){
		e.preventDefault();
		if ( objMain.hasClass('use-sidebar') ){
			hideSidebar();
		}
		else {
			showSidebar();
		}
	}).css('height', objSeparator.parent().outerHeight() + 'px');

	// Load preference
	if ( jQuery.cookie('sidebar-pref') == null ){
		objMain.removeClass('use-sidebar');
	}
});

<?php
echo WT_JS_END;
if ((empty($SEARCH_SPIDER))&&($controller->accept_success)) echo "<b>", WT_I18N::translate('Changes successfully accepted into database'), "</b><br />";
if ($controller->indi->isMarkedDeleted()) echo "<span class=\"error\">".WT_I18N::translate('This record has been marked for deletion upon admin approval.')."</span>";
if (strlen($controller->indi->getAddName()) > 0) echo "<span class=\"name_head\">", PrintReady($controller->indi->getAddName()), "</span><br />";

echo '<div id="main" class="use-sidebar sidebar-at-right">'; //overall page container

echo '<div id="indi_header" style="width:100%;">';
	require './indi_header.php';
echo '</div>';

echo '<div id="hitcounter">';
	if ($SHOW_COUNTER && (empty($SEARCH_SPIDER))) {
		//print indi counter only if displaying a non-private person
		require WT_ROOT.'includes/hitcount.php';
		echo WT_I18N::translate('Hit Count:'), " ", $hitCount;
	}
echo '</div>'; // close #hitcounter

echo '<div id="sidebar2">'; // sidebar code
	if (!$controller->indi->canDisplayDetails()) {
		echo "<table><tr><td class=\"facts_value\" >";
		print_privacy_error();
		echo "</td></tr></table>";
	} else {
		require './sidebar.php';
	}
echo
	'</div>',  // close #sidebar2
	'<a href="#" id="separator"></a>'; //clickable element to open/close sidebar


// ===================================== main content tabs
foreach ($controller->tabs as $tab) {
	echo $tab->getPreLoadContent();
}
$showFull = ($PEDIGREE_FULL_DETAILS) ? 1 : 0;
	echo '<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">';
	echo '<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">';
	foreach ($controller->tabs as $tab) {
		if ($tab->hasTabContent()) {
			if ($tab->getName()==$controller->default_tab) {
				// Default tab loads immediately
				echo '<li class="ui-state-default ui-corner-top ui-tabs-selected"><a class="goToTop" title="', $tab->getName(), '" href="#', $tab->getName(), '">';
			} elseif ($tab->canLoadAjax()) {
				// AJAX tabs load later
				echo '<li class="ui-state-default ui-corner-top"><a class="goToTop" title="', $tab->getName(), '" href="',$controller->indi->getHtmlUrl(),'&amp;action=ajax&amp;module=', $tab->getName(), '">';
			} else {
				// Non-AJAX tabs load immediately (search engines don't load ajax)
				echo '<li class="ui-state-default ui-corner-top"><a class="goToTop" title="', $tab->getName(), '" href="#', $tab->getName(), '">';
			}
			echo '<span title="', $tab->getTitle(), '">', $tab->getTitle(), '</span></a></li>';
		}
	}
	echo '</ul>';
	foreach ($controller->tabs as $tab) {
		if ($tab->hasTabContent()) {
			if ($tab->getName()==$controller->default_tab || !$tab->canLoadAjax()) {
				echo '<div id="', $tab->getName(), '" class="ui-tabs-panel ui-widget-content ui-corner-bottom">';
				echo $tab->getTabContent();
				echo '</div>';
			}
		}
	}
	echo '</div>'; // close #tabs 

echo '</div>'; // close #main
echo WT_JS_START;
echo 'var catch_and_ignore; function paste_id(value) {catch_and_ignore = value;}';
echo 'if (typeof toggleByClassName == "undefined") {';
echo 'alert("webtrees.js: A javascript function is missing.  Please clear your Web browser cache");';
echo '}';
echo WT_JS_END;

print_footer();
