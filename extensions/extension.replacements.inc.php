<?php
function a561_replacements($params) {
	global $REX;
	$output = $params['subject'];
	
	$reps = $REX['ADDON']['settings']['sleightofhand']['replacements'];
	
	if (count($reps)>0) {
		$doc = phpQuery::newDocument($output);		
		foreach ($reps as $rep) {
			if (count($rep)==2) {				
				$elems = pq($rep[0]);
				foreach($elems as $elem) {
					$text = pq($elem)->html();
					$text = strip_tags($text);
					$settings = $rep[1];
					$settings['text']=$text;
					$html = a561_sleightofhand($settings);
					pq($elem)->html($html);
				}
				
			}
		}
		$output = pq($doc)->markup();
		
		//remove empty tags
		$output = preg_replace('/<(p|span|strong|b|em|h1|h2|h3|h4|h5|h6)>(\s|\b)*<\/\1>/','',$output);
	}
	
	return $output;
}
rex_register_extension('OUTPUT_FILTER', 'a561_replacements'); 
?>