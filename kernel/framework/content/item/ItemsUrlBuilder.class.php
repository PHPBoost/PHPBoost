<?php
/**
 * @package     Content
 * @subpackage  Item
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 6.0 - 2019 12 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ItemsUrlBuilder
{
	protected static function get_module_id(string $module_id = ''): string
	{
		return !empty($module_id) ? $module_id : Environment::get_running_module_name();
	}

	protected static function get_dispatcher(string $module_id = ''): string
	{
		return '/' . self::get_module_id($module_id);
	}

	public static function manage(string $module_id = ''): Url
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/manage/');
	}

	public static function add(?int $id_category = null, string $module_id = '', mixed $additional_parameter = null): Url
	{
		$id_category = !empty($id_category) ? $id_category . '/' : '';
		$additional_parameter = (!empty($additional_parameter) ? ((is_int($additional_parameter) && $additional_parameter > 1) || !is_int($additional_parameter) ? $additional_parameter . '/' : '') : '');
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/add/' . $id_category . $additional_parameter);
	}

	public static function edit(int $id, string $module_id = '', mixed $additional_parameter = null): Url
	{
		$additional_parameter = (!empty($additional_parameter) ? ((is_int($additional_parameter) && $additional_parameter > 1) || !is_int($additional_parameter) ? $additional_parameter . '/' : '') : '');
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/'. $id .'/edit/' . $additional_parameter);
	}

	public static function duplicate(int $id, string $module_id = '', mixed $additional_parameter = null): Url
	{
		$additional_parameter = (!empty($additional_parameter) ? ((is_int($additional_parameter) && $additional_parameter > 1) || !is_int($additional_parameter) ? $additional_parameter . '/' : '') : '');
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/'. $id .'/duplicate/' . $additional_parameter);
	}

	public static function delete(int $id, string $module_id = '', mixed $additional_parameter = null): Url
	{
		$additional_parameter = (!empty($additional_parameter) ? ((is_int($additional_parameter) && $additional_parameter > 1) || !is_int($additional_parameter) ? $additional_parameter . '/' : '') : '');
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/' . $id . '/delete/' . $additional_parameter . '?token=' . AppContext::get_session()->get_token());
	}

	public static function display(int $id_category, string $rewrited_name_category, int $id, string $rewrited_name, string $module_id = '', string $anchor = '', mixed $additional_parameter = null): Url
	{
		$additional_parameter = (!empty($additional_parameter) ? ((is_int($additional_parameter) && $additional_parameter > 1) || !is_int($additional_parameter) ? $additional_parameter . '/' : '') : '');
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/' . $id_category . '-' . $rewrited_name_category . '/' . $id . '-' . $rewrited_name . '/' . $additional_parameter . $anchor);
	}
	public static function display_item(int $id, string $rewrited_title, string $module_id = '', string $anchor = '', mixed $additional_parameter = null): Url
	{
		$additional_parameter = (!empty($additional_parameter) ? ((is_int($additional_parameter) && $additional_parameter > 1) || !is_int($additional_parameter) ? $additional_parameter . '/' : '') : '');
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/' . $id . '-' . $rewrited_title . '/' . $additional_parameter . $anchor);
	}

	public static function display_comments(int $id_category, string $rewrited_name_category, int $id, string $rewrited_title, string $module_id = ''): Url
	{
		return self::display($id_category, $rewrited_name_category, $id, $rewrited_title, $module_id, '#comments-list');
	}

	public static function display_item_comments(int $id, string $rewrited_title, string $module_id = ''): Url
	{
		return self::display_item($id, $rewrited_title, $module_id, '#comments-list');
	}

	public static function display_category(int $id, string $rewrited_name, string $module_id = '', string $sort_field = '', string $sort_mode = '', int|string $page = 1, int|string $subcategories_page = 1): Url
	{
		$category = $id > 0 || $sort_field ? $id . '-' . $rewrited_name . '/' : '';
		$sort_field = is_string($sort_field) && $sort_field !== '' ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== '' ? $sort_mode . '/' : '';
		$page = $page !== 1 || $subcategories_page !== 1 ? $page . '/' : '';
		$subcategories_page = $subcategories_page !== 1 ? $subcategories_page . '/' : '';
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/' . $category . $sort_field . $sort_mode . $page . $subcategories_page);
	}

	public static function display_tag(string $rewrited_name, string $module_id = '', string $sort_field = '', string $sort_mode = '', int $page = 1): Url
	{
		$sort_field = is_string($sort_field) && $sort_field !== '' ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== '' ? $sort_mode . '/' : '';
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/tag/' . $rewrited_name . '/' . $sort_field . $sort_mode . $page);
	}

	public static function display_pending(string $module_id = '', string $sort_field = '', string $sort_mode = '', int $page = 1): Url
	{
		$sort_field = is_string($sort_field) && $sort_field !== '' ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== '' ? $sort_mode . '/' : '';
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/pending/' . $sort_field . $sort_mode . $page);
	}

	public static function display_member_items(?int $user_id = null, string $module_id = '', string $sort_field = '', string $sort_mode = '', int $page = 1): Url
	{
		$sort_field = is_string($sort_field) && $sort_field !== '' ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== '' ? $sort_mode . '/' : '';
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/member/' . $user_id . '/' . $sort_field . $sort_mode . $page);
	}

	public static function specific_page(int $page_id, string $module_id = '', mixed $additional_parameters = []): Url
	{
		$additional_parameters = (!empty($additional_parameters) ? implode('/', $additional_parameters) . '/' : '');
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/' . $page_id . '/' . $additional_parameters);
	}
}
?>
