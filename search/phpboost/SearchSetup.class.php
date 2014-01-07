<?php
/*##################################################
 *		             SearchSetup.class.php
 *                            -------------------
 *   begin                : May 05, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class SearchSetup extends DefaultModuleSetup 
{
	private static $db_utils;
	public static $search_index_table;
	public static $search_results_table;
	
	public static function __static()
	{
		self::$db_utils = PersistenceContext::get_dbms_utils();
		
		self::$search_index_table = PREFIX . 'search_index';
		self::$search_results_table = PREFIX . 'search_results';
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
		self::$db_utils->drop(array(self::$search_index_table, self::$search_results_table));
	}
	
	private function create_tables()
	{
		$this->create_search_index_table();
		$this->create_search_results_table();
	}
	
	private function create_search_index_table()
	{
		$fields = array(
			'id_search' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_user' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'module' => array('type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => 0),
			'search' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'options' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'last_search_use' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'times_used' => array('type' => 'integer', 'length' => 3, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id_search'),
			'indexes' => array(
				'id_user' => array('type' => 'unique', 'fields' => array('id_user', 'module', 'search', 'options')),
				'last_search_use' => array('type' => 'key', 'fields' => 'last_search_use')
			)
		);
		self::$db_utils->create_table(self::$search_index_table, $fields, $options);
	}

	private function create_search_results_table()
	{
		$fields = array(
			'id_search' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_content' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'relevance' => array('type' => 'decimal', 'scale' => 3, 'notnull' => 1, 'default' => 0.00),
			'link' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''")

		);

		$options = array(
			'primary' => array('id_search', 'id_content'),
			'indexes' => array(
				'relevance' => array('type' => 'key', 'fields' => 'relevance')
			)
		);
		self::$db_utils->create_table(self::$search_results_table, $fields, $options);
	}
	
	private function delete_configuration()
	{
		ConfigManager::delete('search', 'config');
	}
}
?>