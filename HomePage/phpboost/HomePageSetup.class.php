<?php
/*##################################################
 *                           HomePageSetup.class.php
 *                            -------------------
 *   begin                : March 14, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class HomePageSetup extends DefaultModuleSetup
{
	public static $home_page_table;

	public static function __static()
	{
		self::$home_page_table = PREFIX . 'home_page';
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
		PersistenceContext::get_dbms_utils()->drop(array(self::$home_page_table));
	}

	private function create_tables()
	{
		$this->create_home_page_table();
	}
	
	private function create_home_page_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 128, 'notnull' => 1),
			'class' => array('type' => 'string', 'length' => 67, 'notnull' => 1),
			'object' => array('type' => 'text', 'length' => 65000),
			'block' => array('type' => 'integer', 'length' => 2, 'notnull' => 1, 'default' => 0),
			'position' => array('type' => 'integer', 'length' => 2, 'notnull' => 1, 'default' => 0),
			'enabled' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'authorizations' => array('type' => 'text', 'length' => 65000),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'block' => array('type' => 'key', 'fields' => 'block'),
				'class' => array('type' => 'key', 'fields' => 'class'),
				'enabled' => array('type' => 'key', 'fields' => 'enabled')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$home_page_table, $fields, $options);}
}
?>