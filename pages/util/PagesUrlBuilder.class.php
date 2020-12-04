<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 04
 * @since       PHPBoost 5.2 - 2020 06 15
*/

class PagesUrlBuilder
{
	private static $dispatcher = '/pages';

	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}

	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name)
	{
		$category = $id > 0 ? $id . '-' . $rewrited_name . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category);
	}

	/**
	 * @return Url
	 */
	public static function display_tag($rewrited_name)
	{
		return DispatchManager::get_url(self::$dispatcher, '/tag/' . $rewrited_name);
	}

	/**
	 * @return Url
	 */
	public static function display_pending()
	{
		return DispatchManager::get_url(self::$dispatcher, '/pending/');
	}

	/**
	 * @return Url
	 */
	public static function display_member_items()
	{
		return DispatchManager::get_url(self::$dispatcher, '/my_items/');
	}

	/**
	 * @return Url
	 */
	public static function manage_items()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	/**
	 * @return Url
	 */
	public static function reorder_items($id_category, $rewrited_name)
	{
		$category = $id_category > 0 ? $id_category . '-' . $rewrited_name . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/reorder/' . $category);
	}

	/**
	 * @return Url
	 */
	public static function add_item($id_category = null)
	{
		$id_category = !empty($id_category) ? $id_category . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category);
	}

	/**
	 * @return Url
	 */
	public static function edit_item($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}

	/**
	 * @return Url
	 */
	public static function delete_item($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function display_item($id_category, $rewrited_name_category, $id, $rewrited_name)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id . '-' . $rewrited_name . '/');
	}

	/**
	 * @return Url
	 */
	public static function display_comments($id_category, $rewrited_name_category, $id, $rewrited_name)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id . '-' . $rewrited_name . '/#comments-list');
	}

	/**
	 * @return Url
	 */
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
