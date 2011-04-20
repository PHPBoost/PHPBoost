<?php
/*##################################################
 *                      AdminThemesInstalledListController.class.php
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

class AdminThemesInstalledListController extends AdminController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_view();

		return new AdminThemesDisplayResponse($this->view, $this->lang['themes.installed']);
	}
	
	private function build_view()
	{
		$installed_themes = ThemeManager::get_installed_themes_map();
		
		foreach ($installed_themes as $theme)
		{
			$configuration = $theme->get_configuration();
			$authorizations = $theme->get_authorizations();
			$is_activated = $theme->is_activated();
			$id = $theme->get_id();
			
			$this->view->assign_block_vars('themes_installed', array(
				
			));
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-themes-common');
		$this->view = new FileTemplate('admin/themes/AdminThemesInstalledListController.tpl');
		$this->view->add_lang($this->lang);
	}
}
?>