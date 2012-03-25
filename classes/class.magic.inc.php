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
 * A561_Magic - Manages communication with ImageMagick
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  MIT License http://www.opensource.org/licenses/mit-license.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */
class A561_Magic
{
    /**
     * Constructor. Calls functions to find ImageMagick
     * and sets cache variable path
     *
     * @return void
     */
    public function __construct()
    {
        $this->_locateMagic();
        $this->generated = A561::env()->getCachePath();
    }

    /**
     * Finds ImageMagick by trying out several common locations
     *
     * @return void
     */
    private function _locateMagic()
    {
        $this->match = false;
        $paths = array(
                'convert', './convert','/usr/local/bin/convert',
                '/usr/bin/convert', '/opt/local/bin/convert',);

        foreach ($paths as $path) {
            if (!$this->match) {
                $x = @exec($path);
                if (strlen($x) > 0) {
                    $this->match = true;
                    $this->convert = $path;
                }
            }
        }
    }

    /**
     * Uses ImageMagick to rotate an image to a give angle
     *
     * @param object $image True-colour image reference
     * @param int    $angle Angle in degrees
     *
     * @return object True-colour image reference
     */
    public function rotate($image, $angle)
    {
        if ($this->match) {
            $angle = intVal($angle);
            $srckey = $this->generated . 'soh-' . rand() . rand();
            $dstkey = $srckey . '-rotated';
            ImagePNG($image, $srckey . '.png');

            exec(
                $this->convert . '  -background none -rotate ' . $angle
                . ' ' . $srckey . '.png png32:' . $dstkey . '.png'
            );
            $image = imagecreatefrompng($dstkey . '.png');
            ImageSaveAlpha($image, true);
            ImageAlphaBlending($image, false);

            @unlink($srckey . '.png');
            @unlink($dstkey . '.png');
        }
        return $image;
    }
}