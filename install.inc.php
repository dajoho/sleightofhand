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

/**
 * @todo PHP4 Check
 * @todo Compare with old install.inc.php
 */

/**
 * Install Addon
 * @todo Sally detection
 */
if (class_exists('rex_extension')) {
    $this->setProperty('install', true);
} else if (isset($REX)) {
    $REX['ADDON']['install']['sleightofhand'] = 1;
}
