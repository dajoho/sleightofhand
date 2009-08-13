<?php
function a561_scripts($params) {
	global $REX;
	$output = $params['subject'];
	
	if (!$REX['REDAXO']) {
		$d = '';
	} else {
		$d = '../';
	}
	
	$buf = '
<link rel="stylesheet" type="text/css" href="'.$d.'index.php?a561_css" />
<script type="text/javascript" src="'.$d.'index.php?a561_js"></script>
<!--[if lt IE 7]><script type="text/javascript" src="'.$d.'index.php?a561_js-ie"></script><![endif]-->
';		
	$output = str_replace('</head>',$buf.'</head>',$output);
	return $output;
}
rex_register_extension('OUTPUT_FILTER', 'a561_scripts'); 
?>