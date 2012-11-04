<?php
/*##################################################
 *                             NewsSetup.class.php
 *                            -------------------
 *   begin                : May 29, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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

class NewsSetup extends DefaultModuleSetup
{
	private static $news_table;
	private static $news_cats_table;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$news_table = PREFIX . 'news';
		self::$news_cats_table = PREFIX . 'news_cat';
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
		PersistenceContext::get_dbms_utils()->drop(array(self::$news_table, self::$news_cats_table));
	}

	private function create_tables()
	{
		$this->create_news_table();
		$this->create_news_cats_table();
	}

	private function create_news_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idcat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'extend_contents' => array('type' => 'text', 'length' => 65000),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'visible' => array('type' => 'boolean', 'notnull' => 1, 'notnull' => 1, 'default' => 0),
			'start' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'end' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'img' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'alt' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'compt' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'sources' => array('type' => 'text', 'length' => 65000),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'idcat' => array('type' => 'key', 'fields' => 'idcat'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents'),
				'extend_contents' => array('type' => 'fulltext', 'fields' => 'extend_contents')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$news_table, $fields, $options);
	}

	private function create_news_cats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'c_order' => array('type' => 'integer', 'length' => 11, 'unsigned' => 1, 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'visible' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'description' => array('type' => 'text', 'length' => 65000),
			'image' => array('type' => 'string', 'length' => 255, 'notnull' => 1)
		);

		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$news_cats_table, $fields, $options);
	}
	
	private function insert_data()
	{
        $this->messages = LangLoader::get('install', 'news');
		$this->insert_news_cats_data();
		$this->insert_news_data();
	}

	private function insert_news_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$news_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'name' => $this->messages['cat.name'],
			'visible' => (int)true,
			'description' => $this->messages['cat.description'],
			'image' => '/news/news.png'
		));
	}
	
	private function insert_news_data()
	{
		PersistenceContext::get_querier()->insert(self::$news_table, array(
			'id' => 1,
			'idcat' => 1,
			'title' => $this->messages['news.title'],
			'contents' => $this->messages['news.content'],
			'extend_contents' => '',
			'timestamp' => time(),
			'visible' => (int)true,
			'start' => 0,
			'end' => 0,
			'img' => '',
			'alt' => '',
			'user_id' => 1,
			'compt' => 0,
			'sources' => serialize(array())
		));
	}
}
?>