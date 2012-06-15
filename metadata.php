<?php

/**
 * Module information
 */
 
$aModule = array(
    'id'           => 'sleightofhand',
    'title'        => 'SleightOfHand - Universal PNG-Fix & Font-Replacement',
    'description'  => 'Creates high quality, 24-bit alpha PNG images from any specified truetype font',
    'thumbnail'    => '',
    'version'      => '1.0',
    'author'       => 'Dave Holloway - GN2 netwerk',
    'extend'       => array(
        'oxoutput' => 'sleightofhand/sleightofhand_oxoutput'
    )
);