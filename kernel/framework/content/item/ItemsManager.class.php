<?php
/**
 * @package     Content
 * @subpackage  Item
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 18
 * @since       PHPBoost 6.0 - 2019 12 20
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ItemsManager
{
	protected static $db_querier;
	protected static $module_id;
	protected static $module;
	protected static $items_table;
	
	protected $get_items_static_select;
	protected $get_items_static_join_table;
	protected $get_items_static_condition;
	protected $get_items_static_sort_field;
	protected $get_items_static_sort_mode;

	public static function __static()
	{
		self::$db_querier  = PersistenceContext::get_querier();
	}

	public function __construct($module_id = '')
	{
		$called_module_id  = ClassLoader::get_module_id_from_class_name(get_called_class());
		self::$module_id   = $module_id ? $module_id : ($called_module_id && !in_array($called_module_id, array('admin', 'kernel', 'user')) ? $called_module_id : Environment::get_running_module_name());
		self::$module      = ModulesManager::get_module(self::$module_id);
		self::$items_table = self::$module->get_configuration()->get_items_table_name();
		
		$this->init_get_items_additional_parameters();
	}

	/**
	 * @desc Initialize the additional parameters to custom the request to get items. Possibility to modify this part only in modules to change the behaviour of the multiple select request.
	 */
	protected function init_get_items_additional_parameters()
	{
		$this->get_items_static_select = '';
		$this->get_items_static_join_table = '';
		$this->get_items_static_condition = '';
		$this->get_items_static_sort_field = '';
		$this->get_items_static_sort_mode = 'ASC';
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
		self::$db_querier->update(self::$items_table, $item->get_properties(), 'WHERE id=:id', array('id' => $item->get_id()));
	}

	/**
	 * @desc Delete an item.
	 * @param string[] $item Item to delete
	 */
	public function delete(Item $item)
	{
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}

		self::$db_querier->delete(self::$items_table, 'WHERE id=:id', array('id' => $item->get_id()));

		self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => self::$module_id, 'id' => $item->get_id()));

		CommentsService::delete_comments_topic_module(self::$module_id, $item->get_id());
		KeywordsService::get_keywords_manager()->delete_relations($item->get_id());
		NotationService::delete_notes_id_in_module(self::$module_id, $item->get_id());
	}

	/**
	 * @desc Delete an item from its id.
	 * @param int $id Item identifier
	 */
	public function delete_from_id(int $id)
	{
		$this->delete($this->get_item($id));
	}

	/**
	 * @desc Return the item with all its properties from its id.
	 * @param int $id Item identifier
	 */
	public function get_item(int $id)
	{
		$row = self::$db_querier->select_single_row_query('SELECT ' . self::$module_id . '.*, member.*, average_notes.average_notes, average_notes.notes_number, note.note
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
	 * @desc Return the list of items corresponding to the condition.
	 * @param string $condition Restriction to apply to the item
	 * @param array $parameters Parameters of the condition
	 * @param int $number_items_per_page Number of items to display
	 * @param int $display_from First item to take into account
	 * @param string $sort_field Field on which apply the sorting
	 * @param string $sort_mode Sort mode (asc or desc)
	 * @param bool $keywords Join keywords relations or not
	 */
	public function get_items($condition = '', array $parameters = array(), int $number_items_per_page = 0, int $display_from = 0, $sort_field = '', $sort_mode = 'DESC', $keywords = false)
	{
		$now = new Date();
		$items = array();

		$result = self::$db_querier->select('SELECT ' . self::$module_id . '.*, member.*, comments_topic.comments_number, average_notes.average_notes, average_notes.notes_number, note.note' . ($this->get_items_static_select ? ', ' . $this->get_items_static_select : '') . '
		FROM ' . self::$items_table . ' ' . self::$module_id . '
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = ' . self::$module_id . '.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' comments_topic ON comments_topic.module_id = :module_id AND comments_topic.id_in_module = ' . self::$module_id . '.id
		' . ($keywords ? 'LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' keywords_relations ON keywords_relations.module_id = :module_id AND keywords_relations.id_in_module = ' . self::$module_id . '.id' : '') . '
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' average_notes ON average_notes.module_name = :module_id AND average_notes.id_in_module = ' . self::$module_id . '.id
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.module_name = :module_id AND note.id_in_module = ' . self::$module_id . '.id AND note.user_id = :current_user_id
		' . $this->get_items_static_join_table . '
		' . $condition . ($this->get_items_static_condition ? ($condition ? ' AND ' : 'WHERE ') . $this->get_items_static_condition : '') . '
		' . ($this->get_items_static_sort_field || $sort_field ? 'ORDER BY ' : '') . ($this->get_items_static_sort_field ? $this->get_items_static_sort_field . ' ' . $this->get_items_static_sort_mode : '') . ($this->get_items_static_sort_field && $sort_field ? ', ' : '') . ($sort_field ? $sort_field . ' ' . $sort_mode : '') . '
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
			CategoriesService::get_categories_manager(self::$module_id)->regenerate_cache();

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
			if (ClassLoader::is_class_registered_and_valid($cache_class) && is_subclass_of($cache_class, 'CacheData'))
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
	public function get_module_id()
	{
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
