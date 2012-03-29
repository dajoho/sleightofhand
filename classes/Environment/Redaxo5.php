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
 * Sleightofhand_Environment_Redaxo5 - Contains information about the
 * current REDAXO5 installation.
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  MIT License http://www.opensource.org/licenses/mit-license.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */

class Sleightofhand_Environment_Redaxo5 implements Sleightofhand_Environment_Abstract
{
    /**
     * Constructor. Saves an instance of the Sleightofhand rex_addon
     *
     * @return void
     */
    public function __construct()
    {
        $this->addon = new rex_addon('sleightofhand');
    }

    /**
     * Detects if the user is logged into the backend
     *
     * @return boolean Backend/Frontend
     */
    public function isBackend()
    {
        return rex::isBackend();
    }

    /**
     * Detects if the environment is encoded with UTF-8
     * or Latin. Always false, because REDAXO5 uses unicode.
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
        return rex_path::src() . 'addons/sleightofhand/';
    }

    /**
     * Gets location of the cache folder
     *
     * @return string Path to folder
     */
    public function getCachePath()
    {
        $dir = rex_path::addonCache('sleightofhand');
        rex_dir::create($dir);
        return $dir;
    }

    /**
     * Gets location of the public PNG-Cache folder
     *
     * @return string Path to folder
     */
    public function getPublicPath()
    {
        $dir = rex_path::addonData('sleightofhand');
        rex_dir::create($dir);
        return $this->addon->getAssetsPath();
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
    public function extensionPoint($type,$callback)
    {
        switch ($type) {
        case 'ALL_GENERATED':
            $type = 'CACHE_DELETED';
            break;
        }
        rex_extension::register($type, $callback);
    }
}