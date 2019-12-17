<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 07 08
*/

class AdminContentUrlBuilder
{
	private static $dispatcher = '/admin/content';

	/*
	 * @ return Url
	 */
	public static function content_configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/config/');
	}
}
?>
