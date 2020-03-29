<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 19
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ArticlesSetup extends DefaultModuleSetup
{
	public static $articles_table;
	public static $articles_cats_table;

	/**
	 * @var string[string] localized messages
	*/
	private $messages;

	public static function __static()
	{
		self::$articles_table = PREFIX . 'articles';
		self::$articles_cats_table = PREFIX . 'articles_cats';
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
		ConfigManager::delete('articles', 'config');
		KeywordsService::get_keywords_manager()->delete_module_relations();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$articles_table, self::$articles_cats_table));
	}

	private function create_tables()
	{
		$this->create_articles_table();
		$this->create_articles_cats_table();
	}

	private function create_articles_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'picture_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'title' => array('type' => 'string', 'length' => 250, 'notnull' => 1, 'default' => "''"),
			'rewrited_title' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'description' => array('type' => 'text', 'length' => 65000),
			'contents' => array('type' => 'text', 'length' => 16777215),
			'number_view' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'author_custom_name' => array('type' =>  'string', 'length' => 255, 'default' => "''"),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_name_displayed' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
			'published' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'publishing_start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'publishing_end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'date_created' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'date_updated' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'sources' => array('type' => 'text', 'length' => 65000),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'description' => array('type' => 'fulltext', 'fields' => 'description'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$articles_table, $fields, $options);
	}

	private function create_articles_cats_table()
	{
		RichCategory::create_categories_table(self::$articles_cats_table);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'articles');
		$this->insert_articles_cats_data();
		$this->insert_articles_data();
	}

	private function insert_articles_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$articles_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'name' => $this->messages['default.category.name'],
			'description' => $this->messages['default.category.description'],
			'image' => '/templates/default/images/default_category_thumbnail.png'
		));
	}

	private function insert_articles_data()
	{
		PersistenceContext::get_querier()->insert(self::$articles_table, array(
			'id' => 1,
			'id_category' => 1,
			'picture_url' => '/templates/default/images/default_item_thumbnail.png',
			'title' => $this->messages['default.article.title'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.article.title']),
			'description' => $this->messages['default.article.description'],
			'contents' => $this->messages['default.article.contents'],
			'number_view' => 0,
			'author_user_id' => 1,
			'author_custom_name' => '',
			'author_name_displayed' => Article::AUTHOR_NAME_DISPLAYED,
			'published' => Article::PUBLISHED_NOW,
			'publishing_start_date' => 0,
			'publishing_end_date' => 0,
			'date_created' => time(),
			'date_updated' => 0,
			'sources' => TextHelper::serialize(array())
		));
	}
}
?>
