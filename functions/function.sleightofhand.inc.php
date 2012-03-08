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

function a561_replace($selector='',$output='',$settings=array()) {
    if (version_compare(PHP_VERSION, '5.0.0', '>')) {
        $doc = phpQuery::newDocument($output);
        $reps = array();
        $reps[0] = array($selector,$settings);
        foreach ($reps as $rep) {
            if (count($rep)==2) {
                $elems = pq($rep[0]);
                foreach($elems as $elem) {
                    $pq = pq($elem);
                    $text = $pq->html();
                    $text = strip_tags($text);
                    $settings = $rep[1];
                    $settings['text']=$text;
                    $html = a561_sleightofhand($settings);
                    $pq->html($html);
                }
                
            }
        }
        $pq = pq($doc);
        $output = $pq->markup();
        
        //remove empty tags
        $output = preg_replace('/<(p|span|strong|b|em|h1|h2|h3|h4|h5|h6)>(\s|\b)*<\/\1>/','',$output);
        $output = str_replace("\n",'',$output);
        $output = str_replace("\r",'',$output); 
        $output = str_replace("\t",'',$output);
    }
    return $output;
}
?>