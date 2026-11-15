<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.1 - 2014 09 11
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminMaintainUrlBuilder
{
	private static $dispatcher = '/admin/administration/maintain';

	public static function maintain(): Url
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
