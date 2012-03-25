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
 * A561_Sleightofhand - Main SOH Graphic Generation Class
 *
 * @category Sleightofhand
 * @package  Sleightofhand
 * @author   Dave Holloway <dh@dajoho.de>
 * @license  GNU http://www.gnu.org/licenses/gpl-2.0.html
 * @version  Release: <package_version>
 * @link     http://bit.ly/sleightofhand-site
 */
class A561_Sleightofhand
{
    /**
     * Boolean. True if a class instance is valid,
     * false if not.
     *
     * @var unknown_type
     */
    var $valid = false;

    /**
     * Main/Init SOH Function
     * Checks settings, caching and generally
     * gets things going.
     *
     * @param array $settings Array of Sleightofhand settings
     */
    public function __construct($settings = array())
    {
        $this->env = A561::make('Environment');

        $this->settings = $settings;

        $this->setting(
            'fontpath',
            $this->env->getModulePath() . 'fonts/'
        );

        $font = $this->setting('font');
        $fontpath = $this->setting('fontpath');
        $size = $this->setting('size');
        $color = $this->setting('color');
        $wrap = $this->setting('wordwrap');
        $quality = $this->setting('quality');
        $align = $this->setting('text-align');

        $quality = intVal($quality);
        if ($quality == 0) {
            $quality = 4;
        }
        $this->setting('quality', $quality);

        /*
         * Do some decoding, as it is possible that html will get passed
         */
        $text = $this->htmlSpecialCharsDecode($settings['text']);
        $text = strip_tags($text);

        if ($this->env->isLatin()) {
            $text = utf8_decode($text);
        }

        if ($wrap > 0) {
            $text = wordwrap($text, $wrap, "\n");
        }

        $this->setting('text', $text);

        if (empty($font) || empty($size) || empty($color) || empty($text)) {
            $this->valid = false;
            $this->error = '[$font, $size or $color missing]';
        }

        if (!empty($font) && !empty($size) && !empty($color)
            && file_exists($fontpath . $font)
        ) {

            $this->valid = true;

            $cachekey = md5(serialize($this->settings));
            $cachepath = $this->env->getPublicPath();

            if (!file_exists($cachepath)) {
                $result = @mkdir($cachepath);

                $this->setting('result', $result);
            }

            $cachefile = $cachepath . 'soh-' . $cachekey . '.png';
            $this->setting('cachefile', $cachefile);
            if (!file_exists($cachefile)) {
                $this->generate();
            }
            /*
             * Force compiling for development
             */
            //$this->generate();
        }
    }

    /**
     * Generates the Sleightofhand graphics
     *
     * @return void
     */
    function generate()
    {
        /*
         * This isn't really needed, and should be commented out while testing.
         * It is only here for poorly configured servers.
         */
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '256M');

        $width = 0;
        $height = 0;
        $offsetX = 0;
        $offsetY = 0;
        $bounds = array();
        $image = "";

        $sizeMultiply = $this->setting('size') * $this->setting('quality');
        $spacing = $this->setting('spacing');

        /*
         * Determine font height.
         * Andreas: http://www.redaxo.de/165-Moduldetails.html?module_id=188
         */
        $abc = 'öäüÖÄÜßABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghi'
               .'jklmnopqrstuvwxyz_0123456789;:<>/(){}%$§"!';
        $bounds = ImageTTFBBox(
            $sizeMultiply, 0,
            $this->setting('fontpath') . $this->setting('font'),
            $abc
        );

        $size = $this->convertBoundingBox($bounds);

        $bounds = ImageTTFBBox(
            $sizeMultiply, 0,
            $this->setting('fontpath') . $this->setting('font'),
            $this->setting('text')
        );
        $sizeB = $this->convertBoundingBox($bounds);

        $width = $sizeB['width'] + 40; // this will be cropped
        $height = $size['height'];
        $offsetY = $size['yOffset'];
        $offsetX = 0;

        /*
         * Deal with multiple lines
         */
        $spacing = floatVal($spacing);
        if ($spacing == 0) {
            $spacing = 1.4;
        }
        $x = $offsetX;
        $y = $offsetY;
        $lines = explode("\n", $this->setting('text'));
        $newY = 0;

        for ($i = 0; $i < count($lines); $i++) {
            $newY = $y + ($i * $sizeMultiply * $spacing);
        }
        $newHeight = $newY + $size['belowBasepoint'];

        /*
         *  Create Alpha Channel
         */
        $image = ImageCreateTrueColor($width, $newHeight);
        ImageSaveAlpha($image, true);
        // ImageAlphaBlending($image, false);
        $bg = ImageColorAllocateAlpha(
            $image, 220, 220, 220, 127
        );
        $bgB = $bg;
        ImageFill(
            $image, 0, 0, $bg
        );
        $fg = $this->convertHex($this->setting('color'));
        $foreground = ImageColorAllocateAlpha(
            $image, $fg[0], $fg[1], $fg[2], 0
        );

        /*
         * Render all lines
         */
        $newY = 0;
        $align = $this->setting('text-align');

        for ($i = 0; $i < count($lines); $i++) {
            $newY = $y + ($i * $sizeMultiply * $spacing);

            $bounds = ImageTTFBBox(
                $sizeMultiply, 0,
                $this->setting('fontpath') . $this->setting('font'),
                $lines[$i]
            );
            switch ($align) {
            case "left":
            case "l":
            case "":
                /* do nothing */
                break;

            case "center":
            case "centre":
            case "c":
                $x = ceil(($width - $bounds[2]) / 2);
                break;

            case "right":
            case "r":
                $x = ($width - $bounds[2]);
                break;
            }

            ImageTTFText(
                $image, $sizeMultiply, 0, $x, $newY, $foreground,
                $this->setting('fontpath') . $this->setting('font'),
                $lines[$i]
            );
        }

        /*
         * Rotation
         */
        $angle = $this->setting('rotateX');
        if ($angle > 0) {
            $magic = new a561_magic;
            $image = $magic->rotate($image, $angle);
            $bgB = imagecolorat($image, 5, 5);
        }

        /*
         * Auto-Crop
         */
        $p = array_fill(0, 4, 0);

        /* Get the image width and height. */
        $imw = imagesx($image);
        $imh = imagesy($image);

        /* Set the X variables. */
        $xmin = $imw;
        $xmax = 0;

        /* Start scanning for the edges. */

        for ($iy = 0; $iy < $imh; $iy++) {
            $first = true;
            for ($ix = 0; $ix < $imw; $ix++) {
                $ndx = imagecolorat($image, $ix, $iy);

                if ($ndx != $bg && $ndx != $bgB) {
                    if ($xmin > $ix) {
                        $xmin = $ix;
                    }
                    if ($xmax < $ix) {
                        $xmax = $ix;
                    }
                    if ($first) {
                        $ix = $xmax;
                        $first = false;
                    }
                }
            }
        }

        $imw = 1 + $xmax - $xmin; /* Image width in pixels */

        /* Make another image to place the trimmed version in. */
        $imB = imagecreatetruecolor($imw + $p[1] + $p[3], $imh + $p[0] + $p[2]);

        /*
         * Make the background of the new image the same as
         * the background of the old one.
         */
        ImageSaveAlpha($imB, true);
        ImageAlphaBlending($imB, false);

        /* Copy it over to the new image. */
        imagecopy($imB, $image, $p[3], $p[0], $xmin, 0, $imw, $imh);
        $image = $imB;

        /*
         * Antialiasing (downsampling)
         * Robcs (http://forum.redaxo.de/sutra74521.html#74521)
         */
        $imgwX = imagesx($image);
        $imghX = imagesy($image);
        $imageAntialised = ImageCreateTrueColor(
            $imgwX / $this->setting('quality'),
            $imghX / $this->setting('quality')
        );
        ImageSaveAlpha($imageAntialised, true);
        ImageAlphaBlending($imageAntialised, false);
        ImageCopyResampled(
            $imageAntialised, $image, 0, 0, 0, 0,
            $imgwX / $this->setting('quality'),
            $imghX / $this->setting('quality'), $imgwX, $imghX
        );

        /* Combine mouseover with main image */
        if ($this->setting('mouseover') != "") {
            $tmp = $this->settings;
            $tmp['color'] = $this->setting('mouseover');
            unset($tmp['mouseover']);
            $mouseover = a561_sleightofhand($tmp, true);

            $sw = imagesx($imageAntialised);
            $sh = imagesy($imageAntialised);

            $newcanvas = imagecreatetruecolor(($sw * 2) + 10, $sh);
            $bg = ImageColorAllocateAlpha($image, 220, 220, 220, 127);
            ImageFill($newcanvas, 0, 0, $bg);

            ImageSaveAlpha($newcanvas, true);
            ImageAlphaBlending($newcanvas, false);
            $basepng = $imageAntialised;
            $mouseoverpng = imagecreatefrompng($mouseover);

            $this->setting('mouseoverpng', $mouseover);

            imagecopyresampled(
                $newcanvas, $basepng, 0, 0, 0, 0, $sw, $sh, $sw, $sh
            );
            imagecopyresampled(
                $newcanvas, $mouseoverpng, $sw + 10, 0, 0, 0, $sw, $sh, $sw, $sh
            );

            /* Overwrite the original base png with combined version */
            $imageAntialised = $newcanvas;
        }

        /* Cache the file */
        ImagePNG($imageAntialised, $this->setting('cachefile'));
    }

    /**
     * Converts HEX Codes into RGB
     *
     * @param string $hex String containing hex value
     *
     * @return array Array of RGB values
     */
    function convertHex($hex)
    {
        if (is_array($hex)) {
            return $hex;
        }
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) == 6) {
            return array(
                hexdec(substr($hex, 0, 2)),
                hexdec(substr($hex, 2, 2)),
                hexdec(substr($hex, 4, 2))
            );
        }
    }

    /**
     * Returns path to a generated Sleightofhand image
     *
     * @return string Path to image
     */
    function getImageLink()
    {
        $cachefile = $this->setting('cachefile');
        if (file_exists($cachefile)) {
            return $this->env->getPublicPath() . basename($cachefile);
        }
        return '';
    }

    /**
     * Returns HTML code for embedding a Sleightofhand graphic
     *
     * @return string HTML Code
     */
    function getCode()
    {
        $cachefile = $this->setting('cachefile');

        if (file_exists($cachefile)) {
            $dims = getimagesize($cachefile);

            $this->setting('width', $dims[0]);
            $this->setting('height', $dims[1]);

            $code = '';

            $classes = array();
            $classes[] = 'soh';

            if ($this->setting('mouseover') != "") {
                $this->setting(
                    'width',
                    intVal($dims[0] / 2) - 5  /* 5 = padding/2 */
                );
                $classes[] = 'soh-mouseover';
            }

            $code .= '<span class="' . implode(' ', $classes) . '"';

            if ($this->setting('mouseoverpng') != "") {
                $rel = $this->getImageLink() . ','
                        . $this->setting('mouseoverpng');

                /*
                 * If using html5 mode, the validator stupidly thinks "rel"
                 * is invalid here we remove the rel= for the validator.
                 * Remember; html5 isn't yet final, so there is still a good
                 * chance rel will be reactivated.
                 * If you disagree with this, don't use Sleightofhand
                 */
                $ua = substr($_SERVER['HTTP_USER_AGENT'], 0, 3);
                if ($ua != "W3C") {
                    $code .= ' rel="' . $rel . '"';
                }
            }

            if ($this->env->isBackend()) {
                $text = '';
            } else {
                if ($this->env->isLatin()) {
                    $text = htmlentities($this->setting('text'));
                } else {
                    $text = htmlentities(
                        $this->setting('text'), ENT_QUOTES, 'UTF-8'
                    );
                }
            }
            $code .= ' style="width:' . $this->setting('width') . 'px;height:'
                    . $this->setting('height') . 'px;background-image:url('
                    . $this->env->getPublicPath() . basename($cachefile)
                    . ')">' . $text . '</span>';

            if ($this->setting('link') != "") {
                $code = '<a href="' . $this->setting('link') . '">' . $code
                        . '</a>';
            }

            if ($this->setting('prefix') != "") {
                $code = $this->setting('prefix') . $code;
            }

            if ($this->setting('suffix') != "") {
                $code = $code . $this->setting('suffix');
            }

            return $code;
        } else {
            return '<p class="soh-error"><strong>sleightofhand</strong>-'.
            'Please check permissions on: ' . $cachepath . '</p>';
        }
    }

    /**
     * Internal function to calculate box positioning.
     * It is probably best not to touch this.
     *
     * @param array $bbox Array of box information
     *
     * @return array Processed array of box information
     */
    function convertBoundingBox($bbox)
    {
        if ($bbox[0] >= -1) {
            $xOffset = -abs($bbox[0] + 1);
        } else {
            $xOffset = abs($bbox[0] + 2);
        }
        $width = abs($bbox[2] - $bbox[0]);
        if ($bbox[0] < -1) {
            $width = abs($bbox[2]) + abs($bbox[0]) - 1;
        }
        $yOffset = abs($bbox[5] + 1);
        if ($bbox[5] >= -1) {
            $yOffset = -$yOffset; /* Fixed characters below the baseline. */
        }
        $height = abs($bbox[7]) - abs($bbox[1]);
        if ($bbox[3] > 0) {
            $height = abs($bbox[7] - $bbox[1]) - 1;
        }
        return array('width' => $width, 'height' => $height,
                'xOffset' => $xOffset,
                'yOffset' => $yOffset,
                'belowBasepoint' => max(0, $bbox[1]));
    }

    /**
     * Decodes any HTML-Entities back to normal characters
     *
     * @param string $string String containing entities
     * @param string $style  Style of conversion
     *
     * @return string Converted string
     */
    function htmlSpecialCharsDecode($string, $style = ENT_COMPAT)
    {
        $translation = array_flip(
            get_html_translation_table(HTML_SPECIALCHARS, $style)
        );
        if ($style === ENT_QUOTES) {
            $translation['&#039;'] = '\'';
        }
        $string = strtr($string, $translation);
        $string = str_replace('&amp;', '&', $string);
        return $string;
    }

    /**
     * Generic getter and setter for internal class settings
     *
     * @param string $key   Key
     * @param string $value Value
     *
     * @return mixed Key-Value or true
     */
    function setting($key = null, $value = null)
    {
        if ($key != null && $value == null) {
            /* getter */
            if (isset($this->settings[$key])) {
                return $this->settings[$key];
            } else {
                return '';
            }
        } else if ($key != null && $value != null) {
            /* setter */
            $this->settings[$key] = $value;
            return true;
        }

    }
}