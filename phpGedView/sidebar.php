<?php
if (!defined('WT_SCRIPT_NAME')) define('WT_SCRIPT_NAME', 'sidebar.php');
require_once('includes/session.php');
require_once(WT_ROOT.'includes/classes/class_module.php');

$sb_action = safe_GET('sb_action', WT_REGEX_ALPHANUM, 'none');
//-- handle ajax calls
if ($sb_action!='none') {
	$sidebarmods = WTModule::getActiveList('S', WT_USER_ACCESS_LEVEL);
	uasort($sidebarmods, "WTModule::compare_sidebar_order");
	class tempController {
		var $pid;
		var $famid;
	}
	
	$controller = new tempController();

	$pid = safe_GET_xref('pid', '');
	if (empty($pid)) $pid = safe_POST_xref('pid', '');
	if (!empty($pid)) {
		$controller->pid = $pid;
	}
	$pid = safe_GET_xref('rootid',  '');
	if (empty($pid)) $pid = safe_POST_xref('rootid', '');
	if (!empty($pid)) {
		$controller->pid = $pid;
	}
	$famid = safe_GET('famid', WT_REGEX_XREF, '');
	if (empty($famid)) $famid = safe_POST('famid', WT_REGEX_XREF, '');
	if (!empty($famid)) {
		$controller->famid = $famid;
	}
	$sid = safe_GET('sid', WT_REGEX_XREF, '');
	if (empty($sid)) $sid = safe_POST('sid', WT_REGEX_XREF, '');
	if (!empty($sid)) {
		$controller->sid = $sid;
	}
	
	if ($sb_action=='loadMods') {
		$counter = 0;
		foreach($sidebarmods as $mod) {
			if ($mod->hasSidebar()) {
				$sb = $mod->getSidebar();
				if (isset($controller)) $sb->setController($controller);
				if ($sb->hasContent()) {
					?><h3 title="<?php echo $mod->getName()?>"><a href="#"><?php echo $sb->getTitle()?></a></h3>
					<div id="sb_content_<?php echo $mod->getName()?>">
					<?php if ($counter==0) echo $sb->getContent();
					else {?><img src="<?php echo $WT_IMAGE_DIR ?>/loading.gif" /><?php }?>
					</div>
					<?php 
					$counter++;
				}
			}
		}
		exit;
	}
	if ($sb_action=='loadmod') {
		$modName = safe_GET('mod', WT_REGEX_URL, '');
		if (isset($sidebarmods[$modName])) {
			$mod = $sidebarmods[$modName];
			if ($mod->hasSidebar()) {
				$sb = $mod->getSidebar();
				if (isset($controller)) $sb->setController($controller);
				echo $sb->getContent();
			}
		}
		exit;
	}
	if (isset($sidebarmods[$sb_action])) {
		$mod = $sidebarmods[$sb_action];
		if ($mod->hasSidebar()) {
			$sb = $mod->getSidebar();
			echo $sb->getAjaxContent();
		}
	}
	exit;
}

global $controller;
$pid='';
$famid='';
if (isset($controller)) {
	if (isset($controller->pid)) $pid = $controller->pid;
	if (isset($controller->rootid)) $pid = $controller->rootid;
	if (isset($controller->famid)) $famid = $controller->famid;
	if (isset($controller->sid)) $pid = $controller->sid;
} else {
	$pid = safe_GET_xref('pid', '');
	if (empty($pid)) $pid = safe_POST_xref('pid', '');
	if (empty($pid)) $pid = safe_GET_xref('rootid',  '');
	if (empty($pid)) $pid = safe_POST_xref('rootid', '');
	if (empty($pid)) $pid = safe_POST_xref('sid', '');
	if (empty($pid)) $pid = safe_GET_xref('sid', '');
	$famid = safe_GET('famid', WT_REGEX_XREF, '');
	if (empty($famid)) $famid = safe_POST('famid', WT_REGEX_XREF, '');
}
?>
<style type="text/css">
<!-- 
<?php 
//-- standard theme styles
if ($TEXT_DIRECTION=='ltr') { ?>

<?php } 
//-- RTL styles
else {  ?>
/*
#sidebar {
	position: absolute;
	left: 1px;
	width: 0px;
	height: 450px;
	z-index: 50;
	margin-top: 10px;
	margin-left: 12px;
	background-color: #dddddd;
}
#sidebar_controls {
	position: absolute;
	float: right;
	right: -22px;
	margin-top: 0px;
	margin-right: 5px;
	height: 29px;
	width: 16px;
	z-index: 10;
}
#sidebar_open img {
	padding-top: 6px;
	padding-bottom: 7px;
	margin-right: 0px;
	height: 15px;
	background-color: #ffffff;
}
*/
<?php } ?>


// -->
</style>
<script type="text/javascript" src="js/jquery/jquery.scrollfollow.js"></script> 
<script type="text/javascript">
<!--
jQuery.noConflict(); // @see http://docs.jquery.com/Using_jQuery_with_Other_Libraries/
var loadedMods = new Array();
function closeCallback() {
	jQuery('#sidebarAccordion').hide();
	jQuery('#sidebar_pin').hide();
}
function openCallback() {
	jQuery('#sidebarAccordion').accordion({
		fillSpace: true, 
		changestart: function(event, ui) {
			loadedMods[ui.oldHeader.attr('title')] = true;
			var active = ui.newHeader.attr('title');
			if (!loadedMods[active]) {
				jQuery('#sb_content_'+active).load('sidebar.php?sb_action=loadmod&mod='+active+'&pid=<?php echo $pid?>&famid=<?php echo $famid?>');
			}
		}
	});
}
jQuery(document).ready(function() {

//	jQuery('#sidebar').scrollFollow();

	var modsLoaded = false;
	jQuery('#sidebar_open').toggle(function() {
		jQuery('#sidebar_open img').attr('src', '<?php echo $WT_IMAGE_DIR."/".$WT_IMAGES['slide_close']['other'];?>');
		jQuery('#sidebar').animate({
			right: "0px",
			width: "310px"
		}, 500);
		if (!modsLoaded) {
			jQuery('#sidebarAccordion').load('sidebar.php', 'sb_action=loadMods&pid=<?php echo $pid?>&famid=<?php echo $famid?>', openCallback);
			modsLoaded=true;
		}
		else jQuery("#sidebarAccordion").accordion("resize");
		jQuery('#sidebarAccordion').show();
	}, function() {
		jQuery('#sidebar_open img').attr('src', '<?php echo $WT_IMAGE_DIR."/".$WT_IMAGES['slide_open']['other'];?>');
		jQuery('#sidebar').css('left', '');
		jQuery('#sidebar').animate({
			right: "0px",
			width: "0px"
		}, 500, 'linear', closeCallback);
	});
});
//-->
</script>
<div id="sidebar">
	<div id="sidebar_controls" class="ui-accordion-header ui-helper-reset ui-state-active ui-corner-top ui-state-focus">
		<a id="sidebar_open" href="#open"><img src="<?php echo $WT_IMAGE_DIR."/".$WT_IMAGES['slide_open']['other'];?>" border="0" alt=""/></a>
		<!--<a id="sidebar_pin" href="#pin"><img src="<?php echo $WT_IMAGE_DIR."/".$WT_IMAGES['pin-out']['other'];?>" border="0" alt=""/></a>-->
	</div>
	<div id="sidebarAccordion"></div>
	<span class="ui-icon ui-icon-grip-dotted-horizontal" style="margin:2px auto;"></span>
</div>
<div id="debug">
</div>
