<?php
/**
 * Sleightofhand
 *
 * PHP version 5
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  GNU http://www.gnu.org/licenses/gpl-2.0.html
 * @version  GIT: <git_id>
 * @link     http://bit.ly/sleightofhand-site
 */

/**
 * A561 - Generic Bootstrap to get things going
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  GNU http://www.gnu.org/licenses/gpl-2.0.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */

class A561
{
    static $env;

    /**
     * Static function to start the bootloader
     *
     * @param string $dir Sleightofhand Module Folder
     *
     * @return void
     */
    static public function main($dir='')
    {
        /* classes, functions */
        include_once $dir . 'classes/class.environment.inc.php';
        include_once $dir . 'classes/class.environment.redaxo4.inc.php';
        include_once $dir . 'classes/class.environment.redaxo5.inc.php';
        include_once $dir . 'classes/class.sleightofhand.inc.php';
        include_once $dir . 'classes/class.replacements.inc.php';
        include_once $dir . 'classes/class.magic.inc.php';
        include_once $dir . 'functions/function.sleightofhand.inc.php';

        /* extension points */
        include_once $dir . 'extensions/extension.scripts.inc.php';
        include_once $dir . 'extensions/extension.cache.inc.php';

        /* include phpquery + replacement EP, if using php5 */
        if (version_compare(PHP_VERSION, '5.0.0', '>')) {
            if (!class_exists('phpQuery')) {
                include_once $dir . 'classes/class.phpquery.inc.php';
                include_once $dir . 'extensions/extension.replacements.inc.php';
            }
        }

        $env = self::make('Environment');

        /* retrieve an image if it is requested */
        $soh = rex_request('a561_soh', 'string');
        if ($soh != "") {
            $soh = str_replace('/', '', $soh);
            $soh = str_replace('.', '', $soh);
            $soh = str_replace("\\", '', $soh);

            $cachefile = $env->getCacheFolder().'soh-' . $soh . '.png';
            if (file_exists($cachefile)) {
                header("Content-type: image/png");
                $fp = fopen($cachefile, 'rb');
                header("Content-Length: " . filesize($cachefile));
                fpassthru($fp);
            }
            exit();
        }
        /* css, javascripts etc. */
        if (isset($_REQUEST['a561_css'])) {
            $cssfile = $env->getAssetPath() . 'soh.css';
            A561::sendFile($cssfile, 'text/css');
            exit();
        }
        if (isset($_REQUEST['a561_js'])) {
            $jsfile = $env->getAssetPath() . 'soh.js';
            A561::sendFile($jsfile, 'text/javascript');
            exit();
        }
        if (isset($_REQUEST['a561_js-ie'])) {
            $jsfile = $env->getAssetPath() . 'soh-ie.js';
            A561::sendFile($jsfile, 'text/javascript');
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
        } else if (isset($REX)) {
            return 'redaxo';
        } else if (!defined('IS_SALLY')) {
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
            $class = 'A561_' . $class;
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
A561::main($dir);