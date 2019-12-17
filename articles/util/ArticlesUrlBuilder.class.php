<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 02
 * @since       PHPBoost 4.0 - 2013 03 04
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ArticlesUrlBuilder
{
	private static $dispatcher = '/articles';

	public static function display_category($id, $rewrited_name, $sort_field = '', $sort_mode = '', $page = 1, $subcategories_page = 1)
	{
		$config = ArticlesConfig::load();
		$category = $id > 0 ? $id . '-' . $rewrited_name .'/' : '';
		$page = $page !== 1 || $subcategories_page !== 1 ? $page . '/': '';
		$subcategories_page = $subcategories_page !== 1 ? $subcategories_page . '/': '';
		$sort_field = $sort_field !== $config->get_items_default_sort_field() ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== $config->get_items_default_sort_mode() ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $category . $sort_field . $sort_mode . $page . $subcategories_page);
	}

	public static function manage_articles()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	public static function print_article($id_article, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/print/' . $id_article . '-' .$rewrited_title . '/');
	}

	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config/');
	}

	public static function add_article($id_category = null)
	{
		$id_category = !empty($id_category) ? $id_category . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category);
	}

	public static function edit_article($id, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/' . $page);
	}

	public static function delete_article($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}

	public static function display_article($id_category, $rewrited_name_category, $id_article, $rewrited_title, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id_article . '-' .$rewrited_title . '/' . $page);
	}

	public static function display_comments_article($id_category, $rewrited_name_category, $id_article, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id_article . '-' . $rewrited_title . '/#comments-list');
	}

	public static function display_pending_articles($sort_field = '', $sort_mode = '', $page = 1)
	{
		$config = ArticlesConfig::load();
		$page = $page !== 1 ? $page . '/': '';
		$sort_field = $sort_field !== $config->get_items_default_sort_field() ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== $config->get_items_default_sort_mode() ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/pending/' . $sort_field . $sort_mode . $page);
	}

	public static function display_tag($rewrited_name, $sort_field = '', $sort_mode = '', $page = 1)
	{
		$config = ArticlesConfig::load();
		$page = $page !== 1 ? $page . '/' : '';
		$sort_field = $sort_field !== $config->get_items_default_sort_field() ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== $config->get_items_default_sort_mode() ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/tag/'. $rewrited_name . '/' . $sort_field . $sort_mode . $page);
	}

	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
