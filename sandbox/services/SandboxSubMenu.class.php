<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 01
 * @since       PHPBoost 5.2 - 2020 05 19
*/

class SandboxSubMenu
{
    public static function get_submenu()
    {
        $view = new FileTemplate('sandbox/SandboxSubMenu.tpl');
		$view->add_lang(LangLoader::get_all_langs('sandbox'));

		$view->put_all(array(
            'C_GMAP'          => ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key(),
            'U_HOME'          => SandboxUrlBuilder::home()->rel(),
            'U_BUILDER'       => SandboxUrlBuilder::builder()->rel(),
            'U_COMPONENT'     => SandboxUrlBuilder::component()->rel(),
            'U_LAYOUT'        => SandboxUrlBuilder::layout()->rel(),
            'U_BBCODE'        => SandboxUrlBuilder::bbcode()->rel(),
            'U_ICONS'         => SandboxUrlBuilder::icons()->rel(),
            'U_MENUS_NAV'     => SandboxUrlBuilder::menus_nav()->rel(),
            'U_MENUS_CONTENT' => SandboxUrlBuilder::menus_content()->rel(),
            'U_TABLE'         => SandboxUrlBuilder::table()->rel(),
            'U_EMAIL'         => SandboxUrlBuilder::email()->rel(),
            'U_TEMPLATE'      => SandboxUrlBuilder::template()->rel(),
            'U_LANG'          => SandboxUrlBuilder::lang()->rel(),
		));

        return $view;
    }
}
?>
