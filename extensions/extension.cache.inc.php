<?php
function a561_clearcache($params) {
	global $REX;

	foreach (glob($REX['HTDOCS_PATH'].'files/soh/*.png') as $file) {
		@unlink($file);
	}

	return $params['subject'];
}
rex_register_extension('ALL_GENERATED', 'a561_clearcache'); 
?>