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
 * Sleightofhand_Environment_Sally - Contains information about the
 * current SallyCMS installation.
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  MIT License http://www.opensource.org/licenses/mit-license.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */

class Sleightofhand_Environment_Sally implements Sleightofhand_Environment_Abstract
{
    /**
     * Constructor. Saves an instance of the Sleightofhand rex_addon
     *
     * @return void
     */
    public function __construct()
    {
        $this->addon = sly_Service_Factory::getService('AddOn');
    }

    /**
     * Detects if the user is logged into the backend
     *
     * @return boolean Backend/Frontend
     */
    public function isBackend()
    {
        return sly_Core::isBackend();
    }

    /**
     * Detects if the environment is encoded with UTF-8
     * or Latin. Always false, because Sally uses unicode.
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
        return $this->addon->baseFolder('sleightofhand') . '/';
    }

    /**
     * Gets location of the cache folder
     *
     * @return string Path to folder
     */
    public function getCachePath()
    {
        $dir = $this->addon->internalFolder('sleightofhand') . '/';
        return str_replace(SLY_DATAFOLDER, './data', $dir);
    }

    /**
     * Gets location of the public PNG-Cache folder
     *
     * @return string Path to folder
     */
    public function getPublicPath()
    {
        $dir = $this->addon->publicFolder('sleightofhand') . '/';
        return str_replace(SLY_DATAFOLDER, './data', $dir);
    }

    /**
     * Gets location of the asset folder
     *
     * @return string Path to folder
     */
    public function getAssetPath()
    {
        return $this->getModulePath() . 'data/';
    }

    /**
     * Registers an extension point
     *
     * @param string $type     Extension Point type
     * @param mixed  $callback Callback function/array
     *
     * @return void
     */
    public function extensionPoint($type,$callback)
    {
        if ($type != 'ALL_GENERATED') {
            $dispatcher = sly_Core::dispatcher();
            $dispatcher->register('OUTPUT_FILTER', $callback);
        }
    }
}