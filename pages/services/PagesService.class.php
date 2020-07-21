<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 07 21
 * @since       PHPBoost 5.2 - 2020 06 15
*/

class PagesService
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
		return self::$db_querier->count(PagesSetup::$pages_table, $condition, $parameters);
	}

	 /**
	 * @desc Create a new entry in the database table.
	 * @param string[] $item : new Page
	 */
	public static function add(Page $item)
	{
		$result = self::$db_querier->insert(PagesSetup::$pages_table, $item->get_properties());

		return $result->get_last_inserted_id();
	}

	 /**
	 * @desc Update an entry.
	 * @param string[] $item : Page to update
	 */
	public static function update(Page $item)
	{
		self::$db_querier->update(PagesSetup::$pages_table, $item->get_properties(), 'WHERE id=:id', array('id' => $item->get_id()));
	}

	 /**
	 * @desc Update the position of a page.
	 * @param string[] $id_page : id of the page to update
	 * @param string[] $position : new page position
	 */
	public static function update_position($id_page, $position)
	{
		self::$db_querier->update(PagesSetup::$pages_table, array('i_order' => $position), 'WHERE id=:id', array('id' => $id_page));
	}

	public static function update_views_number(Page $item)
	{
		self::$db_querier->update(PagesSetup::$pages_table, array('views_number' => $item->get_views_number()), 'WHERE id=:id', array('id' => $item->get_id()));
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
			self::$db_querier->delete(PagesSetup::$pages_table, 'WHERE id=:id', array('id' => $id));

			self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'pages', 'id' => $id));

			CommentsService::delete_comments_topic_module('pages', $id);
			KeywordsService::get_keywords_manager()->delete_relations($id);
	}

	 /**
	 * @desc Return the properties of a page.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function get_page($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT pages.*, member.*
		FROM ' . PagesSetup::$pages_table . ' pages
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = pages.author_user_id
		' . $condition, $parameters);

		$item = new Page();
		$item->set_properties($row);
		return $item;
	}

	public static function clear_cache()
	{
		Feed::clear_cache('pages');
		KeywordsCache::invalidate();
		PagesCache::invalidate();
        CategoriesService::get_categories_manager('pages')->regenerate_cache();
	}
}
?>
