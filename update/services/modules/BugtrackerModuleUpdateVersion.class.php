<?php
/*##################################################
 *                       BugtrackerModuleUpdateVersion.class.php
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
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('bugtracker');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (in_array(PREFIX . 'bugtracker', $tables))
		{
			$this->update_config();
			$this->update_bugtracker_table();
			$this->create_bugtracker_users_filters_table();
		}
		
		$this->update_comments();
		$this->delete_old_files();
	}
	
	private function update_config()
	{
		$config = BugtrackerConfig::load();
		$status_list = array('new' => 0, 'pending' => 0, 'assigned' => 20, 'in_progress' => 50, 'rejected' => 0, 'reopen' => 30, 'fixed' => 100);
		$config->set_status_list($status_list);
		BugtrackerConfig::save();
	}
	
	private function update_bugtracker_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'bugtracker');
		if (isset($columns['progess']))
			$this->db_utils->drop_column(PREFIX . 'bugtracker', 'progess');
		
		$this->querier->inject('ALTER TABLE '. PREFIX .'bugtracker' .' CHANGE title title VARCHAR(255);');
	}
	
	private function create_bugtracker_users_filters_table()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (!in_array(PREFIX . 'bugtracker_users_filters', $tables))
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
	
	private function update_comments()
	{
		$result = $this->querier->select('SELECT bugtracker.id, bugtracker.title
		FROM ' . PREFIX . 'bugtracker bugtracker
		JOIN ' . PREFIX . 'comments_topic com ON com.id_in_module = bugtracker.id
		WHERE com.module_id = \'bugtracker\'
		ORDER BY bugtracker.id ASC');
		
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'comments_topic',
				array('path' => '/bugtracker/?url=/detail/'.$row['id'].'-'.Url::encode_rewrite($row['title'])), 
				'WHERE id_in_module=:id_in_module AND module_id=:module_id',
				array('id_in_module' => $row['id'], 'module_id' => 'bugtracker')
			);
		}
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/english/' . $this->module_id . '_config.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/english/' . $this->module_id . '_english.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/french/' . $this->module_id . '_config.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/french/' . $this->module_id . '_french.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_' . $this->module_id . '.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_' . $this->module_id . '_authorizations.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/' . $this->module_id . '.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/' . $this->module_id . '_search_form.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_' . $this->module_id . '.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_' . $this->module_id . '_authorizations.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/' . $this->module_id . '.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/' . $this->module_id . '_begin.php'));
		$file->delete();
		
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/images'));
		if ($folder->exists())
			$folder->delete();
	}
}
?>