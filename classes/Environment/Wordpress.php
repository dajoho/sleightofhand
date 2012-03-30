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
 * Sleightofhand_Environment_Redaxo4 - Contains information about the
 * current REDAXO4 installation.
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  MIT License http://www.opensource.org/licenses/mit-license.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */

class Sleightofhand_Environment_Wordpress
implements Sleightofhand_Environment_Abstract
{
    /**
     * Detects if the user is logged into the backend
     *
     * @return boolean Backend/Frontend
     */
    public function isBackend()
    {
        return false;
    }

    /**
     * Detects if the environment is encoded with UTF8
     * or Latin.
     *
     * @return boolean Latin/UTF8
     */
    public function isLatin()
    {
        return false;
    }

    /**
     * Gets location of the module folder
     *
     * @return string Path to folder
     */
    public function getModulePath()
    {
        $dir =  WP_PLUGIN_DIR . '/sleightofhand/';
        return $dir;
    }

    /**
     * Gets location of the cache folder
     *
     * @return string Path to folder
     */
    public function getCachePath()
    {
        return $this->getModulePath();
    }

    /**
     * Gets location of the public PNG-Cache folder
     *
     * @return string Path to folder
     */
    public function getPublicPath()
    {
        return dirname(PLUGINDIR) . '/soh/';
    }

    /**
     * Gets location of the asset folder
     *
     * @return string Path to folder
     */
    public function getAssetPath()
    {
        return $this->getModulePath().'data/';
    }

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
            Sleightofhand_Output_Filter::register($callback);
        }
    }

}