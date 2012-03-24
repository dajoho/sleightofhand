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

$install = true;

if (!defined('IS_SALLY')) {
    $folder = 'files/soh/';
    if (!file_exists($REX['HTDOCS_PATH'] . $folder)) {
        if (!mkdir($REX['HTDOCS_PATH'] . $folder)) {
            $REX['ADDON']['installmsg']['sleightofhand'] = 'Please check
                    permissions on: ' . $folder;
            $install = false;
        }
    }

} else {
    $service = sly_Service_Factory::getService('AddOn');
    $pubDir = $service->publicFolder('sleightofhand');
    //$state   = rex_is_writable($pubDir);
    //if ($state !== true) {
    //  throw new Exception($state);
    //}
}

$REX['ADDON']['install']['sleightofhand'] = 1;