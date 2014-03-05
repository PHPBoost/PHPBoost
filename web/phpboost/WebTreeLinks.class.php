<?php
/*##################################################
 *		                         WebTreeLinks.class.php
 *                            -------------------
 *   begin                : November 24, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class WebTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		global $LANG;
		load_module_lang('web'); //Chargement de la langue du module.
		
		$tree = new ModuleTreeLinks();
		
		$manage_categories_link = new AdminModuleLink($LANG['admin.categories.manage'], new Url('/web/admin_web_cat.php'));
		$manage_categories_link->add_sub_link(new AdminModuleLink($LANG['admin.categories.manage'], new Url('/web/admin_web_cat.php')));
		$manage_categories_link->add_sub_link(new AdminModuleLink(LangLoader::get_message('cat_add', 'admin'), new Url('/web/admin_web_cat.php#add_cat')));
		$tree->add_link($manage_categories_link);
		
		$manage_web_link = new AdminModuleLink($LANG['links.manage'], new Url('/web/admin_web.php'));
		$manage_web_link->add_sub_link(new AdminModuleLink($LANG['links.manage'], new Url('/web/admin_web.php')));
		$manage_web_link->add_sub_link(new AdminModuleLink($LANG['web_add'], new Url('/web/admin_web_add.php')));
		$tree->add_link($manage_web_link);
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), new Url('/web/admin_web_config.php')));
		
		return $tree;
	}
}
?>