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
 * Sleightofhand_Replacement - Container class to store CSS replacement data
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  MIT License http://www.opensource.org/licenses/mit-license.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */

class Sleightofhand_Preloader
{
    static $tags = array();

    /**
     * Adds a preloader to the output_filter queue
     *
     * @param string $tag Preloader
     *
     * @return void
     */
    static public function addPreloader($tag)
    {
        self::$tags[] = $tag;
    }

}