<?php
/**
 * @package     Content
 * @subpackage  Keyword
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 6.0 - 2019 12 19
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class KeywordsService
{
	protected static ?KeywordsManager $keywords_manager = null;

	public static function get_keywords_manager(string $module_id = ''): KeywordsManager
	{
		if (self::$keywords_manager === null)
		{
			self::$keywords_manager = new KeywordsManager($module_id);
		}
		return self::$keywords_manager;
	}
}
?>
