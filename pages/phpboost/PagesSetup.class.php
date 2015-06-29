<?php
/*##################################################
 *                             PagesSetup.class.php
 *                            -------------------
 *   begin                : January 17, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class PagesSetup extends DefaultModuleSetup
{
	public static $pages_table;
	public static $pages_cats_table;
	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$pages_table = PREFIX . 'pages';
		self::$pages_cats_table = PREFIX . 'pages_cats';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}

	public function uninstall()
	{
		$this->drop_tables();
		$this->delete_configuration();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$pages_table, self::$pages_cats_table));
	}
	
	private function delete_configuration()
	{
		ConfigManager::delete('pages', 'config');
	}

	private function create_tables()
	{
		$this->create_pages_table();
		$this->create_pages_cats_table();
	}

	private function create_pages_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'encoded_title' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'auth' => array('type' => 'text', 'length' => 65000),
			'is_cat' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'id_cat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'hits' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'count_hits' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'display_print_link' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'activ_com' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'redirect' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$pages_table, $fields, $options);
	}

	private function create_pages_cats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_page' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
		);

		PersistenceContext::get_dbms_utils()->create_table(self::$pages_cats_table, $fields, $options);
	}
}
?>