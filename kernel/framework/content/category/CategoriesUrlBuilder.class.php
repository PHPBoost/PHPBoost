<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 20
 * @since       PHPBoost 5.3 - 2019 11 02
*/

class CategoriesUrlBuilder
{
	protected static $module_id;

	protected static function get_module_id($module_id = '')
	{
		if (self::$module_id === null || (!empty($module_id) && $module_id != self::$module_id))
			self::$module_id = !empty($module_id) ? $module_id : Environment::get_running_module_name();
		return self::$module_id;
	}

	protected static function get_dispatcher($module_id = '')
	{
		return '/' . self::get_module_id($module_id);
	}

	/**
	 * @return Url
	 */
	public static function manage_categories($module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/categories/');
	}

	/**
	 * @return Url
	 */
	public static function add_category($id_parent = null, $module_id = '')
	{
		$id_parent = !empty($id_parent) ? $id_parent . '/' : '';
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/categories/add/' . $id_parent);
	}

	/**
	 * @return Url
	 */
	public static function edit_category($id, $module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/categories/'. $id .'/edit/');
	}

	/**
	 * @return Url
	 */
	public static function delete_category($id, $module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/categories/'. $id .'/delete/');
	}

	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name, $module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/' . ($id > 0 ? $id . '-' . $rewrited_name .'/' : ''));
	}
}
?>
