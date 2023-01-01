<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 02 18
 * @since       PHPBoost 6.0 - 2019 12 20
*/

class ModulesUrlBuilder
{
	protected static function get_module_id($module_id = '')
	{
		return !empty($module_id) ? $module_id : Environment::get_running_module_name();
	}

	protected static function get_dispatcher($module_id = '')
	{
		return '/' . self::get_module_id($module_id);
	}

	/**
	 * @return Url
	 */
	public static function admin($module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/admin/');
	}

	/**
	 * @return Url
	 */
	public static function configuration($module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/admin/config/');
	}

	/**
	 * @return Url
	 */
	public static function home($module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/');
	}
}
?>
