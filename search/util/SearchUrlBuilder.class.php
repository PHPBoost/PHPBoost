<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 02 24
 * @since       PHPBoost 5.0 - 2017 02 24
*/

class SearchUrlBuilder
{
	private static $dispatcher = '/search';

	/**
	 * @return Url
	 */
	public static function documentation()
	{
		return new Url (ModulesManager::get_module('search')->get_configuration()->get_documentation());
	}
}
?>
