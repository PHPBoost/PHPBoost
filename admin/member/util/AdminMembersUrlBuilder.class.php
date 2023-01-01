<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 06 10
 * @since       PHPBoost 3.0 - 2012 05 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminMembersUrlBuilder
{
	private static $dispatcher = '/admin/member';

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
	public static function delete($id)
	{
		return DispatchManager::get_url(self::$dispatcher, $id. '/delete/');
	}

	/*
	 * @ return Url
	*/
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/config/');
	}
}
?>
