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
 * A561_Replacements - Container class to store CSS replacement data
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  GNU http://www.gnu.org/licenses/gpl-2.0.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */

class A561_Replacements
{
    static $replacements = array();

    /**
     * Adds a CSS replacement to the Sleightofhand queue
     *
     * @param string $selector CSS Selector
     * @param array  $settings Array of Sleightofhand settings
     *
     * @return void
     */
    static public function addReplacement($selector='',$settings=array())
    {
        self::$replacements[] = array($selector,$settings);
    }

}