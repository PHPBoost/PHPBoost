<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 08 27
 * @since       PHPBoost 3.0 - 2010 01 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class WikiSetup extends DefaultModuleSetup
{
	public static $wiki_articles_table;
	public static $wiki_cats_table;
	public static $wiki_contents_table;
	public static $wiki_favorites_table;

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
	}

	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('wiki', 'config');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$wiki_articles_table, self::$wiki_cats_table, self::$wiki_contents_table, self::$wiki_favorites_table));
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
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_contents' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'encoded_title' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'hits' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'id_cat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'is_cat' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'defined_status' => array('type' => 'string', 'length' => 6, 'notnull' => 1, 'default' => 0),
			'undefined_status' => array('type' => 'text', 'length' => 65000),
			'redirect' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'title' => array('type' => 'fulltext', 'fields' => 'title')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$wiki_articles_table, $fields, $options);
	}

	private function create_wiki_cats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'article_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$wiki_cats_table, $fields, $options);
	}

	private function create_wiki_contents_table()
	{
		$fields = array(
			'id_contents' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_article' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'menu' => array('type' => 'text', 'length' => 65000),
			'content' => array('type' => 'text', 'length' => 16777215),
			'activ' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'user_ip' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'change_reason' => array('type' => 'text', 'length' => 100, 'notnull' => 0)
		);
		$options = array(
			'primary' => array('id_contents'),
			'indexes' => array(
				'content' => array('type' => 'fulltext', 'fields' => 'content')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$wiki_contents_table, $fields, $options);
	}

	private function create_wiki_favorites_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'id_article' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$wiki_favorites_table, $fields, $options);
	}

}
?>
