<?php
function a561_sleightofhand($settings = array(),$fn=false) {

	$obj = new a561_sleightofhand($settings);
		
	if ($obj->VALID) {
		if (!$fn) {
			return $obj->getCode();
		} else {
			return $obj->getImageLink();
		}
	} else {
		return '';
	}
}

function a561_addReplacement($selector='',$settings=array()) {
	global $REX;
	if (version_compare(PHP_VERSION, '5.0.0', '>')) {
		$REX['ADDON']['settings']['sleightofhand']['replacements'][] = array($selector,$settings);
	} else {
		//fail silently
	}
}
?>