<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 09
 * @since       PHPBoost 4.1 - 2014 08 21
*/

class WebService
{
	private static $db_querier;

	private static $keywords_manager;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	 /**
	 * @desc Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 */
	public static function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(WebSetup::$web_table, $condition, $parameters);
	}

	 /**
	 * @desc Create a new entry in the database table.
	 * @param string[] $weblink : new WebLink
	 */
	public static function add(WebLink $weblink)
	{
		$result = self::$db_querier->insert(WebSetup::$web_table, $weblink->get_properties());

		return $result->get_last_inserted_id();
	}

	 /**
	 * @desc Update an entry.
	 * @param string[] $weblink : WebLink to update
	 */
	public static function update(WebLink $weblink)
	{
		self::$db_querier->update(WebSetup::$web_table, $weblink->get_properties(), 'WHERE id=:id', array('id' => $weblink->get_id()));
	}

	 /**
	 * @desc Update the number of views of a link.
	 * @param string[] $weblink : WebLink to update
	 */
	public static function update_number_views(WebLink $weblink)
	{
		self::$db_querier->update(WebSetup::$web_table, array('number_views' => $weblink->get_number_views()), 'WHERE id=:id', array('id' => $weblink->get_id()));
	}

	 /**
	 * @desc Delete an entry.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(WebSetup::$web_table, $condition, $parameters);
	}

	 /**
	 * @desc Return the properties of a weblink.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function get_weblink($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT web.*, member.*, notes.average_notes, notes.number_notes, note.note
		FROM ' . WebSetup::$web_table . ' web
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = web.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
		' . $condition, $parameters);

		$weblink = new WebLink();
		$weblink->set_properties($row);
		return $weblink;
	}

	 /**
	 * @desc Return the keywords manager.
	 */
	public static function get_keywords_manager()
	{
		if (self::$keywords_manager === null)
		{
			self::$keywords_manager = new KeywordsManager(WebKeywordsCache::load());
		}
		return self::$keywords_manager;
	}
}
?>
