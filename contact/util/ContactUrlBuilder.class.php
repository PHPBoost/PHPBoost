<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 03 01
*/

class ContactUrlBuilder
{
	private static $dispatcher = '/contact';

	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}

	public static function check_field_name()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/check_name/');
	}

	public static function add_field()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/add/');
	}

	public static function edit_field($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/'. $id .'/edit/');
	}

	public static function delete_field()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/delete/');
	}

	public static function change_display()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/change_display/');
	}

	public static function manage_fields()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/fields/');
	}

	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
