<?php
/**
 * @package     Content
 * @subpackage  Item
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 13
 * @since       PHPBoost 5.3 - 2019 12 20
*/

class Item
{
	protected static $module_id;
	protected static $module;

	const READ_AUTHORIZATIONS = 1;
	const WRITE_AUTHORIZATIONS = 2;
	const CONTRIBUTION_AUTHORIZATIONS = 4;
	const MODERATION_AUTHORIZATIONS = 8;

	const SORT_ALPHABETIC = 'title';
	const SORT_DATE = 'creation_date';
	const SORT_AUTHOR = 'display_name';
	const SORT_VIEWS_NUMBER = 'views_number';
	const SORT_NOTATION = 'average_notes';
	const SORT_COMMENTS_NUMBER = 'number_comments';

	const SORT_FIELDS_URL_VALUES = array(
		self::SORT_ALPHABETIC => 'title',
		self::SORT_DATE => 'date',
		self::SORT_AUTHOR => 'author',
		self::SORT_VIEWS_NUMBER => 'views',
		self::SORT_NOTATION => 'notes',
		self::SORT_COMMENTS_NUMBER => 'comments'
	);

	const ASC = 'ASC';
	const DESC = 'DESC';

	public static function __static()
	{
		self::$module_id   = Environment::get_running_module_name();
		self::$module      = ModulesManager::get_module(self::$module_id);
	}

	public function __construct($module_id = '')
	{
		if ($module_id)
		{
			self::$module_id = $module_id;
			self::$module    = ModulesManager::get_module(self::$module_id);
		}
		else
		{
			self::__static();
		}
	}
}
?>
