<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
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
	public static function reorder_items($id_category, $rewrited_name)
	{
		$category = $id_category > 0 ? $id_category . '-' . $rewrited_name . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/reorder/' . $category);
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
