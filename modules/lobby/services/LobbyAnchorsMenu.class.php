<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class LobbyAnchorsMenu
{
	/**
	 * Builds the one-page anchors navigation menu from all displayed lobby modules.
	 */
	public static function get_anchors_menu_view(): FileTemplate
	{
		$lang    = LangLoader::get_all_langs('lobby');
		$config  = LobbyConfig::load();
		$modules = LobbyModulesList::load();

		$view = new FileTemplate('lobby/pagecontent/anchors-menu.tpl');
		$view->add_lang($lang);

		$built_in_with_lang = [
			LobbyConfig::MODULE_EDITO,
			LobbyConfig::MODULE_LASTCOMS
		];

		foreach ($config->get_modules() as $key => $module_data)
		{
			$module_id = $module_data['module_id'];

			// Skip anchors menu itself and carousel
			if (in_array($module_id, [LobbyConfig::MODULE_ANCHORS_MENU, LobbyConfig::MODULE_CAROUSEL]))
			{
				continue;
			}

			if ($module_data['displayed'] != 1)
			{
				continue;
			}

			// Check the module is installed and active when it is a real module
			if (!in_array($module_id, [LobbyConfig::MODULE_EDITO, LobbyConfig::MODULE_LASTCOMS]))
			{
				$phpboost_id = !empty($module_data['phpboost_module_id']) ? $module_data['phpboost_module_id'] : $module_id;
				if (!ModulesManager::is_module_installed($phpboost_id) || !ModulesManager::is_module_activated($phpboost_id))
				{
					continue;
				}
			}

			// Determine the display title
			if (!empty($module_data['has_category']) && !empty($module_data['id_category']) && $module_data['id_category'] != Category::ROOT_CATEGORY)
			{
				$phpboost_id  = $module_data['phpboost_module_id'] ?? $module_id;
				$category     = CategoriesService::get_categories_manager($phpboost_id)->get_categories_cache()->get_category((int) $module_data['id_category']);
			}

			$is_category = !empty($module_data['has_category']) && !empty($module_data['id_category']) && $module_data['id_category'] != Category::ROOT_CATEGORY;

			$view->assign_block_vars('tabs', [
				'C_CATEGORY'      => $is_category,
				'C_DISPLAYED_TAB' => (bool) $module_data['displayed'],
				'TAB_POSITION'    => $config->get_module_position_by_id($module_id),
				'U_TAB'           => '#' . $module_id,
				'TAB_TITLE'       => $is_category && isset($category) ? ModulesManager::get_module($module_data['phpboost_module_id'])->get_configuration()->get_name() : $module_data['module_name'],
				'TAB_CATEGORY'    => $is_category && isset($category) ? $category->get_name() : '',
			]);
		}

		$view->put_all([
			'MENU_POSITION' => $config->get_module_position_by_id(LobbyConfig::MODULE_ANCHORS_MENU),
		]);

		return $view;
	}
}
?>
