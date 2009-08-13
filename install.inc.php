<?php

$install = true;


$folder = 'files/soh/';
if (!file_exists($REX['HTDOCS_PATH'].$folder)) {
	if (!mkdir($REX['HTDOCS_PATH'].$folder)) {
		$REX['ADDON']['installmsg']['sleightofhand'] = 'Please check permissions on: '.$folder;
		$install = false;
	}
}



$REX['ADDON']['install']['sleightofhand'] = 1;


?>