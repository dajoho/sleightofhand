<?php
/**
 * Sleightofhand
 *
 * PHP version 5
 *
 * @package Sleightofhand
 * @author  Dave Holloway <dh@dajoho.de>
 * @license GNU http://www.gnu.org/licenses/gpl-2.0.html
 * @version GIT: <git_id>
 * @link    http://bit.ly/sleightofhand-site
 */

/**
 * A561_Magic - Manages communication with ImageMagick
 *
 * @package Sleightofhand
 * @author  Dave Holloway <dh@dajoho.de>
 * @license GNU http://www.gnu.org/licenses/gpl-2.0.html
 * @version Release: <package_version>
 * @link    http://bit.ly/sleightofhand-site
 */
class A561_Magic
{
    /**
     * Constructor. Calls functions to find ImageMagick
     * and sets cache variable path
     *
     * @return void
     */
    function a561_magic()
    {
        global $REX;
        $this->locateMagic();
        $this->generated = $REX['INCLUDE_PATH'] . '/generated/files/';
    }

    /**
     * Finds ImageMagick by trying out several common locations
     *
     * @return void
     */
    function locateMagic()
    {
        global $REX;
        $this->match = false;
        $paths = array(
                $REX['ADDON']['settings']['sleightofhand']['imagemagic']
                . '/convert', 'convert', './convert',
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
    function rotate($image, $angle)
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