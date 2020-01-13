<?php
/**
 * @package     Content
 * @subpackage  Item
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 13
 * @since       PHPBoost 5.3 - 2019 12 20
*/

class ItemsUrlBuilder
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
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/manage/');
	}

	/**
	 * @return Url
	 */
	public static function add($id_category = null, $module_id = '')
	{
		$id_category = !empty($id_category) ? $id_category . '/' : '';
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/add/' . $id_category);
	}

	/**
	 * @return Url
	 */
	public static function edit($id, $module_id = '', $additional_parameter = null)
	{
		$additional_parameter = !empty($additional_parameter) ? (is_int($additional_parameter) && $additional_parameter > 1 ? $additional_parameter . '/' : (!is_int($additional_parameter) ? $additional_parameter . '/' : '')) : '';
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/'. $id .'/edit/' . $additional_parameter);
	}

	/**
	 * @return Url
	 */
	public static function delete($id, $module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/' . $id . '/delete/?token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function display($id_category, $rewrited_name_category, $id, $rewrited_name, $anchor = '', $module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/' . $id_category . '-' . $rewrited_name_category . '/' . $id . '-' . $rewrited_name . '/' . $anchor);
	}
	/**
	 * @return Url
	 */
	public static function display_item($id, $rewrited_title, $anchor = '', $module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/' . $id . '-' . $rewrited_title . '/' . $anchor);
	}

	/**
	 * @return Url
	 */
	public static function display_comments($id_category, $rewrited_name_category, $id, $rewrited_title, $module_id = '')
	{
		return self::display($id_category, $rewrited_name_category, $id, $rewrited_title, '#comments-list', $module_id);
	}

	/**
	 * @return Url
	 */
	public static function display_item_comments($id, $rewrited_title, $module_id = '')
	{
		return self::display_item($id, $rewrited_title, '#comments-list', $module_id);
	}

	/**
	 * @return Url
	 */
	public static function display_pending($module_id = '')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/pending/');
	}
}
?>
