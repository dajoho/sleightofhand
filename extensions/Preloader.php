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
 * Adds preloader code to HTML.
 * CSS Element Replacements.
 *
 * @param array $output REDAXO OUTPUT_FILTER Settings
 *
 * @return string Modified HTML Code
 */
function Sleightofhand_preloader($output)
{
    $tags = Sleightofhand_Preloader::$tags;


    $preloaders = '';
    if (count($tags) > 0) {
        foreach ($tags as $tag) {
            $preloaders.=$tag;
        }
        $output = str_replace('</body>','<div id="sleightofhand_preloader" style="position:absolute;top:-10000em;left;-10000em;width:10em;">'.$preloaders.'</div></body>', $output);
    }


    return $output;
}
Sleightofhand::env()->extensionPoint(
    'OUTPUT_FILTER', 'Sleightofhand_preloader'
);