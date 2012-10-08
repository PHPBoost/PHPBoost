<?php
/*##################################################
 *                              BugtrackerSetup.class.php
 *                            -------------------
 *   begin                : April 16, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
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

class BugtrackerSetup extends DefaultModuleSetup
{
	private static $bugtracker_table;
	private static $bugtracker_history_table;

	public static function __static()
	{
		self::$bugtracker_table = PREFIX . 'bugtracker';
		self::$bugtracker_history_table = PREFIX . 'bugtracker_history';
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
		PersistenceContext::get_dbms_utils()->drop(array(self::$bugtracker_table, self::$bugtracker_history_table));
	}
	
	private function delete_configuration()
	{
		ConfigManager::delete('bugtracker', 'config');
	}
	
	private function create_tables()
	{
		$this->create_bugtracker_table();
		$this->create_bugtracker_history_table();
	}

	private function create_bugtracker_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'author_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'submit_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'status' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'severity' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'priority' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'type' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'category' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'reproductible' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 1),
			'reproduction_method' => array('type' => 'text', 'length' => 65000),
			'detected_in' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'fixed_in' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'assigned_to_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$bugtracker_table, $fields, $options);
	}
	
	private function create_bugtracker_history_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'bug_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'updater_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'update_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'updated_field' => array('type' => 'string', 'length' => 64, 'default' => 0),
			'old_value' => array('type' => 'text', 'length' => 65000),
			'new_value' => array('type' => 'text', 'length' => 65000),
			'change_comment' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array('bug_id' => array('type' => 'key', 'fields' => 'bug_id'))
		);
		
		PersistenceContext::get_dbms_utils()->create_table(self::$bugtracker_history_table, $fields, $options);
	}
}
?>
