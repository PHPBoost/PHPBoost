<?php
/*##################################################
 *                             ArticlesSetup.class.php
 *                            -------------------
 *   begin                : January 17, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class ArticlesSetup extends DefaultModuleSetup
{
	private static $articles_table;
	private static $articles_cat_table;
	private $messages;
	
	public static function __static()
	{
		self::$articles_table = PREFIX . 'articles';
		self::$articles_cat_table = PREFIX . 'articles_cats';
	}
	
	public function __construct()
	{
		$this->querier = PersistenceContext::get_querier();
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
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$articles_table, self::$articles_cat_table));
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
			'idcat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'contents' => array('type' => 'text', 'length' => 65000),
			'sources' => array('type' => 'text', 'length' => 65000),
			'icon' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'start' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'end' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'views' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'visible' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'idcat' => array('type' => 'key', 'fields' => 'idcat'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$articles_table, $fields, $options);
	}

	private function create_articles_cats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'c_order' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 150, 'notnull' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'image' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'visible' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'default' => "''")
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array('class' => array('type' => 'key', 'fields' => 'c_order'))
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$articles_cat_table, $fields, $options);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'articles');
		$this->insert_categories_data();
		$this->insert_articles_data();
	}
	
	private function insert_categories_data()
	{
		$this->querier->insert(self::$articles_cat_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'name' => $this->messages['default.category.name'],
			'description' => $this->messages['default.category.description'],
			'image' => 'articles.png',
			'visible' => 1,
			'auth' => ''
		));
	}
	
	private function insert_articles_data()
	{
		$this->querier->insert(self::$articles_table, array(
			'id' => 1,
			'idcat' => 1,
			'title' => $this->messages['default.article.name'],
			'description' => $this->messages['default.article.description'],
			'contents' => $this->messages['default.article.contents'],
			'sources' => serialize(array()),
			'icon' => 'articles.png',
			'timestamp' => time(),
			'start' => 0,
			'end' => 0,
			'user_id' => 1,
			'views' => 0,
			'visible' => 1
		));
	}
}
?>