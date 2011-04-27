<?php
/*##################################################
 *                             ArticlesSetup.class.php
 *                            -------------------
 *   begin                : April 25, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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

class ArticlesSetup extends DefaultModuleSetup
{
	private static $articles_table;
	private static $articles_categories;

	public static function __static()
	{
		self::$articles_table = PREFIX . 'articles';
		self::$articles_categories = PREFIX . 'articles_categories';
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
		PersistenceContext::get_dbms_utils()->drop(array(self::$articles_table, self::$articles_categories));
	}

	private function create_tables()
	{
		$this->create_articles_table();
		$this->create_articles_categories_table();
	}

	private function create_articles_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'picture' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'contents' => array('type' => 'text', 'length' => 65000),
			'source' => array('type' => 'text', 'length' => 65000),
			'number_view' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'writer_user_id' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'writer_name_visitor' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'visibility' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'start_visibility' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'end_visibility' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'authorizations' => array('type' => 'text', 'length' => 65000),
			'timestamp_created' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'timestamp_last_modified' => array('type' => 'integer', 'length' => 11, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_categorie' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$articles_table, $fields, $options);
	}

	private function create_articles_categories_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'c_order' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 150, 'notnull' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'picture' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'notation_disabled' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'comments_disabled' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'visible' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'authorizations' => array('type' => 'text', 'default' => "''")
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array('class' => array('type' => 'key', 'fields' => 'c_order'))
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$articles_categories, $fields, $options);
	}

	private function insert_data()
	{
	}
}

?>