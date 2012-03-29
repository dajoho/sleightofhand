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
 * Sleightofhand_Environment - Abstract class, containing methods
 * to retrieve information about the current environment.
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  MIT License http://www.opensource.org/licenses/mit-license.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */

interface Sleightofhand_Environment_Abstract
{
    /**
     * Detects if the user is logged into the backend
     *
     * @return boolean Backend/Frontend
     */
    public function isBackend();

    /**
     * Detects if the environment is encoded with UTF-8
     * or Latin. Always false, because REDAXO5 uses unicode.
     *
     * @return boolean Latin/UTF8
     */
    public function isLatin();

    /**
     * Gets location of the module folder
     *
     * @return string Path to folder
     */
    public function getModulePath();

    /**
     * Gets location of the cache folder
     *
     * @return string Path to folder
     */
    public function getCachePath();

    /**
     * Gets location of the public PNG-Cache folder
     *
     * @return string Path to folder
     */
    public function getPublicPath();

    /**
     * Gets location of the asset folder
     *
     * @return string Path to folder
     */
    public function getAssetPath();

    /**
     * Registers an extension point
     *
     * @param string $type     Extension Point type
     * @param mixed  $callback Callback function/array
     *
     * @return void
     */
    public function extensionPoint($type,$callback);

}