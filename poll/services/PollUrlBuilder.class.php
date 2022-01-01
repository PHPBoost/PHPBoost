<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 27
 * @since       PHPBoost 6.0 - 2020 05 14
*/

class PollUrlBuilder extends ItemsUrlBuilder
{
	public static function ajax_send($module_id = 'poll')
	{
		return DispatchManager::get_url(self::get_dispatcher($module_id), '/ajax_send/');
	}
}
?>