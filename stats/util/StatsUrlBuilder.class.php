<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 21
 * @since       PHPBoost 5.0 - 2017 02 24
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class StatsUrlBuilder
{
	private static $dispatcher = '/stats';

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
	public static function home($section = '', $year = 0, $month = 0, $day = 0)
	{
		$section = !empty($section) ? $section . '/' : '';
		$year = $year > 0 ? $year . '/' : '';
		$month = $month > 0 ? $month . '/' : '';
		$day = $day > 0 ? $day . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $section . $year . $month . $day);
	}

	/**
	 * @return Url
	 */
	public static function table($section = '', $page = 1)
	{
		$section = !empty($section) ? $section . '/' : '';
		$page = $page > 1 ? 'table/' . $page . '/' : '';
		return DispatchManager::get_url(self::$dispatcher, '/' . $section . $page);
	}

	/**
	 * @return Url
	 */
	public static function display_themes_graph()
	{
		return DispatchManager::get_url(self::$dispatcher, '/graphs/themes');
	}

	/**
	 * @return Url
	 */
	public static function display_sex_graph()
	{
		return DispatchManager::get_url(self::$dispatcher, '/graphs/sex');
	}

	/**
	 * @return Url
	 */
	public static function display_visits_year_graph($year = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/graphs/visits/year/' . $year);
	}

	/**
	 * @return Url
	 */
	public static function display_visits_month_graph($year = '', $month = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/graphs/visits/month/' . $year . ($month ? '/' . $month : ''));
	}

	/**
	 * @return Url
	 */
	public static function display_pages_year_graph($year = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/graphs/pages/year/' . $year);
	}

	/**
	 * @return Url
	 */
	public static function display_pages_month_graph($year = '', $month = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/graphs/pages/month/' . $year . ($month ? '/' . $month : ''));
	}

	/**
	 * @return Url
	 */
	public static function display_browsers_graph()
	{
		return DispatchManager::get_url(self::$dispatcher, '/graphs/browsers');
	}

	/**
	 * @return Url
	 */
	public static function display_os_graph()
	{
		return DispatchManager::get_url(self::$dispatcher, '/graphs/os');
	}

	/**
	 * @return Url
	 */
	public static function display_langs_graph()
	{
		return DispatchManager::get_url(self::$dispatcher, '/graphs/langs');
	}

	/**
	 * @return Url
	 */
	public static function display_bots_graph()
	{
		return DispatchManager::get_url(self::$dispatcher, '/graphs/bots');
	}
}
?>
