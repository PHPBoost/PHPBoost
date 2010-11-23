<?php
/*##################################################
 *                             NewsSetup.class.php
 *                            -------------------
 *   begin                : May 29, 2010
 *   copyright            : (C) 2010 Kévin MASSY
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
			'archive' => array('type' => 'boolean', 'notnull' => 1, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'visible' => array('type' => 'boolean', 'notnull' => 1, 'notnull' => 1, 'default' => 0),
			'q_order' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'start' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'end' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'img' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'question' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'alt' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'answer' => array('type' => 'text', 'length' => 65000),
			'nbr_com' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'archive' => array('type' => 'boolean', 'notnull' => 1, 'notnull' => 1, 'default' => 0),
			'lock_com' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
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

}

?>
