<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 4.0 - 2013 07 08
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminContentUrlBuilder
{
	private static $dispatcher = '/admin/content/config';

	/*
	 * @ return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
