<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\css
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 01
 * @since       PHPBoost 3.0 - 2011 10 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ModulesCssFilesService
{
	private static $modules_css_files = array();

	public static function __static()
	{
		$extension_points = AppContext::get_extension_provider_service()->get_extension_point(CssFilesExtensionPoint::EXTENSION_POINT);
		foreach ($extension_points as $module_id => $provider)
		{
			self::$modules_css_files[$module_id] = $provider;
		}
	}

	public static function get_css_files_always_displayed()
	{
		$css_files = array();
		foreach (self::$modules_css_files as $module_id => $module_css_files)
		{
			foreach ($module_css_files->get_css_files_always_displayed() as $css_file)
			{
				$css_files[] = self::get_real_path_css_file($module_id, $css_file);
			}
		}
		return $css_files;
	}

	public static function get_css_files_running_module_displayed()
	{
		$css_files = array();
		$module_id = Environment::get_running_module_name();
		if (array_key_exists($module_id, self::$modules_css_files))
		{
			foreach (self::$modules_css_files[$module_id]->get_css_files_running_module_displayed() as $css_file_options)
			{
				if (!empty($css_file_options['css_file']))
				{
					$module = !empty($css_file_options['module_id']) ? $css_file_options['module_id'] : Environment::get_running_module_name();
					$css_files[] = self::get_real_path_css_file($module, $css_file_options['css_file']);
				}
			}
		}
		return $css_files;
	}

	private static function get_real_path_css_file($module_id, $css_file)
	{
		if (filter_var($css_file, FILTER_VALIDATE_URL))
		{
			return $css_file;
		}
		if (strpos($css_file, '/') !== false)
		{
			return $css_file;
		}
		
		$theme_id = AppContext::get_current_user()->get_theme();
		if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/' . $module_id . '/' . $css_file))
		{
			return '/templates/' . $theme_id . '/modules/' . $module_id . '/' . $css_file;
		}
		return '/' . $module_id . '/templates/' . $css_file;
	}
}
?>
