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
 * Clears Sleightofhand Cache when the ALL_GENERATED
 * REDAXO Extension Point is called.
 *
 * @param array $params REDAXO ALL_GENERATED Settings
 *
 * @return string Unmodified EP Subject
 */
function A561_clearcache($params)
{
    global $REX;
    foreach (glob($REX['HTDOCS_PATH'] . 'files/soh/*.png') as $file) {
        @unlink($file);
    }
    return $params['subject'];
}
rex_register_extension('ALL_GENERATED', 'a561_clearcache');