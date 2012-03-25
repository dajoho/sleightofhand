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
 * A561_Environment_Redaxo - Contains information about the
 * current REDAXO4 installation.
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  MIT License http://www.opensource.org/licenses/mit-license.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */

class A561_Environment_Redaxo extends A561_Environment
{
    /**
     * Detects if the user is logged into the backend
     *
     * @return boolean Backend/Frontend
     */
    public function isBackend()
    {
        global $REX;
        if ($REX['REDAXO']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Detects if the environment is encoded with UTF8
     * or Latin.
     *
     * @return boolean Latin/UTF8
     */
    public function isLatin()
    {
        global $REX;
        $pos = strpos($REX['LANG'], '_utf8');
        if ($pos === false) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Gets location of the module folder
     *
     * @return string Path to folder
     */
    public function getModulePath()
    {
        global $REX;
        return $REX['INCLUDE_PATH'] . '/addons/sleightofhand/';
    }

    /**
     * Gets location of the cache folder
     *
     * @return string Path to folder
     */
    public function getCachePath()
    {
        global $REX;
        return $REX['INCLUDE_PATH'] . '/generated/files/';
    }

    /**
     * Gets location of the public PNG-Cache folder
     *
     * @return string Path to folder
     */
    public function getPublicPath()
    {
        global $REX;
        return $REX['HTDOCS_PATH'] . 'files/soh/';
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
        rex_register_extension($type, $callback);
    }

}