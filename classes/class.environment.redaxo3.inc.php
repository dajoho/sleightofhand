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
 * A561_Environment_Redaxo3 - Contains information about the
 * current REDAXO3 installation.
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  MIT License http://www.opensource.org/licenses/mit-license.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */

class A561_Environment_Redaxo3 extends A561_Environment_Redaxo4
{
    /**
     * Registers an extension point
     *
     * @param string $type     Extension Point type
     * @param mixed  $callback Callback function/array
     *
     * @return void
     */
    public function extensionPoint($type, $callback)
    {
        if ($type == 'OUTPUT_FILTER') {
            A561_Output_Filter::register($callback);
        }
        /** @todo: ALL_GENERATED detection? **/
    }

}