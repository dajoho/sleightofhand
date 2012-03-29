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
 * @param array $output REDAXO OUTPUT_FILTER Settings
 *
 * @return string Modified HTML Code
 */

function Sleightofhand_scripts($output)
{
    $output = Sleightofhand_Output_Filter::parse($output);

    /** @todo Optimize this? Backend elsewhere? */
    if (!Sleightofhand::env()->isBackend()) {
        $d = '';
    } else {
        $d = '../';
    }

    $buf = '
<link rel="stylesheet" type="text/css" href="' . $d
            . 'index.php?sleightofhand_css" />
<script type="text/javascript" src="' . $d
            . 'index.php?sleightofhand_js"></script>
<!--[if lt IE 7]><script type="text/javascript" src="' . $d
            . 'index.php?sleightofhand_js-ie"></script><![endif]-->
';
    $output = str_replace('</head>', $buf . '</head>', $output);
    return $output;
}
Sleightofhand::env()->extensionPoint('OUTPUT_FILTER', 'Sleightofhand_scripts');