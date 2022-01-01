<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 6.0 - 2019 11 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
	public static function manage($module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/categories/');
	}

	/**
	 * @return Url
	 */
	public static function add($id_parent = null, $module_id = '')
	{
		$id_parent = !empty($id_parent) ? $id_parent . '/' : '';
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/categories/add/' . $id_parent);
	}

	/**
	 * @return Url
	 */
	public static function edit($id, $module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/categories/'. $id .'/edit/');
	}

	/**
	 * @return Url
	 */
	public static function delete($id, $module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/categories/'. $id .'/delete/');
	}

	/**
	 * @return Url
	 */
	public static function display($id, $rewrited_name, $module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/' . ($id > 0 ? $id . '-' . $rewrited_name .'/' : ''));
	}
}
?>
