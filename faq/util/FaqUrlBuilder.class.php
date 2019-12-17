<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 08
 * @since       PHPBoost 4.0 - 2014 09 02
*/

class FaqUrlBuilder
{
	private static $dispatcher = '/faq';

	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}

	/**
	 * @return Url
	 */
	public static function manage()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name, $subcategories_page = 1)
	{
		$category = $id > 0 ? $id . '-' . $rewrited_name . '/' : '';
		$subcategories_page = $subcategories_page !== 1 ? $subcategories_page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category . $subcategories_page);
	}

	/**
	 * @return Url
	 */
	public static function reorder_questions($id_category, $rewrited_name)
	{
		$category = $id_category > 0 ? $id_category . '-' . $rewrited_name . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/reorder/' . $category);
	}

	/**
	 * @return Url
	 */
	public static function display_pending($sort_field = '', $sort_mode = '')
	{
		$config = FaqConfig::load();
		$sort_field = $sort_field !== $config->get_items_default_sort_field() ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== $config->get_items_default_sort_mode() ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $sort_field . $sort_mode);
	}

	/**
	 * @return Url
	 */
	public static function add($id_category = null)
	{
		$id_category = !empty($id_category) ? $id_category . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category);
	}

	/**
	 * @return Url
	 */
	public static function edit($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}

	/**
	 * @return Url
	 */
	public static function delete($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function display($id_category, $rewrited_name_category, $id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/#question' . $id);
	}

	/**
	 * @return Url
	 */
	public static function ajax_delete()
	{
		return DispatchManager::get_url(self::$dispatcher, '/ajax_delete/');
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
