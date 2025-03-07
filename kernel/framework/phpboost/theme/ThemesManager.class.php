<?php
/**
 * @package     PHPBoost
 * @subpackage  Theme
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 07 06
 * @since       PHPBoost 3.0 - 2011 04 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ThemesManager
{
	private static $error = null;

	public static function get_installed_themes_map()
	{
		$themes = array();
		foreach (ThemesConfig::load()->get_themes() as $theme) {
			$themes[$theme->get_id()] = $theme;
		}
		ksort($themes);
		return $themes;
	}

	/**
	 * @return Theme[string] the Themes map (name => theme) of the installed themes (activated or not)
	 * sorted by name
	 */
	public static function get_installed_themes_map_sorted_by_localized_name()
	{
		$themes = self::get_installed_themes_map();
		try {
			uasort($themes, array(__CLASS__, 'callback_sort_themes_by_name'));
		} catch (IOException $ex) {
		}
		return $themes;
	}

	public static function get_activated_themes_map()
	{
		$activated_themes = array();
		foreach (ThemesConfig::load()->get_themes() as $theme) {
			if ($theme->is_activated()) {
				$activated_themes[$theme->get_id()] = $theme;
			}
		}
		ksort($activated_themes);
		return $activated_themes;
	}

	/**
	 * @return Theme[string] the Themes map (name => theme) of the installed themes (and activated)
	 * sorted by name
	 */
	public static function get_activated_themes_map_sorted_by_localized_name()
	{
		$themes = self::get_activated_themes_map();
		try {
			uasort($themes, array(__CLASS__, 'callback_sort_themes_by_name'));
		} catch (IOException $ex) {
		}
		return $themes;
	}

	public static function get_activated_and_authorized_themes_map()
	{
		$themes = array();
		foreach (ThemesConfig::load()->get_themes() as $theme) {
			if ($theme->is_activated() && $theme->check_auth()) {
				$themes[$theme->get_id()] = $theme;
			}
		}
		ksort($themes);
		return $themes;
	}

	/**
	 * @return Theme[string] the Themes map (name => theme) of the installed themes (and activated and authorized)
	 * sorted by name
	 */
	public static function get_activated_and_authorized_themes_map_sorted_by_localized_name()
	{
		$themes = self::get_activated_and_authorized_themes_map();
		try {
			uasort($themes, array(__CLASS__, 'callback_sort_themes_by_name'));
		} catch (IOException $ex) {
		}
		return $themes;
	}

	public static function callback_sort_themes_by_name(Theme $theme1, Theme $theme2)
	{
		if (TextHelper::strtolower($theme1->get_configuration()->get_name()) > TextHelper::strtolower($theme2->get_configuration()->get_name()))
		{
			return 1;
		}
		return -1;
	}

	public static function get_default_theme()
	{
		return UserAccountsConfig::load()->get_default_theme();
	}

	public static function get_theme($theme_id)
	{
		return ThemesConfig::load()->get_theme($theme_id);
	}

	public static function get_theme_existed($theme_id)
	{
		if (ThemesConfig::load()->get_theme($theme_id) !== null)
		{
			return true;
		}
		return false;
	}

	public static function install($theme_id, $authorizations = array(), $enable_theme = true)
	{
		if (!file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/config.ini'))
		{
			self::$error = LangLoader::get_message('warning.misfit.phpboost', 'warning-lang');
			$folder = new Folder(PATH_TO_ROOT . '/templates/' . $theme_id);
			$folder->delete();
		}
		else if (!empty($theme_id) && !self::get_theme_existed($theme_id))
		{
			$theme = new Theme($theme_id, $authorizations, $enable_theme);
			$configuration = $theme->get_configuration();
			$theme->set_columns_disabled($configuration->get_columns_disabled());

			$phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
			if (version_compare($phpboost_version, $configuration->get_compatibility(), '>'))
			{
				self::$error = LangLoader::get_message('warning.misfit.phpboost', 'warning-lang');
			}
			else if (!in_array($configuration->get_parent_theme(), self::get_themes_list()))
			{
				self::$error = StringVars::replace_vars(LangLoader::get_message('addon.themes.parent.theme.not.installed', 'addon-lang'), array('id_parent' => $configuration->get_parent_theme()));
			}
			else
			{
				ThemesConfig::load()->add_theme($theme);
				ThemesConfig::save();
			}
		}
		else
		{
			self::$error = LangLoader::get_message('warning.element.already.exists', 'warning-lang');
		}
	}

	public static function uninstall($theme_id, $drop_files = false)
	{
		if (!empty($theme_id) && self::get_theme_existed($theme_id))
		{
			$default_theme = self::get_default_theme();
			if (self::get_theme($theme_id)->get_id() !== $default_theme)
			{
				$theme_childs_list = self::get_theme_childs_list($theme_id);

				PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('theme' => $default_theme),
					'WHERE theme IN :old_theme', array('old_theme' => array_merge(array($theme_id), $theme_childs_list)
				));

				foreach ($theme_childs_list as $id)
				{
					ThemesConfig::load()->remove_theme_by_id($id);
				}
				ThemesConfig::load()->remove_theme_by_id($theme_id);
				ThemesConfig::save();

				if ($drop_files)
				{
					foreach ($theme_childs_list as $id)
					{
						$folder = new Folder(PATH_TO_ROOT . '/templates/' . $id);
						$folder->delete();
					}

					$folder = new Folder(PATH_TO_ROOT . '/templates/' . $theme_id);
					$folder->delete();
				}

				AppContext::get_cache_service()->clear_css_cache();
				AppContext::get_cache_service()->clear_js_cache();
				AppContext::get_cache_service()->clear_template_cache();
			}
		}
	}

	public static function change_visibility($theme_id, $visibility)
	{
		if (!empty($theme_id) && self::get_theme_existed($theme_id))
		{
			$theme = ThemesConfig::load()->get_theme($theme_id);
			$theme->set_activated($visibility);
			ThemesConfig::load()->update($theme);
			ThemesConfig::save();
		}
	}

	public static function change_authorizations($theme_id, Array $authorizations)
	{
		if (!empty($theme_id) && self::get_theme_existed($theme_id))
		{
			$theme = ThemesConfig::load()->get_theme($theme_id);
			$theme->set_authorizations($authorizations);
			ThemesConfig::load()->update($theme);
			ThemesConfig::save();
		}
	}

	public static function change_informations($theme_id, $visibility, Array $authorizations = array(), $columns_disabled = null)
	{
		if (!empty($theme_id) && self::get_theme_existed($theme_id))
		{
			$theme = ThemesConfig::load()->get_theme($theme_id);
			$theme->set_activated($visibility);

			if (!empty($authorizations))
			{
				$theme->set_authorizations($authorizations);
			}

			if ($columns_disabled !== null)
			{
				$theme->set_columns_disabled($columns_disabled);
			}

			ThemesConfig::load()->update($theme);
			ThemesConfig::save();
		}
	}

	public static function change_columns_disabled($theme_id, ColumnsDisabled $columns_disabled)
	{
		if (!empty($theme_id) && self::get_theme_existed($theme_id))
		{
			$theme = ThemesConfig::load()->get_theme($theme_id);
			$theme->set_columns_disabled($columns_disabled);
			ThemesConfig::load()->update($theme);
			ThemesConfig::save();
		}
	}

	public static function change_customize_interface($theme_id, CustomizeInterface $customize_interface)
	{
		if (!empty($theme_id) && self::get_theme_existed($theme_id))
		{
			$theme = ThemesConfig::load()->get_theme($theme_id);
			$theme->set_customize_interface($customize_interface);
			ThemesConfig::load()->update($theme);
			ThemesConfig::save();
		}
	}

	private static function get_themes_list()
	{
		$themes_list = array();
		$folder_containing_phpboost_themes = new Folder(PATH_TO_ROOT .'/templates/');
		foreach ($folder_containing_phpboost_themes->get_folders() as $folder)
		{
			$themes_list[] = $folder->get_name();
		}

		return $themes_list;
	}

	public static function get_theme_childs_list($theme_id)
	{
		$themes_childs_list = array();
		$folder_containing_phpboost_themes = new Folder(PATH_TO_ROOT .'/templates/');
		if (self::get_theme_existed($theme_id))
		{
			$installed_themes_list = array_keys(self::get_installed_themes_map());
			foreach ($folder_containing_phpboost_themes->get_folders() as $folder)
			{
				if ($folder->get_name() != '__default__')
				{
					$theme = new Theme($folder->get_name());
					if ($theme->get_configuration()->get_parent_theme() == $theme_id && in_array($folder->get_name(), $installed_themes_list))
						$themes_childs_list[] = $folder->get_name();
				}
			}
		}

		return $themes_childs_list;
	}

	public static function get_error()
	{
		return self::$error;
	}
}
?>
