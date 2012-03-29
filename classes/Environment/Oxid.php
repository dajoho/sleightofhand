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
 * Sleightofhand_Environment_Oxid - Contains information about the
 * current OXID installation.
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  MIT License http://www.opensource.org/licenses/mit-license.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */
class Sleightofhand_Environment_Oxid implements Sleightofhand_Environment_Abstract
{
    /**
     * Starts consistency checks
     *
     * @return void
     */
    public function __construct()
    {
        $this->_oxidSymlinks();
    }

    /**
     * Symlinks internal module files to their
     * relevant folders within the OXID installation.
     * If a symlink exists and is faulty, it gets
     * repaired.
     *
     * @return void
     */
    private function _oxidSymlinks()
    {
        $links = array(
            'functions/Smarty.php'
            => 'core/smarty/plugins/function.sleightofhand.php'
        );
        /** @todo Improve this? What if the class gets moved? */
        $srcPath = dirname(dirname(dirname(__FILE__))) . '/';
        $destPath = getCwd() . '/';

        foreach ($links as $src=>$dest) {
            $src = $srcPath . $src;
            $dest = $destPath . $dest;
            if (!is_link($dest) || linkinfo($dest)==0 || !file_exists($dest)) {
                if (file_exists($dest)) {
                    @unlink($dest);
                }
                @symlink($src, $dest);
            }
        }

    }

    /**
     * Detects if the user is logged into the backend
     *
     * @return boolean Backend/Frontend
     */
    public function isBackend()
    {
        return isAdmin();
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
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/'
        . ltrim(dirname($_SERVER['SCRIPT_NAME']), '/')
        . '/modules/sleightofhand/';
        return $dir;
    }

    /**
     * Gets location of the cache folder
     *
     * @return string Path to folder
     */
    public function getCachePath()
    {
        return './tmp/';
    }

    /**
     * Gets location of the public PNG-Cache folder
     *
     * @return string Path to folder
     */
    public function getPublicPath()
    {
        return './out/sleightofhand/';
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