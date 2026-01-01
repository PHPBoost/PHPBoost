<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 3.0 - 2010 01 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class WikiSetup extends DefaultModuleSetup
{
	public static $wiki_articles_table;
	public static $wiki_cats_table;
	public static $wiki_contents_table;
	public static $wiki_favorites_table;

	private $messages;

	public static function __static()
	{
		self::$wiki_articles_table = PREFIX . 'wiki_articles';
		self::$wiki_cats_table = PREFIX . 'wiki_cats';
		self::$wiki_contents_table = PREFIX . 'wiki_contents';
		self::$wiki_favorites_table = PREFIX . 'wiki_favorites';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
	}

	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('wiki', 'config');
		CacheManager::invalidate('module', 'wiki');
		KeywordsService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop([
			self::$wiki_articles_table,
			self::$wiki_cats_table,
			self::$wiki_contents_table,
			self::$wiki_favorites_table
		]);
	}

	private function create_tables()
	{
		$this->create_wiki_articles_table();
		$this->create_wiki_cats_table();
		$this->create_wiki_contents_table();
		$this->create_wiki_favorites_table();
	}

	private function create_wiki_articles_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'id_category' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'title' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'rewrited_title' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'i_order' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'published' => ['type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'publishing_start_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'publishing_end_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'creation_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'views_number' => ['type' => 'integer', 'length' => 11, 'default' => 0],
        ];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'title' => ['type' => 'fulltext', 'fields' => 'title'],
				'id_category' => ['type' => 'key', 'fields' => 'id_category'],
				'i_order' => ['type' => 'key', 'fields' => 'i_order'],
            ]
        ];
		PersistenceContext::get_dbms_utils()->create_table(self::$wiki_articles_table, $fields, $options);
	}

	private function create_wiki_contents_table()
	{
		$fields = [
			'content_id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'item_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'summary' => ['type' => 'text', 'length' => 65000],
			'active_content' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'content' => ['type' => 'text', 'length' => 16777215],
			'thumbnail' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'author_user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'author_custom_name' => ['type' =>  'string', 'length' => 255, 'default' => "''"],
			'update_date' => ['type' => 'integer', 'length' => 11, 'default' => 0],
			'change_reason' => ['type' => 'text', 'length' => 65000, 'notnull' => 0],
			'content_level' => ['type' => 'integer', 'length' => 1, 'default' => 0],
			'custom_level' => ['type' => 'text', 'length' => 65000],
			'sources' => ['type' => 'text', 'length' => 65000],
        ];
		$options = [
			'primary' => ['content_id'],
			'indexes' => [
				'item_id' => ['type' => 'key', 'fields' => 'item_id'],
				'content' => ['type' => 'fulltext', 'fields' => 'content'],
            ]
        ];
		PersistenceContext::get_dbms_utils()->create_table(self::$wiki_contents_table, $fields, $options);
	}

	private function create_wiki_cats_table()
	{
		RichCategory::create_categories_table(self::$wiki_cats_table);
	}

	private function create_wiki_favorites_table()
	{
		$fields = [
			'track_id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'track_item_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'track_user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
        ];
		$options = [
			'primary' => ['track_id']
        ];
		PersistenceContext::get_dbms_utils()->create_table(self::$wiki_favorites_table, $fields, $options);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'wiki');
		$this->insert_wiki_cats_data();
		$this->insert_wiki_data();
		$this->insert_wiki_contents_data();
	}

	private function insert_wiki_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$wiki_cats_table, [
			'id'            => 1,
			'id_parent'     => 0,
			'c_order'       => 1,
			'auth'          => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.name']),
			'name'          => $this->messages['default.cat.name'],
			'description'   => $this->messages['default.cat.description'],
			'thumbnail'     => FormFieldThumbnail::DEFAULT_VALUE
		]);
	}

	private function insert_wiki_data()
	{
		PersistenceContext::get_querier()->insert(self::$wiki_articles_table, [
			'id'               		=> 1,
			'id_category'           => 1,
			'title'                 => $this->messages['default.sheet.name'],
			'rewrited_title'        => Url::encode_rewrite($this->messages['default.sheet.name']),
			'i_order'               => 1,
			'published'             => WikiItem::PUBLISHED,
			'publishing_start_date' => 0,
			'publishing_end_date'   => 0,
			'creation_date'  	    => time(),
			'views_number'          => 0
		]);
	}

	private function insert_wiki_contents_data()
	{
		PersistenceContext::get_querier()->insert(self::$wiki_contents_table, [
			'content_id'     	    => 1,
			'item_id'        	    => 1,
			'active_content'        => 1,
			'summary'        	    => '',
			'author_custom_name'    => '',
			'thumbnail'             => FormFieldThumbnail::DEFAULT_VALUE,
			'content'        	    => $this->messages['default.sheet.content'],
			'content_level'    	    => WikiItemContent::NO_LEVEL,
			'sources'               => TextHelper::serialize([]),
			'change_reason'    	    => $this->messages['default.history.init'],
			'author_user_id'        => 1,
			'update_date'    	    => time()
		]);
	}
}
?>
