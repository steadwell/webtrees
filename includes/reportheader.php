<?php

/**
 * Report Header Parser
 *
 * used by the SAX parser to generate PDF reports from the XML report file.
 *
 * @package webtrees
 * @subpackage Reports
 * @version $Id$
 */

if (!defined('WT_WEBTREES')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('WT_REPORTHEADER_PHP', '');

/**
 * element handlers array
 *
 * An array of element handler functions
 * @global array $elementHandler
 */
$elementHandler = array();
$elementHandler["Report"]["start"]   ="ReportSHandler";
$elementHandler["var"]["start"]      ="varSHandler";
$elementHandler["Title"]["start"]    ="TitleSHandler";
$elementHandler["Title"]["end"]      ="TitleEHandler";
$elementHandler["Description"]["end"]="DescriptionEHandler";
$elementHandler["Input"]["start"]    ="InputSHandler";
$elementHandler["Input"]["end"]      ="InputEHandler";

$text = "";
$report_array = array();



/**
 * xml start element handler
 *
 * this function is called whenever a starting element is reached
 * @param resource $parser the resource handler for the xml parser
 * @param string $name the name of the xml element parsed
 * @param array $attrs an array of key value pairs for the attributes
 */
function startElement($parser, $name, $attrs) {
// @deprecated
// global $elementHandler, $processIfs, $processGedcoms, $processRepeats;
	global $elementHandler, $processIfs;

	if (($processIfs==0) || ($name=="if")) {
		if (isset($elementHandler[$name]["start"])) {
			call_user_func($elementHandler[$name]["start"], $attrs);
		}
	}
}

/**
 * xml end element handler
 *
 * this function is called whenever an ending element is reached
 * @param resource $parser the resource handler for the xml parser
 * @param string $name the name of the xml element parsed
 */
function endElement($parser, $name) {
	// @deprecated
	// global $elementHandler, $processIfs, $processGedcoms, $processRepeats;
	global $elementHandler, $processIfs;

	if (($processIfs==0) || ($name=="if")) {
		if (isset($elementHandler[$name]["end"])) {
			call_user_func($elementHandler[$name]["end"]);
		}
	}
}

/**
 * xml character data handler
 *
 * this function is called whenever raw character data is reached
 * just print it to the screen
 * @param resource $parser the resource handler for the xml parser
 * @param string $data the name of the xml element parsed
 */
function characterData($parser, $data) {
	global $text;

	$text .= $data;
}

function ReportSHandler($attrs) {
	global $report_array;

	$access = WT_PRIV_PUBLIC;
	if (isset($attrs["access"])) {
		if (isset($$attrs["access"])) {
			$access = $$attrs["access"];
		}
	}
	$report_array["access"] = $access;

	if (isset($attrs["icon"])) {
		$report_array["icon"] = $attrs["icon"];
	} else {
		$report_array["icon"] = "";
	}
}

function varSHandler($attrs) {
	global $text, $vars, $fact, $desc, $type, $generation;

	$var = $attrs["var"];
	if (!empty($var)) {
		$match = array();
		$tfact = $fact;
		if ($fact=="EVEN") {
			$tfact = $type;
		}
		$var = str_replace(array("@fact", "@desc"), array($tfact, $desc), $var);
		if (substr($var, 0, 15)=='i18n::translate' || substr($var, 0, 14)=='translate_fact') {
			eval("\$var=$var;");
		}
		$text .= $var;
	}
}

function TitleSHandler() {
	// @deprecated
	// global $report_array, $text;
	global $text;

	$text = "";
}

function TitleEHandler() {
	global $report_array, $text;

	$report_array["title"] = $text;
	$text = "";
}

function DescriptionEHandler() {
	global $report_array, $text;

	$report_array["description"] = $text;
	$text = "";
}

function InputSHandler($attrs) {
	global $input, $text;

	$text ="";
	$input = array();
	$input["name"] = "";
	$input["type"] = "";
	$input["lookup"] = "";
	$input["default"] = "";
	$input["value"] = "";
	$input["options"] = "";
	if (isset($attrs["name"])) {
		$input["name"] = $attrs["name"];
	}
	if (isset($attrs["type"])) {
		$input["type"] = $attrs["type"];
	}
	if (isset($attrs["lookup"])) {
		$input["lookup"] = $attrs["lookup"];
	}
	if (isset($attrs["default"])) {
		if ($attrs["default"]=="NOW") {
			$input["default"] = date("d M Y");
		} else {
			$match = array();
			if (preg_match("/NOW\s*([+\-])\s*(\d+)/", $attrs['default'], $match)>0) {
				$plus = 1;
				if ($match[1]=="-") {
					$plus = -1;
				}
				$input["default"] = date("d M Y", time()+$plus*60*60*24*$match[2]);
			} else {
				$input["default"] = $attrs["default"];
			}
		}
	}
	if (isset($attrs["options"])) {
		$input["options"] = $attrs["options"];
	}
}

function InputEHandler() {
	global $report_array, $text, $input;

	$input["value"] = $text;
	if (!isset($report_array["inputs"])) {
		$report_array["inputs"] = array();
	}
	$report_array["inputs"][] = $input;
	$text = "";
}
