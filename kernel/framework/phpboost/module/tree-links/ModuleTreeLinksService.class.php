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
	public static function display_actions_links_menu()
	{
		return self::display(self::get_tree_links(), new FileTemplate('framework/module/module_actions_links_menu.tpl'));
	}
	
	public static function display(ModuleTreeLinks $tree_links, View $view)
	{
		foreach ($tree_links->get_links() as $element)
		{
			if ($element->is_visible())
			{
				$view->assign_block_vars('element', array(), array(
					'ELEMENT' => $element->export()
				));
			}
		}
		
		$view->put('C_DISPLAY', $tree_links->has_links());
		
		return $view;
	}
	
	public static function get_tree_links()
	{
		try {
			return AppContext::get_extension_provider_service()->get_provider(Environment::get_running_module_name())->get_extension_point(ModuleTreeLinksExtensionPoint::EXTENSION_POINT)->get_actions_tree_links();
		} catch (UnexistingExtensionPointProviderException $e) {
			return new ModuleTreeLinks();
		}
		catch (ExtensionPointNotFoundException $e) {
			return new ModuleTreeLinks();
		}
	}
}
?>