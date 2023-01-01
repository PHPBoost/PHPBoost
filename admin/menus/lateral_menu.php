<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 02
 * @since       PHPBoost 2.0 - 2008 11 23
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

function lateral_menu()
{
    $view = new FileTemplate('admin/menus/panel.tpl');
    $view->add_lang(LangLoader::get_all_langs());
    $view->put_all(array());
    $view->display();
}
?>
