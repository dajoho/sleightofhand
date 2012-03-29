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

$dir = null;
if (class_exists('rex_extension')) {
    $dir = $this->getBasePath();
} else if (defined('SLY_SALLYFOLDER')) {
    $dir = SLY_SALLYFOLDER . '/addons/sleightofhand/';
} else if (isset($REX)) {
    $REX['ADDON']['author'][$mypage] = "Dave Holloway";
    $dir = $REX['INCLUDE_PATH'] . '/addons/sleightofhand/';
}
if ($dir !== null) {
    include_once $dir.'bootstrap.inc.php';
}