<?php
/*##################################################
 *		                         MediaTreeLinks.class.php
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
class MediaTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		global $MEDIA_LANG;
		load_module_lang('media'); //Chargement de la langue du module.
		require_once(PATH_TO_ROOT . '/media/media_constant.php');
		$authorizations = MediaConfig::load()->get_authorizations();
		
		$tree = new ModuleTreeLinks();
		
		$manage_categories_link = new AdminModuleLink($MEDIA_LANG['admin.categories.manage'], PATH_TO_ROOT . Url('/media/admin_media_cats.php'));
		$manage_categories_link->add_sub_link(new AdminModuleLink($MEDIA_LANG['admin.categories.manage'], PATH_TO_ROOT . Url('/media/admin_media_cats.php')));
		$manage_categories_link->add_sub_link(new AdminModuleLink($MEDIA_LANG['add_cat'], PATH_TO_ROOT . Url('/media/admin_media_cats.php?new=1')));
		$tree->add_link($manage_categories_link);
		
		$manage_media_link = new AdminModuleLink($MEDIA_LANG['medias.manage'], PATH_TO_ROOT . Url('/media/moderation_media.php'));
		$manage_media_link->add_sub_link(new AdminModuleLink($MEDIA_LANG['medias.manage'], PATH_TO_ROOT . Url('/media/moderation_media.php')));
		$manage_media_link->add_sub_link(new AdminModuleLink($MEDIA_LANG['add_media'], PATH_TO_ROOT . Url('/media/media_action.php')));
		$tree->add_link($manage_media_link);
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), PATH_TO_ROOT . Url('/media/admin_media_config.php')));
		
		if (!AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$tree->add_link(new ModuleLink($MEDIA_LANG['add_media'], PATH_TO_ROOT . Url('/media/media_action.php'), AppContext::get_current_user()->check_auth($authorizations, MEDIA_AUTH_WRITE) || AppContext::get_current_user()->check_auth($authorizations, MEDIA_AUTH_CONTRIBUTION)));
		}
		
		return $tree;
	}
}
?>