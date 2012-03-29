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
 * Sleightofhand_Output_Filter - Generic output filter
 * for environments without inbuilt output processors.
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  MIT License http://www.opensource.org/licenses/mit-license.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */

class Sleightofhand_Output_Filter
{
    /**
     * Registers a new output filter
     *
     * @param mixed $callback Callback function/array
     *
     * @return void
     */
    static public function register($callback)
    {
        ob_start($callback);
        register_shutdown_function('ob_end_flush');
    }

    /**
     * Environment-sensitive helper function to
     * prepare content for the callbacks.
     *
     * @param mixed $output REDAXO Array or string
     *
     * @return string Output filter subject (normally HTML)
     */
    static public function parse($output)
    {
        if (is_array($output)) {
            $output = $output['subject'];
        }
        return $output;
    }

}