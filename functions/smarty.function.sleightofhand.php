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
 * Smarty function to redirect calls through
 * Sleightofhand.
 *
 * @param array  $params  Sleightofhand settings
 * @param Smarty &$smarty Smarty reference
 *
 * @example [{sleightofhand
 *              text=$oDetailsProduct->oxarticles__oxtitle->value
 *              settings='size:25;color:#000000;mouseover:#FF0000;
 *              rotateX:2;wordwrap:20;font:Chinese_Ruler.ttf;' }]
 *
 * @return string Modified string
 */
function Smarty_Function_sleightofhand($params, &$smarty)
{
    $text = (isset($params['text'])) ? $params['text'] : '';

    $settings = array();
    if (isset($params['settings'])) {
        $tmpSettings = explode(';', $params['settings']);
        foreach ($tmpSettings as $row) {
            $setting = explode(':', $row, 2);
            if (count($setting) == 2) {
                $key = trim($setting[0]);
                $value = trim($setting[1]);
                if ($key != '' && $value != '') {
                    $settings[$key] = $value;
                }
            }
        }
        $settings['text'] = $text;
        $text = A561_Sleightofhand($settings);
    }
    return $text;
}
