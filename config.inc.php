<?php
/**
 * Sleightofhand
 *
 * PHP version 5
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  GNU http://www.gnu.org/licenses/gpl-2.0.html
 * @version  GIT: <git_id>
 * @link     http://bit.ly/sleightofhand-site
 */

$mypage = 'sleightofhand';

if (class_exists('rex_extension')) {
    $dir = $this->getBasePath();
} else {
    $REX['ADDON']['author'][$mypage] = "Dave Holloway";
    $REX['ADDON']['settings'][$mypage]['replacements'] = array();
    $REX['ADDON']['settings'][$mypage]['imagemagic'] = '';
    $dir = $REX['INCLUDE_PATH'] . '/addons/sleightofhand/';
}

require_once $dir.'bootstrap.inc.php';