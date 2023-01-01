<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 22
 * @since       PHPBoost 6.0 - 2021 10 22
*/

class HistoryUrlBuilder
{
	private static $dispatcher = '/history';

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
	public static function display_history()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/history/');
	}
}
?>
