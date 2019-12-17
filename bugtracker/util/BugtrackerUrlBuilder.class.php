<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 04 10
 * @since       PHPBoost 3.0 - 2012 10 20
*/

class BugtrackerUrlBuilder
{
	const DEFAULT_SORT_FIELD = 'date';
	const DEFAULT_SORT_MODE = 'desc';

	private static $dispatcher = '/bugtracker';

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
	public static function authorizations()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/authorizations');
	}

	/**
	 * @return Url
	 */
	public static function delete_parameter($parameter, $id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/delete/' . $parameter . '/' . $id);
	}

	/**
	 * @return Url
	 */
	public static function delete_default_parameter($parameter)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/delete/default/' . $parameter . '/?token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}

	/**
	 * @return Url
	 */
	public static function detail($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/detail/' . $param);
	}

	/**
	 * @return Url
	 */
	public static function detail_comments($param = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/detail/' . $param, true);
	}

	/**
	 * @return Url
	 */
	public static function add()
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/');
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
	public static function history($id, $page = 1)
	{
		$page = $page !== 1 ? $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/history/' . $id . '/' . $page);
	}

	/**
	 * @return Url
	 */
	public static function roadmap($version = 0, $version_name = null, $status = null, $sort_field = '', $sort_mode = '', $page = 1)
	{
		$version = $version !== 0 ? $version . '-' : '';
		$version_name = $version_name !== null ? $version_name . '/' : '';
		$status = $status !== null ? $status . '/' : '';
		$sort_field = $sort_field !== '' ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== '' ? $sort_mode . '/' : '';
		$page = $page !== 1 ? $page . '/': '';
		return DispatchManager::get_url(self::$dispatcher, '/roadmap/' . $version . $version_name . $status . $sort_field . $sort_mode . $page);
	}

	/**
	 * @return Url
	 */
	public static function stats()
	{
		return DispatchManager::get_url(self::$dispatcher, '/stats/');
	}

	/**
	 * @return Url
	 */
	public static function delete($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function add_filter($page = 1, $filter = null, $filter_id = 0)
	{
		$page = $page !== 1 ? $page . '/' : '';
		$filter = $filter !== null ? $filter . '/' : '';
		$filter_id = $filter !== null && $filter_id !== 0 ? $filter_id . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/add_filter/' . $page . $filter . $filter_id);
	}

	/**
	 * @return Url
	 */
	public static function delete_filter()
	{
		return DispatchManager::get_url(self::$dispatcher, '/delete_filter/');
	}

	/**
	 * @return Url
	 */
	public static function change_status($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/change_status/' . $id);
	}

	/**
	 * @return Url
	 */
	public static function check_status_changed()
	{
		return DispatchManager::get_url(self::$dispatcher, '/check_status_changed/');
	}

	/**
	 * @return Url
	 */
    public static function unsolved($sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1, $filter = null, $filter_id = 0)
	{
		$sort_field = $sort_field !== '' ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== '' ? $sort_mode . '/' : '';
		$page = $page !== 1 ? $page . '/' : '';
		$filter = $filter !== null ? $filter . '/' : '';
		$filter_id = $filter !== null && $filter_id !== 0 ? $filter_id : '';
		return DispatchManager::get_url(self::$dispatcher, '/unsolved/' . $sort_field . $sort_mode . $page . $filter . $filter_id);
	}

	/**
	 * @return Url
	 */
    public static function solved($sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1, $filter = null, $filter_id = 0)
	{
		$sort_field = $sort_field !== '' ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== '' ? $sort_mode . '/' : '';
		$page = $page !== 1 ? $page . '/' : '';
		$filter = $filter !== null ? $filter . '/' : '';
		$filter_id = $filter !== null && $filter_id !== 0 ? $filter_id : '';
		return DispatchManager::get_url(self::$dispatcher, '/solved/' . $sort_field . $sort_mode . $page . $filter . $filter_id);
	}
}
?>
