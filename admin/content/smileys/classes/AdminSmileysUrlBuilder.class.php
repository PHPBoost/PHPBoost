<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 4.1 - 2015 05 22
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSmileysUrlBuilder
{
	private static $dispatcher = '/admin/content/smileys';

	/*
	 * @ return Url
	 */
	public static function management()
	{
		return DispatchManager::get_url(self::$dispatcher, '/management/');
	}

	/*
	 * @ return Url
	 */
	public static function add()
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/');
	}

	/*
	 * @ return Url
	 */
	public static function edit($id)
	{
		return DispatchManager::get_url(self::$dispatcher, $id . '/edit/');
	}

	/*
	 * @ return Url
	 */
	public static function delete($id)
	{
		return DispatchManager::get_url(self::$dispatcher, $id . '/delete/');
	}
}
?>
