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
 * Main Sleightofhand Conversion Function
 *
 * @param array   $settings Array of settings
 * @param boolean $fn       Return Image Link or Code
 *
 * @return string
 */
function A561_sleightofhand($settings = array(),$fn=false)
{
    $obj = new a561_sleightofhand($settings);

    if ($obj->valid) {
        if (!$fn) {
            return $obj->getCode();
        } else {
            return $obj->getImageLink();
        }
    } else {
        return '';
    }
}

/**
 * Queues a replacement of given CSS Selector.
 * Runs as an extension point on the whole HTML output.
 *
 * @param string $selector CSS Selector
 * @param string $settings Array of Sleightofhand settings
 *
 * @return void
 */
function A561_addReplacement($selector='',$settings=array())
{
    global $REX;
    if (version_compare(PHP_VERSION, '5.0.0', '>')) {
        $REX['ADDON']['settings']['sleightofhand']['replacements'][] = array(
            $selector,$settings);
    } else {
        //fail silently
    }
}

/**
 * Replaces elements that match a certain CSS Selector
 * within a given HTML string with Sleightofhand Images.
 *
 * @param string $selector CSS Selector
 * @param string $output   HTML Source
 * @param array  $settings Array of Sleightofhand Settings
 *
 * @return string Modified HTML Code
 */
function A561_replace($selector='',$output='',$settings=array())
{
    if (version_compare(PHP_VERSION, '5.0.0', '>')) {
        $doc = phpQuery::newDocument($output);
        $reps = array();
        $reps[0] = array($selector,$settings);
        foreach ($reps as $rep) {
            if (count($rep)==2) {
                $elems = pq($rep[0]);
                foreach ($elems as $elem) {
                    $pq = pq($elem);
                    $text = $pq->html();
                    $text = strip_tags($text);
                    $settings = $rep[1];
                    $settings['text']=$text;
                    $html = a561_sleightofhand($settings);
                    $pq->html($html);
                }

            }
        }
        $pq = pq($doc);
        $output = $pq->markup();

        //remove empty tags
        $output = preg_replace(
            '/<(p|span|strong|b|em|h1|h2|h3|h4|h5|h6)>(\s|\b)*<\/\1>/',
            '', $output
        );
        $output = str_replace("\n", '', $output);
        $output = str_replace("\r", '', $output);
        $output = str_replace("\t", '', $output);
    }
    return $output;
}