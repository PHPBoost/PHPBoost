<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\tree-links
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 20
 * @since       PHPBoost 4.1 - 2013 11 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ModuleTreeLinksService
{
	public static function display_actions_menu()
	{
		$module_name = Environment::get_running_module_name();
		$tree_links = self::get_tree_links($module_name);
		if ($tree_links !== null)
		{
			$actions_tree_links = $tree_links->get_actions_tree_links();
			$tpl = new FileTemplate('framework/module/module_actions_links_menu.tpl');

			$module = ModulesManager::get_module($module_name);

			$tpl->put_all(array(
				'C_DISPLAY' => $actions_tree_links->has_visible_links(),
				'ID' => $module_name,
				'MODULE_NAME' => $module_name != 'user' ? $module->get_configuration()->get_name() : LangLoader::get_message('users', 'user-common'),
			));

			if ($module_name != 'user')
			{
				$home_page = $module->get_configuration()->get_home_page();
				if (!empty($home_page))
				{
					$module_home = new ModuleLink(LangLoader::get_message('home', 'main'), new Url('/' . $module->get_id() . '/' . $home_page));
					$tpl->assign_block_vars('element', array(), array(
						'ELEMENT' => $module_home->export()
					));
				}
			}

			return self::display($actions_tree_links, $tpl);
		}
	}

	public static function display_admin_actions_menu(Module $module)
	{
		$id_module = $module->get_id();
		$configuration = $module->get_configuration();
		$admin_main_page = $configuration->get_admin_main_page();

		$tpl = new FileTemplate('framework/module/admin_module_actions_links_menu.tpl');
		$tpl->put_all(array(
			'U_LINK' => TPL_PATH_TO_ROOT . '/' . $id_module . '/' . $configuration->get_admin_main_page(),
			'NAME' => $configuration->get_name(),
			'IMG' => TPL_PATH_TO_ROOT . '/' . $id_module . '/' . $id_module . '_mini.png',
			'C_HAS_SUB_LINK' => false,
			'C_DISPLAY' => !empty($admin_main_page)
		));

		$tree_links = self::get_tree_links($id_module);
		if ($tree_links !== null)
		{
			$actions_tree_links = $tree_links->get_actions_tree_links();

			$tpl->put_all(array(
				'C_HAS_SUB_LINK' => $actions_tree_links->has_links(),
				'C_DISPLAY' => $actions_tree_links->has_links() || !empty($admin_main_page)
			));

			$home_page = $configuration->get_home_page();
			if (!empty($home_page))
			{
				$module_home = new ModuleLink(LangLoader::get_message('home', 'main'), new Url('/' . $id_module . '/' . $home_page));
				$tpl->assign_block_vars('element', array(), array(
					'ELEMENT' => $module_home->export()
				));
			}

			return self::display($actions_tree_links, $tpl);
		}

		return $tpl;
	}

	public static function display(ModuleTreeLinks $tree_links, View $view)
	{
		$has_links = $tree_links->has_links();
		if ($has_links)
		{
			$links = $tree_links->get_links();
			foreach ($links as $element)
			{
				if ($element->is_visible())
				{
					$view->assign_block_vars('element', array(), array(
						'ELEMENT' => $element->export()
					));
				}
			}
		}

		return $view;
	}

	public static function get_tree_links($module_name)
	{
		try {
			return AppContext::get_extension_provider_service()->get_provider($module_name)->get_extension_point(ModuleTreeLinksExtensionPoint::EXTENSION_POINT);
		} catch (UnexistingExtensionPointProviderException $e) {
			return null;
		} catch (ExtensionPointNotFoundException $e) {
			return null;
		}
	}
}
?>
