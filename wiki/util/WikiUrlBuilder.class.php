<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 02 24
 * @since       PHPBoost 4.0 - 2014 02 02
 * @contributor xela <xela@phpboost.com>
*/

class WikiUrlBuilder
{
	private static $dispatcher = '/wiki';

	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return new Url('/wiki/admin_wiki.php');
	}

	/**
	 * @return Url
	 */
	public static function add_category()
	{
		return new Url('/wiki/post.php?type=cat');
	}

	/**
	 * @return Url
	 */
	public static function add()
	{
		return new Url('/wiki/post.php');
	}

	/**
	 * @return Url
	 */
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}

	/**
	 * @return Url
	 */
	public static function documentation()
	{
		return new Url (ModulesManager::get_module('wiki')->get_configuration()->get_documentation());
	}
}
?>
