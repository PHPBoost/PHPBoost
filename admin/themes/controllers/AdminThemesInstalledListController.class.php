<?php
/*##################################################
 *                      AdminThemesInstalledListController.class.php
 *                            -------------------
 *   begin                : April 20, 2011
 *   copyright            : (C) 2011 K�vin MASSY
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

class AdminThemesInstalledListController extends AdminController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view();
		$this->save($request);

		return new AdminThemesDisplayResponse($this->view, $this->lang['themes.installed_theme']);
	}
	
	private function build_view()
	{
		$installed_themes = ThemesManager::get_installed_themes_map();
		foreach($installed_themes as $theme)
		{
			$configuration = $theme->get_configuration();
			$authorizations = $theme->get_authorizations();
			$default_theme = ThemesManager::get_default_theme();
			$pictures = $configuration->get_pictures();
			
			$this->view->assign_block_vars('themes_installed', array(
				'C_IS_DEFAULT_THEME' => $theme->get_id() == $default_theme,
				'C_IS_ACTIVATED' => $theme->is_activated(),
				'C_WEBSITE' => $configuration->get_author_link() !== '',
				'C_PICTURES' => count($pictures) > 0,
				'ID' => $theme->get_id(),
				'NAME' => $configuration->get_name(),
				'VERSION' => $configuration->get_version(),
				'MAIN_PICTURE' => count($pictures) > 0 ? Url::to_rel('/templates/' . $theme->get_id() . '/' . current($pictures)) : '',
				'AUTHOR_NAME' => $configuration->get_author_name(),
				'AUTHOR_WEBSITE' => $configuration->get_author_link(),
				'AUTHOR_EMAIL' => $configuration->get_author_mail(),
				'DESCRIPTION' => $configuration->get_description() !== '' ? $configuration->get_description() : $this->lang['themes.bot_informed'],
				'COMPATIBILITY' => $configuration->get_compatibility(),
				'AUTHORIZATIONS' => Authorizations::generate_select(Theme::ACCES_THEME, $authorizations, array(2 => true), $theme->get_id()),
				'HTML_VERSION' => $configuration->get_html_version() !== '' ? $configuration->get_html_version() : $this->lang['themes.bot_informed'],
				'CSS_VERSION' => $configuration->get_css_version() !== '' ? $configuration->get_css_version() : $this->lang['themes.bot_informed'],
				'MAIN_COLOR' => $configuration->get_main_color() !== '' ? $configuration->get_main_color() : $this->lang['themes.bot_informed'],
				'WIDTH' => $configuration->get_variable_width() ? $this->lang['themes.variable-width'] : $configuration->get_width()
			));
			
			if (count($pictures) > 0)
			{
				unset($pictures[0]);
				foreach ($pictures as $picture)
				{
					$this->view->assign_block_vars('themes_installed.pictures', array(
						'URL' => Url::to_rel('/templates/' . $theme->get_id() . '/' . $picture)
					));
				}
			}
		}
		
		$this->view->put_all(array(
			'L_DELETE' => LangLoader::get_message('delete','common'),
			'L_RESET' => LangLoader::get_message('reset','main'),
			'L_UPDATE' => LangLoader::get_message('update','main')
		));
	}
	
	public function save(HTTPRequestCustom $request)
	{
		$installed_themes = ThemesManager::get_installed_themes_map();
		
		foreach ($installed_themes as $theme)
		{
			if ($request->get_string('delete-' . $theme->get_id(), ''))
			{
				AppContext::get_response()->redirect(AdminThemeUrlBuilder::delete_theme($theme->get_id()));
			}
		}
		
		if ($request->get_bool('update', false))
		{
			foreach ($installed_themes as $theme)
			{
				if ($theme->get_id() !== ThemesManager::get_default_theme())
				{
					$id_theme = $theme->get_id();
					$activated = $request->get_bool('activated-' . $id_theme, false);
					$authorizations = Authorizations::auth_array_simple(Theme::ACCES_THEME, $id_theme);
					ThemesManager::change_informations($id_theme, $activated, $authorizations);
				}
			}
			AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme());
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