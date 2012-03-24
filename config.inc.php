<?php
/**
 * Sleightofhand
 *
 * PHP version 5
 *
 * @package Sleightofhand
 * @author  Dave Holloway <dh@dajoho.de>
 * @license GNU http://www.gnu.org/licenses/gpl-2.0.html
 * @version GIT: <git_id>
 * @link    http://bit.ly/sleightofhand-site
 */

$mypage = 'sleightofhand';

$REX['ADDON']['rxid'][$mypage] = '561';
$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['version'][$mypage] = "1.0";
$REX['ADDON']['author'][$mypage] = "Dave Holloway";
$REX['ADDON']['settings'][$mypage]['replacements'] = array();
$REX['ADDON']['settings'][$mypage]['imagemagic'] = '';

// classes, functions
require_once $REX['INCLUDE_PATH']
        . '/addons/sleightofhand/classes/class.sleightofhand.inc.php';
require_once $REX['INCLUDE_PATH']
        . '/addons/sleightofhand/classes/class.magic.inc.php';
require_once $REX['INCLUDE_PATH']
        . '/addons/sleightofhand/functions/function.sleightofhand.inc.php';

// extension points
require_once $REX['INCLUDE_PATH']
        . '/addons/sleightofhand/extensions/extension.scripts.inc.php';
require_once $REX['INCLUDE_PATH']
        . '/addons/sleightofhand/extensions/extension.cache.inc.php';

// include phpquery + replacement EP, if using php5
if (version_compare(PHP_VERSION, '5.0.0', '>')) {
    if (!class_exists('phpQuery')) {
        include_once $REX['INCLUDE_PATH']
        . '/addons/sleightofhand/classes/class.phpquery.inc.php';
        include_once $REX['INCLUDE_PATH']
        . '/addons/sleightofhand/extensions/extension.replacements.inc.php';
    }
}

// retrieve an image if it is requested
$soh = rex_request('a561_soh', 'string');
if ($soh != "") {
    $soh = str_replace('/', '', $soh);
    $soh = str_replace('.', '', $soh);
    $soh = str_replace("\\", '', $soh);

    $cachefile = $REX['INCLUDE_PATH'] . '/generated/files/soh-' . $soh . '.png';
    if (file_exists($cachefile)) {
        header("Content-type: image/png");
        $fp = fopen($cachefile, 'rb');
        header("Content-Length: " . filesize($cachefile));
        fpassthru($fp);
    }
    exit();
}
// css, javascripts etc.
if (isset($_REQUEST['a561_css'])) {
    $cssfile = $REX['INCLUDE_PATH'] . '/addons/sleightofhand/data/soh.css';
    rex_send_file($cssfile, 'text/css');
    exit();
}
if (isset($_REQUEST['a561_js'])) {
    $jsfile = $REX['INCLUDE_PATH'] . '/addons/sleightofhand/data/soh.js';
    rex_send_file($jsfile, 'text/javascript');
    exit();
}
if (isset($_REQUEST['a561_js-ie'])) {
    $jsfile = $REX['INCLUDE_PATH'] . '/addons/sleightofhand/data/soh-ie.js';
    rex_send_file($jsfile, 'text/javascript');
    exit();
}