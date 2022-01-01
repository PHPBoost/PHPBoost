<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2015 05 22
*/

class AdminSmileysUrlBuilder
{
	private static $dispatcher = '/admin/smileys';

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
