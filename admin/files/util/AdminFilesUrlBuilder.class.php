<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2015 05 22
*/

class AdminFilesUrlBuilder
{
	private static $dispatcher = '/admin/files';

	/*
	 * @ return Url
	 */
	public static function management()
	{
		return new Url('/admin/admin_files.php');
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
