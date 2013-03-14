<?php
/*##################################################
 *                             ContactSetup.class.php
 *                            -------------------
 *   begin                : March 1, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class ContactSetup extends DefaultModuleSetup
{
	public static $contact_extended_fields_table;

	public static function __static()
	{
		self::$contact_extended_fields_table = PREFIX . 'contact_extended_fields';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}

	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('contact', 'config');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$contact_extended_fields_table));
	}

	private function create_tables()
	{
		$this->create_contact_extended_fields_list_table();
	}

	private function create_contact_extended_fields_list_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'position' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'field_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'description' => array('type' => 'text', 'length' => 65000),
			'field_type' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'possible_values' => array('type' => 'text', 'length' => 65000),
			'default_values' => array('type' => 'text', 'length' => 65000),
			'required' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'display' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'regex' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'freeze' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'indexes' => array(
				'id' => array('type' => 'unique', 'fields' => 'id')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$contact_extended_fields_table, $fields, $options);
	}
}
?>