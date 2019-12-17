<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 11
 * @since       PHPBoost 3.0 - 2011 09 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class NewsletterUrlBuilder
{
	const DEFAULT_SORT_FIELD = 'date';
	const DEFAULT_SORT_MODE = 'bottom';

	private static $dispatcher = '/newsletter';

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
	public static function add_newsletter($type = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/' . $type);
	}

	/**
	 * @return Url
	 */
	public static function manage_streams()
	{
		return DispatchManager::get_url(self::$dispatcher, '/streams/');
	}

	/**
	 * @return Url
	 */
	public static function add_stream()
	{
		return DispatchManager::get_url(self::$dispatcher, '/stream/add/');
	}

	/**
	 * @return Url
	 */
	public static function edit_stream($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/stream/' . $id .'/edit/');
	}

	/**
	 * @return Url
	 */
	public static function delete_stream($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/stream/' . $id .'/delete/');
	}

	/**
	 * @return Url
	 */
	public static function subscribers($id_stream = 0, $rewrited_name = '', $sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1)
	{
		$stream = $id_stream > 0 ? $id_stream . '-' . $rewrited_name . '/' : '';
		$page = $page !== 1 ? $page . '/': '';
		return DispatchManager::get_url(self::$dispatcher, '/subscribers/' . $stream . $sort_field . '/' . $sort_mode . '/' . $page);
	}

	/**
	 * @return Url
	 */
	public static function subscribe()
	{
		return DispatchManager::get_url(self::$dispatcher, '/subscribe/');
	}

	/**
	 * @return Url
	 */
	public static function unsubscribe()
	{
		return DispatchManager::get_url(self::$dispatcher, '/unsubscribe/');
	}

	/**
	 * @return Url
	 */
	public static function edit_subscriber($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/subscriber/' . $id . '/edit/');
	}

	/**
	 * @return Url
	 */
	public static function delete_subscriber($id, $stream_id = null)
	{
		return DispatchManager::get_url(self::$dispatcher, '/subscriber/' . $id . '/delete/' . (!empty($stream_id) ? $stream_id : ''));
	}

	/**
	 * @return Url
	 */
	public static function archives($id_stream = 0, $rewrited_name = '', $sort_field = self::DEFAULT_SORT_FIELD, $sort_mode = self::DEFAULT_SORT_MODE, $page = 1)
	{
		$stream = $id_stream > 0 ? $id_stream . '-' . $rewrited_name . '/' : '';
		$page = $page !== 1 ? $page . '/': '';
		$sort_field = $sort_field !== self::DEFAULT_SORT_FIELD ? $sort_field . '/' : '';
		$sort_mode = $sort_mode !== self::DEFAULT_SORT_MODE ? $sort_mode . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/archives/' . $stream . $sort_field . $sort_mode . $page);
	}

	/**
	 * @return Url
	 */
	public static function archive($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/archive/' . $id);
	}

	/**
	 * @return Url
	 */
	public static function delete_archive($id, $id_stream)
	{
		return DispatchManager::get_url(self::$dispatcher, '/delete/' . $id . '/' . $id_stream . '/?token=' . AppContext::get_session()->get_token());
	}

	/**
	 * @return Url
	 */
	public static function home($page = 1)
	{
		$page = $page !== 1 ? $page . '/': '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $page);
	}
}
?>
