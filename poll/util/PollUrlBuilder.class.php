<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 02 24
 * @since       PHPBoost 5.0 - 2017 02 24
*/

class PollUrlBuilder
{
	private static $dispatcher = '/poll';

	/**
	 * @return Url
	 */
	public static function documentation()
	{
		return new Url (ModulesManager::get_module('poll')->get_configuration()->get_documentation());
	}
}
?>
