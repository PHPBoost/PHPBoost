<?php
/*##################################################
 *                       NewsModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 7, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
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

class BugtrackerModuleUpdateVersion extends ModuleUpdateVersion
{
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('bugtracker');
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$this->update_bugtracker_table();
		$this->create_bugtracker_users_filters_table();
	}
	
	private function update_bugtracker_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'bugtracker');
		if (isset($columns['progess']))
		{
			$this->db_utils->drop_column(PREFIX . 'bugtracker', 'progess');
		}
	}
	
	private function create_bugtracker_users_filters_table()
	{
		$tables = $this->db_utils->list_tables(true);
		if (!isset($tables[PREFIX . 'bugtracker_users_filters']))
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
			$this->db_utils->create_table(PREFIX . 'bugtracker_users_filters', $fields, $options);
		}
	}
}
?>