<?php
/*##################################################
 *                      AdminThemesNotInstalledListController.class.php
 *                            -------------------
 *   begin                : April 20, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class AdminThemesNotInstalledListController extends AdminController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_view();

		return new AdminThemesDisplayResponse($this->view, $this->lang['themes.add']);
	}
	
	private function build_view()
	{
		$not_installed_themes = $this->get_not_installed_themes();
		foreach($not_installed_themes as $name)
		{
			$theme_configuration = ThemeConfigurationManager::get($name);
			
			$this->view->assign_block_vars('themes_not_installed', array(
				
			));
		}
		
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-themes-common');
		$this->view = new FileTemplate('admin/themes/AdminThemesNotInstalledListController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function get_not_installed_themes()
	{
		$installed_themes = ThemeManager::get_installed_themes_map();
		$themes_not_installed = array();
		$folder_containing_phpboost_themes = new Folder(PATH_TO_ROOT .'/templates/');
		foreach($folder_containing_phpboost_themes->get_folders() as $theme)
		{
			$name = $theme->get_name();
			if ($name !== 'default' && !ThemeManager::get_theme_existed($name))
			{
				$themes_not_installed[] = $name;
			}
		}
		sort($themes_not_installed);
		return $themes_not_installed;
	}
}
?>