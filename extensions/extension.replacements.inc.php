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
 * Replaces via REDAXO OUTPUT_FILTER any registered
 * CSS Element Replacements.
 *
 * @param array $output REDAXO OUTPUT_FILTER Settings
 *
 * @return string Modified HTML Code
 */
function A561_replacements($output)
{
    $output = A561_Output_Filter::parse($output);

    $reps = A561_Replacements::$replacements;

    if (count($reps) > 0) {
        $doc = phpQuery::newDocument($output);
        foreach ($reps as $rep) {
            if (count($rep) == 2) {
                $elems = pq($rep[0]);
                foreach ($elems as $elem) {
                    $text = pq($elem)->html();
                    $text = strip_tags($text);
                    $settings = $rep[1];
                    $settings['text'] = $text;
                    $html = a561_sleightofhand($settings);
                    pq($elem)->html($html);
                }

            }
        }
        $output = pq($doc)->markup();

        //remove empty tags
        $output = preg_replace(
            '/<(p|span|strong|b|em|h1|h2|h3|h4|h5|h6)>(\s|\b)*<\/\1>/', '',
            $output
        );
    }

    return $output;
}
A561::env()->extensionPoint('OUTPUT_FILTER', 'a561_replacements');