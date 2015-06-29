<?php
/*##################################################
 *                             WikiSetup.class.php
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
			'content' => array('type' => 'text', 'length' => 65000),
			'activ' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'user_ip' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
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