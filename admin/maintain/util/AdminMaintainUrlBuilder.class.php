<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2014 09 11
*/

class AdminMaintainUrlBuilder
{
	private static $dispatcher = '/admin/maintain';

	/*
	 * @ return Url
	 */
	public static function maintain()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
