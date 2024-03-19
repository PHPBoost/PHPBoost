<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 03 13
 * @since       PHPBoost 3.0 - 2011 04 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminThemesInstalledListController extends DefaultAdminController
{
	protected function get_template_to_use()
	{
        return new FileTemplate('admin/themes/AdminThemesInstalledListController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_view();
		$this->save($request);

		return new AdminThemesDisplayResponse($this->view, $this->lang['addon.themes.installed']);
	}

	private function build_view()
	{
		$phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
		$installed_themes = ThemesManager::get_installed_themes_map_sorted_by_localized_name();
		$selected_theme_number = 0;
		$theme_number = 1;
		foreach($installed_themes as $theme)
		{
			$configuration = $theme->get_configuration();
			$theme_has_parent = $configuration->get_parent_theme() != '' && $configuration->get_parent_theme() != '__default__';

            $authorizations = $theme->get_authorizations();
			$author_email = $configuration->get_author_mail();
			$author_website = $configuration->get_author_link();
			$pictures = $configuration->get_pictures();

			$this->view->assign_block_vars('themes_installed', array(
				'C_AUTHOR_EMAIL'       => !empty($author_email),
				'C_AUTHOR_WEBSITE'     => !empty($author_website),
				'C_COMPATIBLE'         => $configuration->get_addon_type() == 'theme' && $configuration->get_compatibility() == $phpboost_version && ($theme_has_parent ? ThemesManager::get_theme_existed($configuration->get_parent_theme()) : true),
				'C_COMPATIBLE_ADDON'   => $configuration->get_addon_type() == 'theme',
				'C_COMPATIBLE_VERSION' => $configuration->get_compatibility() == $phpboost_version,
				'C_IS_DEFAULT_THEME'   => $theme->get_id() == ThemesManager::get_default_theme(),
				'C_IS_ACTIVATED'       => $theme->is_activated(),
				'C_THUMBNAILS'         => count($pictures) > 0,
				'C_PARENT_THEME'       => $configuration->get_parent_theme() != '' && $configuration->get_parent_theme() != '__default__',

				'THEME_NUMBER'   => $theme_number,
				'MODULE_ID'      => $theme->get_id(),
				'MODULE_NAME'    => $configuration->get_name(),
				'CREATION_DATE'  => $configuration->get_creation_date(),
				'LAST_UPDATE'    => $configuration->get_last_update(),
				'VERSION'        => $configuration->get_version(),
				'AUTHOR'         => $configuration->get_author_name(),
				'AUTHOR_EMAIL'   => $author_email,
				'AUTHOR_WEBSITE' => $author_website,
				'DESCRIPTION'    => $configuration->get_description() !== '' ? $configuration->get_description() : $this->lang['common.unspecified'],
				'COMPATIBILITY'  => $configuration->get_compatibility(),
				'AUTHORIZATIONS' => Authorizations::generate_select(Theme::ACCES_THEME, $authorizations, array( 2 => true), $theme->get_id()),
				'HTML_VERSION'   => $configuration->get_html_version() !== '' ? $configuration->get_html_version() : $this->lang['common.unspecified'],
				'CSS_VERSION'    => $configuration->get_css_version() !== '' ? $configuration->get_css_version() : $this->lang['common.unspecified'],
				'MAIN_COLOR'     => $configuration->get_main_color() !== '' ? $configuration->get_main_color() : $this->lang['common.unspecified'],
				'WIDTH'          => $configuration->get_variable_width() ? $this->lang['addon.themes.variable.width'] : $configuration->get_width(),
				'PARENT_THEME'   => $configuration->get_parent_theme() != '' && $configuration->get_parent_theme() != '__default__' ? (ThemesManager::get_theme_existed($configuration->get_parent_theme()) ? ThemesManager::get_theme($configuration->get_parent_theme())->get_configuration()->get_name() : $configuration->get_parent_theme()) : '',

				'U_MAIN_THUMBNAIL' => count($pictures) > 0 ? Url::to_rel('/templates/' . $theme->get_id() . '/' . current($pictures)) : '',
			));

			if (count($pictures) > 0)
			{
				unset($pictures[0]);
				foreach ($pictures as $picture)
				{
					$url = '/templates/' . (!preg_match('/\/default\//', $picture) ? $theme->get_id() . '/' : '') . $picture;
					$this->view->assign_block_vars('themes_installed.pictures', array(
						'U_THUMBNAIL' => Url::to_rel($url)
					));
				}
			}

			if ($theme->get_id() == ThemesManager::get_default_theme())
				$default_theme_number = $theme_number;

			$theme_number++;
		}

		$installed_themes_number = count($installed_themes);
		$this->view->put_all(array(
			'C_SEVERAL_THEMES_INSTALLED' => $installed_themes_number > 1,

			'THEMES_NUMBER'        => $installed_themes_number,
			'DEFAULT_THEME_NUMBER' => $default_theme_number
		));
	}

	public function save(HTTPRequestCustom $request)
	{
		$installed_themes = ThemesManager::get_installed_themes_map_sorted_by_localized_name();

		if ($request->get_string('delete-selected-themes', false))
		{
			$theme_ids = array();
			$theme_number = 1;
			foreach ($installed_themes as $theme)
			{
				if ($request->get_value('delete-checkbox-' . $theme_number, 'off') == 'on')
				{
					$theme_ids[] = $theme->get_id();
				}
				$theme_number++;
			}

			$number_ids = count($theme_ids);
			if ($number_ids > 1)
			{
				$temporary_file = PATH_TO_ROOT . '/cache/themes_to_delete.txt';
				$file = new File($temporary_file);
				$file->write(implode(',', $theme_ids));
				$id = 'delete_multiple';
			}
			else
				$id = $number_ids ? $theme_ids[0] : '';

			if ($number_ids)
				AppContext::get_response()->redirect(AdminThemeUrlBuilder::delete_theme($id));
		}
		elseif ($request->get_string('activate-selected-themes', false) || $request->get_string('deactivate-selected-themes', false))
		{
			$activated = 0;
			if ($request->get_string('activate-selected-themes', false))
				$activated = 1;

			$theme_number = 1;
			foreach ($installed_themes as $theme)
			{
				if ($theme->get_id() !== ThemesManager::get_default_theme() && ($request->get_value('delete-checkbox-' . $theme_number, 'off') == 'on') )
				{
					$authorizations = Authorizations::auth_array_simple(Theme::ACCES_THEME, $theme->get_id());
					ThemesManager::change_informations($theme->get_id(), $activated, $authorizations);
				}
				$theme_number++;
			}
			AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme(), $this->lang['warning.process.success']);
		}
		else
		{
			foreach ($installed_themes as $theme)
			{
				if ($request->get_string('default-' . $theme->get_id(), ''))
				{
					$authorizations = Authorizations::auth_array_simple(Theme::ACCES_THEME, $theme->get_id());
					ThemesManager::change_informations($theme->get_id(), 1, $authorizations);

					$user_accounts_config = UserAccountsConfig::load();
					$user_accounts_config->set_default_theme($theme->get_id());
					UserAccountsConfig::save();

					AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme(), $this->lang['warning.process.success']);
				}
				else if ($request->get_string('delete-' . $theme->get_id(), ''))
				{
					AppContext::get_response()->redirect(AdminThemeUrlBuilder::delete_theme($theme->get_id()));
				}
				else if ($request->get_string('enable-' . $theme->get_id(), ''))
				{
					$authorizations = Authorizations::auth_array_simple(Theme::ACCES_THEME, $theme->get_id());
					ThemesManager::change_informations($theme->get_id(), 1, $authorizations);

					AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme(), $this->lang['warning.process.success']);
				}
				else if ($request->get_string('disable-' . $theme->get_id(), ''))
				{
					$authorizations = Authorizations::auth_array_simple(Theme::ACCES_THEME, $theme->get_id());
					ThemesManager::change_informations($theme->get_id(), 0, $authorizations);

					AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme(), $this->lang['warning.process.success']);
				}
			}
		}

		if ($request->get_bool('update', false))
		{
			foreach ($installed_themes as $theme)
			{
				if ($theme->get_id() !== ThemesManager::get_default_theme())
				{
					$authorizations = Authorizations::auth_array_simple(Theme::ACCES_THEME, $theme->get_id());
					ThemesManager::change_informations($theme->get_id(), $theme->is_activated(), $authorizations);
				}
			}
			AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme(), $this->lang['warning.process.success']);
		}
	}
}
?>
