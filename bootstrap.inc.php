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
 * Sleightofhand - Generic Bootstrap to get things going
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  MIT License http://www.opensource.org/licenses/mit-license.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */
class Sleightofhand
{
    static $env;

    /**
     * Static function to start the bootloader, fails if
     * environment can't be detected.
     *
     * @param string $dir Sleightofhand Module Folder
     *
     * @return void
     */
    static public function main($dir='')
    {
        /* classes, functions */
        include_once $dir . 'classes/Environment/Abstract.php';
        include_once $dir . 'classes/Environment/Redaxo5.php';
        include_once $dir . 'classes/Environment/Redaxo4.php';
        include_once $dir . 'classes/Environment/Redaxo3.php';
        include_once $dir . 'classes/Environment/Sally.php';
        include_once $dir . 'classes/Environment/Oxid.php';
        include_once $dir . 'classes/Environment/Wordpress.php';
        include_once $dir . 'classes/Output/Filter.php';
        include_once $dir . 'classes/Generator/Generator.php';
        include_once $dir . 'classes/Replacement/Replacement.php';
        include_once $dir . 'classes/Magic/Magic.php';
        include_once $dir . 'functions/Sleightofhand.php';

        /* extension points */
        include_once $dir . 'extensions/Scripts.php';
        include_once $dir . 'extensions/Cache.php';

        /* include phpquery + replacement EP, if using php5 */
        if (!class_exists('phpQuery')) {
            include_once $dir . 'classes/Phpquery/Phpquery.php';
            include_once $dir . 'extensions/Replacement.php';
        }

        /* check if environment can be detected, if not, return. */
        try {
            $env = self::env();
        } catch (Exception $e) {
            return;
        }

        /** @todo Tidy $env */
        $env = self::make('Environment');

        /* css, javascripts etc. */
        if (isset($_REQUEST['sleightofhand_css'])) {
            $cssfile = $env->getAssetPath() . 'soh.css';
            Sleightofhand::sendFile($cssfile, 'text/css');
            exit();
        }
        if (isset($_REQUEST['sleightofhand_js'])) {
            $jsfile = $env->getAssetPath() . 'soh.js';
            Sleightofhand::sendFile($jsfile, 'text/javascript');
            exit();
        }
        if (isset($_REQUEST['sleightofhand_js-ie'])) {
            $jsfile = $env->getAssetPath() . 'soh-ie.js';
            Sleightofhand::sendFile($jsfile, 'text/javascript');
            exit();
        }
    }

    /**
     * Detects the current environment and returns a string
     * containing its name/version.
     *
     * @return string Environment Name/Version
     */
    static public function getEnvironmentSuffix()
    {
        global $REX;

        if (class_exists('rex_extension')) {
            return 'redaxo5';
        } else if (function_exists('rex_register_extension')) {
            return 'redaxo4';
        } else if (isset($REX)) {
            return 'redaxo3';
        } else if (defined('SLY_SALLYFOLDER')) {
            return 'sally';
        } else if (class_exists('oxSuperCfg')) {
            return 'oxid';
        } else if (class_exists('WP')) {
            return 'wordpress';
        }
        return '';
    }

    /**
     * Creates instances of desired classes.
     * Returns environment-specific intances, if available.
     *
     * @param string $class  Class Name
     * @param array  $params Parameters to pass to __constructor()
     *
     * @return object Instance of whatever class is called
     */
    static public function make($class='',$params=array())
    {   $object = null;
        if ($class!='') {
            $class = 'Sleightofhand_' . $class;
            $specificClass = ucfirst($class) . '_'
                . ucfirst(self::getEnvironmentSuffix());
            if (class_exists($specificClass)) {
                $object = new $specificClass();
            } else if (class_exists($class)) {
                $object = new $class();
            }
        }
        if (is_object($object)) {
            return $object;
        } else {
            throw new Exception('No classes match: '.$class);
        }
    }

    /**
     * Returns an instance of the current environment object
     *
     * @return Environment
     */
    static public function env()
    {
        if (!is_object(self::$env)) {
            self::$env = self::make('Environment');
        }
        return self::$env;
    }

    /**
     * Sends a file to the browser with the given MIME-Type
     *
     * @param string $filename Filename to send
     * @param string $mimetype Mimetype to send
     *
     * @return void
     */
    static public function sendFile($filename='',$mimetype='text/plain')
    {
        header('Content-Type:' . $mimetype);
        echo file_get_contents($filename);
    }
}

/**
 * A561 - Wrapper for Sleightofhand Class
 *
 * @category   Sleightofhand
 * @package    Sleightofhand
 * @author     Dave Holloway <dh@dajoho.de>
 * @license    MIT License http://www.opensource.org/licenses/mit-license.html
 * @version    Release: <package_version>
 * @link       http://bit.ly/sleightofhand-site
 * @deprecated Here for compatibility reasons only. Please use Sleightofhand.
 */
class A561 extends Sleightofhand
{
}

Sleightofhand::main($dir);