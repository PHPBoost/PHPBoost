<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 2.0 - 2008 11 23
*/

function lateral_menu()
{
    global $LANG;
    $tpl = new FileTemplate('admin/menus/panel.tpl');
    $tpl->put_all(array(
        'L_MENUS_MANAGEMENT' => $LANG['menus_management'],
        'L_ADD_CONTENT_MENUS' => $LANG['menus_content_add'],
        'L_ADD_LINKS_MENUS' => $LANG['menus_links_add'],
        'L_ADD_FEED_MENUS' => $LANG['menus_feed_add']
    ));
    $tpl->display();
}
?>
