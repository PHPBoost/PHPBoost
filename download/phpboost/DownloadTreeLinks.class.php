<?php
/*##################################################
 *		                         DownloadTreeLinks.class.php
 *                            -------------------
 *   begin                : November 23, 2013
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
class DownloadTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		global $DOWNLOAD_LANG, $Cache, $DOWNLOAD_CACHE;
		load_module_lang('download'); //Chargement de la langue du module.
		$Cache->load('download');
		
		$tree = new ModuleTreeLinks();
		
		$manage_categories_link = new AdminModuleLink($DOWNLOAD_LANG['admin.categories.manage'], PATH_TO_ROOT . Url('/download/admin_download_cat.php'));
		$manage_categories_link->add_sub_link(new AdminModuleLink($DOWNLOAD_LANG['admin.categories.manage'], PATH_TO_ROOT . Url('/download/admin_download_cat.php')));
		$manage_categories_link->add_sub_link(new AdminModuleLink($DOWNLOAD_LANG['add_category'], PATH_TO_ROOT . Url('/download/admin_download_cat.php?new=1')));
		$tree->add_link($manage_categories_link);
		
		$manage_faq_link = new AdminModuleLink($DOWNLOAD_LANG['files.manage'], PATH_TO_ROOT . Url('/download/admin_download.php'));
		$manage_faq_link->add_sub_link(new AdminModuleLink($DOWNLOAD_LANG['files.manage'], PATH_TO_ROOT . Url('/download/admin_download.php')));
		$manage_faq_link->add_sub_link(new AdminModuleLink($DOWNLOAD_LANG['add_file'], PATH_TO_ROOT . Url('/download/management.php?new=1')));
		$tree->add_link($manage_faq_link);
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), PATH_TO_ROOT . Url('/download/admin_download_config.php')));
		
		if (!AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$tree->add_link(new ModuleLink($DOWNLOAD_LANG['add_file'], PATH_TO_ROOT . Url('/download/management.php?new=1'), AppContext::get_current_user()->check_auth(DownloadConfig::load()->get_authorizations(), DOWNLOAD_WRITE_CAT_AUTH_BIT)));
		}
		
		return $tree;
	}
}
?>