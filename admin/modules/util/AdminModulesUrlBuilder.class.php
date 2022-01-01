<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2018 01 10
 * @since       PHPBoost 3.0 - 2011 09 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminModulesUrlBuilder
{
	private static $dispatcher = '/admin/modules';

	/*
	 * @ return Url
	 */
	public static function list_installed_modules()
	{
		return DispatchManager::get_url(self::$dispatcher, '/installed/');
	}

	/*
	 * @ return Url
	 */
	public static function add_module()
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/');
	}

	/*
	 * @ return Url
	 */
	public static function update_module($id = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/update/' . $id);
	}

	/*
	 * @ return Url
	 */
	public static function delete_module($id)
	{
		return DispatchManager::get_url(self::$dispatcher, $id . '/delete/');
	}
}
?>
