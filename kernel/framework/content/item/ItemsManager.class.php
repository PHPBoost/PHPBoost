<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 27
 * @since       PHPBoost 5.3 - 2019 12 20
*/

class ItemsManager
{
	protected static $db_querier;
	protected static $module_id;
	protected static $module;
	protected static $items_table;

	public static function __static()
	{
		self::$db_querier  = PersistenceContext::get_querier();
	}

	public function __construct($module_id = '')
	{
		self::$module_id   = $module_id ? $module_id : Environment::get_running_module_name();
		self::$module      = ModulesManager::get_module(self::$module_id);
		self::$items_table = self::$module->get_configuration()->get_items_table_name();
	}

	 /**
	 * @desc Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 * @param array $parameters (optional) : Parameters list to apply to the condition
	 */
	public function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(self::$items_table, $condition, $parameters);
	}

	 /**
	 * @desc Count items number having a specific keyword.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 * @param array $parameters (optional) : Parameters list to apply to the condition
	 */
	public function count_items_having_keyword($condition = '', $parameters = array())
	{
		return self::$db_querier->select_single_row_query('SELECT COUNT(*) AS items_number
		FROM ' . self::$items_table . ' ' . self::$module_id . '
		LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' keywords_relations ON keywords_relations.module_id = \'' . self::$module_id . '\' AND keywords_relations.id_in_module = ' . self::$module_id . '.id
		' . $condition, $parameters);
	}

	 /**
	 * @desc Create a new item.
	 * @param string[] $item new Item
	 */
	public function add(Item $item)
	{
		$result = self::$db_querier->insert(self::$items_table, $item->get_properties());
		return $result->get_last_inserted_id();
	}

	 /**
	 * @desc Update an item.
	 * @param string[] $item Item to update
	 */
	public function update(Item $item)
	{
		self::$db_querier->update(self::$items_table, $item->get_properties(), 'WHERE id=:id', array('id', $item->get_id()));
	}

	 /**
	 * @desc Delete an item.
	 * @param int $id Item identifier
	 */
	public function delete(int $id)
	{
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
		
		self::$db_querier->delete(self::$items_table, 'WHERE id=:id', array('id' => $id));
		
		self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => self::$module_id, 'id' => $id));
		
		CommentsService::delete_comments_topic_module(self::$module_id, $id);
		KeywordsService::get_keywords_manager()->delete_relations($id);
		NotationService::delete_notes_id_in_module(self::$module_id, $id);
	}

	 /**
	 * @desc Return the item with all its properties.
	 * @param int $id Item identifier
	 */
	public function get_item(int $id)
	{
		$row = self::$db_querier->select_single_row_query('SELECT ' . self::$module_id . '.*, member.*, average_notes.average_notes, average_notes.number_notes, note.note
		FROM ' . self::$items_table . ' ' . self::$module_id . '
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = ' . self::$module_id . '.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' average_notes ON average_notes.module_name = :module_id AND average_notes.id_in_module = ' . self::$module_id . '.id
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.module_name = :module_id AND note.id_in_module = ' . self::$module_id . '.id AND note.user_id = :current_user_id
		WHERE ' . self::$module_id . '.id=:id', array(
			'module_id'       => self::$module_id,
			'id'              => $id,
			'current_user_id' => AppContext::get_current_user()->get_id()
		));

		$item = self::get_item_class();
		$item->set_properties($row);
		return $item;
	}

	 /**
	 * @desc Updates the views number of the item.
	 * @param string[] $item Item to update
	 */
	public function update_views_number(Item $item)
	{
		self::$db_querier->update(self::$items_table, array('views_number' => $item->get_views_number() + 1), 'WHERE id=:id', array('id' => $item->get_id()));
	}

	 /**
	 * @desc Return the list of items correspnding to the condition.
	 * @param string $condition Restriction to apply to the item
	 * @param array $parameters Parameters of the condition
	 * @param int $number_items_per_page Number of items to display
	 * @param int $display_from First item to take into account
	 * @param string $sort_field Field on which apply the sorting
	 * @param string $sort_mode Sort mode (asc or desc)
	 */
	public function get_items($condition = '', array $parameters = array(), int $number_items_per_page = 0, int $display_from = 0, $sort_field = '', $sort_mode = '', $keywords = false)
	{
		$now = new Date();
		$items = array();
		
		$result = self::$db_querier->select('SELECT ' . self::$module_id . '.*, member.*, comments_topic.number_comments, average_notes.average_notes, average_notes.number_notes, note.note
		FROM ' . self::$items_table . ' ' . self::$module_id . '
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = ' . self::$module_id . '.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' comments_topic ON comments_topic.module_id = :module_id AND comments_topic.id_in_module = ' . self::$module_id . '.id
		' . ($keywords ? 'LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' keywords_relations ON keywords_relations.module_id = :module_id AND keywords_relations.id_in_module = ' . self::$module_id . '.id' : '') . '
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' average_notes ON average_notes.module_name = :module_id AND average_notes.id_in_module = ' . self::$module_id . '.id
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.module_name = :module_id AND note.id_in_module = ' . self::$module_id . '.id AND note.user_id = :current_user_id
		' . $condition . '
		' . ($sort_field ? 'ORDER BY ' . $sort_field . ' ' . $sort_mode : '') . '
		' . ($number_items_per_page ? 'LIMIT :number_items_per_page OFFSET :display_from' : ''), array_merge($parameters, array(
			'module_id'             => self::$module_id,
			'current_user_id'       => AppContext::get_current_user()->get_id(),
			'timestamp_now'         => $now->get_timestamp(),
			'number_items_per_page' => $number_items_per_page,
			'display_from'          => $display_from
		)));
		
		while ($row = $result->fetch())
		{
			$item = self::get_item_class();
			$item->set_properties($row);
			$items[] = $item;
		}
		$result->dispose();
		
		return $items;
	}

	 /**
	 * @desc Clear caches files
	 */
	public function clear_cache()
	{
		Feed::clear_cache(self::$module_id);
		KeywordsCache::invalidate();
		
		if (self::$module->get_configuration()->has_categories())
			CategoriesService::get_categories_manager()->regenerate_cache();
		
		$this->clear_module_cache();
	}

	 /**
	 * @desc Clear module caches files if needed
	 */
	protected function clear_module_cache()
	{
		$cache_classes = array(ucfirst(self::$module_id) . 'Cache', ucfirst(self::$module_id) . 'MiniMenuCache');
		foreach ($cache_classes as $cache_class)
		{
			if (class_exists($cache_class) && is_subclass_of($cache_class, 'CacheData'))
				call_user_func($cache_class .'::invalidate');
		}
	}
	
	/**
	 * Get the authorizations of the module.
	 * @return mixed[] The array of authorizations of the module.
	 */
	public function get_heritated_authorizations()
	{
		return self::$module->get_configuration()->get_configuration_parameters()->get_authorizations();
	}

	/**
	 * @return string module identifier.
	 */
	public function get_module_id() {
		return self::$module_id;
	}

	/**
	 * @return mixed[] new Item
	 */
	public static function get_item_class()
	{
		$class_name = self::$module->get_configuration()->get_item_name();
		return new $class_name(self::$module_id);
	}
}
?>
