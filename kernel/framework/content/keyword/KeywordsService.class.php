<?php
/**
 * @package     Content
 * @subpackage  Keyword
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 01 23
 * @since       PHPBoost 6.0 - 2019 12 19
*/

class KeywordsService
{
	protected static $keywords_manager;

	public static function get_keywords_manager($module_id = '')
	{
		if (self::$keywords_manager === null)
		{
			self::$keywords_manager = new KeywordsManager($module_id);
		}
		return self::$keywords_manager;
	}
}
?>
