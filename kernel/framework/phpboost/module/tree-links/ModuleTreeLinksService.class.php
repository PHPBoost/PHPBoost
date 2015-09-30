<?php
/*##################################################
 *		                         ModuleTreeLinksService.class.php
 *                            -------------------
 *   begin                : November 15, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
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
			$module = ModulesManager::get_module($module_name);
			
			$tpl = new FileTemplate('framework/module/module_actions_links_menu.tpl');
			$tpl->put_all(array(
				'C_DISPLAY' => $actions_tree_links->has_visible_links(),
				'ID' => $module_name,
				'MODULE_NAME' => $module->get_configuration()->get_name(),
			));
			
			$home_page = $module->get_configuration()->get_home_page();
			if (!empty($home_page))
			{
				$module_home = new ModuleLink(LangLoader::get_message('home', 'main'), new Url('/' . $module->get_id() . '/' . $home_page));
				$tpl->assign_block_vars('element', array(), array(
					'ELEMENT' => $module_home->export()
				));
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