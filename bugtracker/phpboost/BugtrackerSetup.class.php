<?php
/*##################################################
 *                              BugtrackerSetup.class.php
 *                            -------------------
 *   begin                : April 16, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
	public static $bugtracker_table;
	public static $bugtracker_history_table;
	public static $bugtracker_users_filters_table;
	
	public static function __static()
	{
		self::$bugtracker_table = PREFIX . 'bugtracker';
		self::$bugtracker_history_table = PREFIX . 'bugtracker_history';
		self::$bugtracker_users_filters_table = PREFIX . 'bugtracker_users_filters';
	}
	
	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}
	
	public function upgrade($installed_version)
	{
		//Config
		$config = BugtrackerConfig::load();
		$status_list = array(Bug::NEW_BUG => 10, Bug::ASSIGNED => 50, Bug::IN_PROGRESS => 50, Bug::REJECTED => 0, Bug::REOPEN => 30, Bug::FIXED => 100);
		$config->set_status_list($status_list);
		
		BugtrackerConfig::save();
		
		//Table columns
		$columns = PersistenceContext::get_dbms_utils()->desc_table(self::$bugtracker_table);
		if (isset($columns['progess']))
		{
			PersistenceContext::get_dbms_utils()->drop_column(self::$bugtracker_table, 'progess');
			PersistenceContext::get_dbms_utils()->add_column(self::$bugtracker_table, 'progress', array('type' => 'integer', 'length' => 11, 'default' => 0));
			
			PersistenceContext::get_querier()->update(self::$bugtracker_table, array('progress' => $status_list[Bug::NEW_BUG]), "WHERE status='" . Bug::NEW_BUG . "'");
			PersistenceContext::get_querier()->update(self::$bugtracker_table, array('progress' => $status_list[Bug::ASSIGNED]), "WHERE status='" . Bug::ASSIGNED . "'");
			PersistenceContext::get_querier()->update(self::$bugtracker_table, array('progress' => $status_list[Bug::IN_PROGRESS]), "WHERE status='" . Bug::IN_PROGRESS . "'");
			PersistenceContext::get_querier()->update(self::$bugtracker_table, array('progress' => $status_list[Bug::REOPEN]), "WHERE status='" . Bug::REOPEN . "'");
			PersistenceContext::get_querier()->update(self::$bugtracker_table, array('progress' => $status_list[Bug::FIXED]), "WHERE status='" . Bug::FIXED . "'");
		}
		
		//New table
		$tables = PersistenceContext::get_dbms_utils()->list_tables(true);
		if (!isset($tables[self::$bugtracker_users_filters_table]))
		{
			$this->create_bugtracker_users_filters_table();
		}
		
		return '4.1.0';
	}
	
	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('bugtracker', 'config');
	}
	
	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$bugtracker_table, self::$bugtracker_history_table, self::$bugtracker_users_filters_table));
	}
	
	private function create_tables()
	{
		$this->create_bugtracker_table();
		$this->create_bugtracker_history_table();
		$this->create_bugtracker_users_filters_table();
	}
	
	private function create_bugtracker_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'author_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'submit_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'fix_date' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'status' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'severity' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'priority' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'type' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'category' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'reproductible' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 1),
			'reproduction_method' => array('type' => 'text', 'length' => 65000),
			'detected_in' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'fixed_in' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'progress' => array('type' => 'integer', 'length' => 11, 'default' => 0),
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
	
	private function create_bugtracker_users_filters_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'page' => array('type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => "''"),
			'filters' => array('type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => "''"),
			'filters_ids' => array('type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => "''"),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array('user_id' => array('type' => 'key', 'fields' => 'user_id'))
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$bugtracker_users_filters_table, $fields, $options);
	}
}
?>
