<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 05
 * @since       PHPBoost 6.0 - 2019 11 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CategoriesUrlBuilder
{
	protected static string $module_id = '';

	protected static function get_module_id(string $module_id = ''): string
	{
		if (self::$module_id === null || (!empty($module_id) && $module_id != self::$module_id))
			self::$module_id = !empty($module_id) ? $module_id : Environment::get_running_module_name();
		return self::$module_id;
	}

	protected static function get_dispatcher(string $module_id = ''): string
	{
		return '/' . self::get_module_id($module_id);
	}

	public static function manage(string $module_id = ''): Url
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/categories/');
	}

	public static function add(int $id_parent = 0, string $module_id = ''): Url
	{
		$id_parent = !empty($id_parent) ? $id_parent . '/' : '';
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/categories/add/' . $id_parent);
	}

	public static function edit(int $id, string $module_id = ''): Url
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/categories/'. $id .'/edit/');
	}

	public static function delete(int $id, string $module_id = ''): Url
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/categories/'. $id .'/delete/');
	}

	public static function display(int $id, string $rewrited_name, string $module_id = ''): Url
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/' . ($id > 0 ? $id . '-' . $rewrited_name .'/' : ''));
	}
}
?>
