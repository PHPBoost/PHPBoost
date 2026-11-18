<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 4.0 - 2014 05 16
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminExtendedFieldsUrlBuilder
{
	private static $dispatcher = '/admin/users/member';

	public static function fields_list(string $params = ''): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/list/' . $params);
	}

	public static function add(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/add/');
	}

	public static function edit(int $id): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/' . $id. '/edit/');
	}

	public static function delete(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/delete/');
	}

	public static function change_display(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/extended-fields/change_display/');
	}
}
?>
