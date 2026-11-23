<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 6.0 - 2019 12 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ModulesUrlBuilder
{
	protected static function get_module_id(string $module_id = ''): string
	{
		return !empty($module_id) ? $module_id : Environment::get_running_module_name();
	}

	protected static function get_dispatcher(string $module_id = ''): string
	{
		return '/' . self::get_module_id($module_id);
	}

	public static function admin(string $module_id = ''): Url
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/admin/');
	}

	public static function configuration(string $module_id = ''): Url
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/admin/config/');
	}

	public static function home(string $module_id = ''): Url
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/');
	}
}
?>
