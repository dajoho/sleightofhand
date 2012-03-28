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

$dir = $_SERVER['DOCUMENT_ROOT'] . '/'
        . ltrim(dirname($_SERVER['SCRIPT_NAME']), '/')
        . '/modules/sleightofhand/';

require_once $dir . '/bootstrap.inc.php';

/**
 * Sleightofhand_Oxoutput - Main SOH Graphic Generation Class
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  MIT License http://www.opensource.org/licenses/mit-license.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */
class Sleightofhand_Oxoutput extends Sleightofhand_Oxoutput_Parent
{

}
