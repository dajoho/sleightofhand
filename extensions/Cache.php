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
 * Clears Sleightofhand Cache when the ALL_GENERATED
 * REDAXO Extension Point is called.
 *
 * @param array $output REDAXO ALL_GENERATED Settings
 *
 * @return string Unmodified EP Subject
 */
function Sleightofhand_clearCache($output)
{
    $output = Sleightofhand_Output_Filter::parse($output);

    foreach (glob(Sleightofhand::env()->getPublicPath() . '*.png') as $file) {
        @unlink($file);
    }
    return $output;
}
Sleightofhand::env()->extensionPoint(
    'ALL_GENERATED', 'Sleightofhand_clearCache'
);