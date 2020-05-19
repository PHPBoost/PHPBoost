<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 06
 * @since       PHPBoost 5.2 - 2020 03 06
*/

class SandboxSubMenu
{
    public static function get_submenu()
    {
        $view = new FileTemplate('sandbox/SandboxSubMenu.tpl');
		$view->add_lang(LangLoader::get('submenu', 'sandbox'));

		$view->put_all(array(
            'C_GMAP' => ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key(),
		));

        return $view;
    }
}
?>
