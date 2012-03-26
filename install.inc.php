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
 * @todo PHP4 Check
 */

/**
 * Install Addon
 * @todo Sally detection
 */
if (class_exists('rex_extension')) {
    $this->setProperty('install', true);
} else if (defined('SLY_SALLYFOLDER')) {
    $service = sly_Service_Factory::getService('AddOn');
    $pubDir = $service->publicFolder('sleightofhand');
} else if (isset($REX)) {
    $REX['ADDON']['install']['sleightofhand'] = 1;
}
