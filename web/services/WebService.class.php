<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebService
{
	private static $db_querier;

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
	 * @param string[] $item : new WebLink
	 */
	public static function add(WebItem $item)
	{
		$result = self::$db_querier->insert(WebSetup::$web_table, $item->get_properties());

		return $result->get_last_inserted_id();
	}

	 /**
	 * @desc Update an entry.
	 * @param string[] $item : WebLink to update
	 */
	public static function update(WebItem $item)
	{
		self::$db_querier->update(WebSetup::$web_table, $item->get_properties(), 'WHERE id=:id', array('id' => $item->get_id()));
	}

	 /**
	 * @desc Update the number of views of a link.
	 * @param string[] $item : WebLink to update
	 */
	public static function update_views_number(WebItem $item)
	{
		self::$db_querier->update(WebSetup::$web_table, array('views_number' => $item->get_views_number()), 'WHERE id=:id', array('id' => $item->get_id()));
	}

	 /**
	 * @desc Delete an entry.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function delete(int $id)
	{
		if (AppContext::get_current_user()->is_readonly())
        {
            $controller = PHPBoostErrors::user_in_read_only();
            DispatchManager::redirect($controller);
        }
			self::$db_querier->delete(WebSetup::$web_table, 'WHERE id=:id', array('id' => $id));

			self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'web', 'id' => $id));

			CommentsService::delete_comments_topic_module('web', $id);
			KeywordsService::get_keywords_manager()->delete_relations($id);
			NotationService::delete_notes_id_in_module('web', $id);
	}

	 /**
	 * @desc Return the properties of an item.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function get_item($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT web.*, member.*, notes.average_notes, notes.notes_number, note.note
		FROM ' . WebSetup::$web_table . ' web
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = web.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
		' . $condition, $parameters);

		$item = new WebItem();
		$item->set_properties($row);
		return $item;
	}

	public static function clear_cache()
	{
		Feed::clear_cache('web');
		KeywordsCache::invalidate();
		WebCache::invalidate();
		CategoriesService::get_categories_manager()->regenerate_cache();
	}
}
?>
