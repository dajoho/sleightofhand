<?php
/**
 * Sleightofhand
 *
 * PHP version 5
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  MIT License http://www.opensource.org/licenses/mit-license.html
 * @version  GIT: <git_id>
 * @link     http://bit.ly/sleightofhand-site
 */

/**
 * Loads necessary CSS/JS files into the correct
 * area </head> of the output HTML code
 *
 * @param array $params REDAXO OUTPUT_FILTER Settings
 *
 * @return string Modified HTML Code
 */

function A561_scripts($output)
{
    $output = A561_Output_Filter::parse($output);

    if (!A561::env()->isBackend()) {
        $d = '';
    } else {
        $d = '../';
    }

    $buf = '
<link rel="stylesheet" type="text/css" href="' . $d
            . 'index.php?a561_css" />
<script type="text/javascript" src="' . $d
            . 'index.php?a561_js"></script>
<!--[if lt IE 7]><script type="text/javascript" src="' . $d
            . 'index.php?a561_js-ie"></script><![endif]-->
';
    $output = str_replace('</head>', $buf . '</head>', $output);
    return $output;
}
A561::env()->extensionPoint('OUTPUT_FILTER', 'A561_scripts');