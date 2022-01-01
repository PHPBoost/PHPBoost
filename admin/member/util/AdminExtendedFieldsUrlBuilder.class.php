<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2014 05 16
*/

class AdminExtendedFieldsUrlBuilder
{
	private static $dispatcher = '/admin/member';

	/*
	 * @ return Url
	*/
	public static function fields_list($params = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/list/' . $params);
	}

	/*
	 * @ return Url
	 */
	public static function add()
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/add/');
	}

	/*
	 * @ return Url
	 */
	public static function edit($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/' . $id. '/edit/');
	}

	/*
	 * @ return Url
	 */
	public static function delete()
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/delete/');
	}

	/*
	 * @ return Url
	 */
	public static function change_display()
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/change_display/');
	}
}
?>
