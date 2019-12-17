<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Alain091 <alain091@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2017 02 24
 * @since       PHPBoost 3.0 - 2011 08 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class PagesUrlBuilder
{
	const PREFIX = '/pages/';

	public static function get_link_item($encoded_title)
	{
		return PATH_TO_ROOT . self::PREFIX.url(
			'pages.php?title=' . $encoded_title,
			$encoded_title);
	}

	public static function get_link_error($error=null)
	{
		if (!empty($error))
			$error = '?error='.$error;
		return PATH_TO_ROOT . self::PREFIX.url('pages.php'.$error);
	}

	public static function get_link_item_com($id,$com=0)
	{
		return PATH_TO_ROOT . self::PREFIX.url(
			'pages.php?id='.$id.'&com=0');
	}

	/**
	 * @return Url
	 */
	public static function documentation()
	{
		return new Url (ModulesManager::get_module('pages')->get_configuration()->get_documentation());
	}
}
?>
