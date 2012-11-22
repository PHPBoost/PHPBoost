<?php
/*##################################################
 *                       BugtrackerModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : October 08, 2012
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
		$this->update_comments();
		$this->update_bugtracker_table();
		$this->rename_status();
		$this->rename_types();
		$this->rename_categories();
		$this->rename_priorities();
		$this->rename_severities();
		$this->rename_versions();
		$this->rename_fields();
		$this->retrieve_bugs_fix_date();
	}
	
	private function update_comments()
	{
		$result = $this->querier->select('SELECT bugtracker.id, bugtracker.nbr_com, bugtracker.lock_com, com.*
		FROM ' . PREFIX . 'bugtracker bugtracker
		JOIN ' . PREFIX . 'com com ON com.idprov = bugtracker.id
		WHERE com.script = \'bugtracker\'');
		$id_in_module = 0;
		$id_topic = 0;
		while ($row = $result->fetch())
		{
			if (empty($id_in_module) || $id_in_module !== $row['id'])
			{
				$id_in_module = $row['id'];
				$topic = $this->querier->insert(PREFIX . 'comments_topic', array(
					'module_id' => $row['script'],
					'id_in_module' => $row['id'],
					'number_comments' => $row['nbr_com'],
					'is_locked' => $row['lock_com'],
					'path' => '/bugtracker/bugtracker.php?view&id='. $row['id'] .'&com=0',
				));
				$id_topic = $topic->get_last_inserted_id();
			}
			
			$this->querier->insert(PREFIX . 'comments', array(
				'id_topic' => $id_topic,
				'user_id' => $row['user_id'],
				'pseudo' => $row['login'],
				'user_ip' => $row['user_ip'],
				'timestamp' => $row['timestamp'],
				'message' => $row['contents']
			));
		}
	}
	
	private function update_bugtracker_table()
	{
		$this->db_utils->drop_column(PREFIX . 'bugtracker', 'nbr_com');
		$this->db_utils->drop_column(PREFIX . 'bugtracker', 'lock_com');
		
		$this->db_utils->add_column(PREFIX . 'bugtracker', 'progess', array('type' => 'integer', 'length' => 11, 'default' => 0));
		$this->db_utils->add_column(PREFIX . 'bugtracker', 'fix_date', array('type' => 'integer', 'length' => 11, 'default' => 0));
	}
	
	private function rename_status()
	{
		// Suppression du statut closed (remplacé par statut fixed)
		$this->querier->update(PREFIX . 'bugtracker', array('status' => 'fixed'), "WHERE status='closed'");
		$this->querier->update(PREFIX . 'bugtracker_history', array('old_value' => 'fixed'), "WHERE old_value='closed'");
		$this->querier->update(PREFIX . 'bugtracker_history', array('new_value' => 'fixed'), "WHERE new_value='closed'");
	}
	
	private function rename_types()
	{
		$rows_change = array(
			'anomaly' => 1,
			'evolution_demand' => 2
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			$this->querier->update(PREFIX . 'bugtracker', array('severity' => $new_name), "WHERE type='" . $old_name . "'");
			$this->querier->update(PREFIX . 'bugtracker_history', array('old_value' => $new_name), "WHERE old_value='" . $old_name . "'");
			$this->querier->update(PREFIX . 'bugtracker_history', array('new_value' => $new_name), "WHERE new_value='" . $old_name . "'");
		}
	}
	
	private function rename_categories()
	{
		$rows_change = array(
			'kernel' => 1,
			'module' => 2,
			'graphism' => 3,
			'installation' => 4
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			$this->querier->update(PREFIX . 'bugtracker', array('category' => $new_name), "WHERE category='" . $old_name . "'");
			$this->querier->update(PREFIX . 'bugtracker_history', array('old_value' => $new_name), "WHERE old_value='" . $old_name . "'");
			$this->querier->update(PREFIX . 'bugtracker_history', array('new_value' => $new_name), "WHERE new_value='" . $old_name . "'");
		}
	}
	
	private function rename_priorities()
	{
		$rows_change = array(
			'none' => 1,
			'low' => 2,
			'normal' => 3,
			'high' => 4,
			'urgent' => 5,
			'immediate' => 5  //Cette priorité disparait
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			$this->querier->update(PREFIX . 'bugtracker', array('priority' => $new_name), "WHERE priority='" . $old_name . "'");
			$this->querier->update(PREFIX . 'bugtracker_history', array('old_value' => $new_name), "WHERE old_value='" . $old_name . "'");
			$this->querier->update(PREFIX . 'bugtracker_history', array('new_value' => $new_name), "WHERE new_value='" . $old_name . "'");
		}
	}
	
	private function rename_severities()
	{
		$rows_change = array(
			'minor' => 1,
			'major' => 2,
			'critical' => 3
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			$this->querier->update(PREFIX . 'bugtracker', array('severity' => $new_name), "WHERE severity='" . $old_name . "'");
			$this->querier->update(PREFIX . 'bugtracker_history', array('old_value' => $new_name),  "WHERE old_value='" . $old_name . "'");
			$this->querier->update(PREFIX . 'bugtracker_history', array('new_value' => $new_name),  "WHERE new_value='" . $old_name . "'");
		}
	}
	
	private function rename_versions()
	{
		$this->querier->update(PREFIX . 'bugtracker', array('detected_in' => 0, 'fixed_in' => 0), 'WHERE 1');
		$this->querier->update(PREFIX . 'bugtracker_history', array('old_value' => 0, 'new_value' => 0), "WHERE updated_field='detected_in'");
		$this->querier->update(PREFIX . 'bugtracker_history', array('old_value' => 0, 'new_value' => 0), "WHERE updated_field='fixed_in'");
	}
	
	private function rename_fields()
	{
		$rows_change = array(
			'type' => 'type INT(11) DEFAULT 0',
			'category' => 'category INT(11) DEFAULT 0',
			'priority' => 'priority INT(11) DEFAULT 0',
			'severity' => 'severity INT(11) DEFAULT 0',
			'detected_in' => 'detected_in INT(11) DEFAULT 0',
			'fixed_in' => 'fixed_in INT(11) DEFAULT 0'
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'bugtracker CHANGE '. $old_name .' '. $new_name);
		}
	}
	
	private function retrieve_bugs_fix_date()
	{
		$result = $this->querier->select('SELECT id FROM ' . PREFIX . 'bugtracker');
		
		while ($row = $result->fetch())
		{
			$bug_fix_date = $this->querier->select_single_row(PREFIX . 'bugtracker_history', array('MAX(update_date) as fix_date'), "WHERE bug_id=:id AND updated_field='status' AND (new_value='fixed' OR new_value='rejected')", array(
				'id' => $row['id']
			));
			
			$this->querier->update(PREFIX . 'bugtracker', array('fix_date' => $bug_fix_date['fix_date']), "WHERE id=:id", array(
				'id' => $row['id']
			));
		}
	}
}
?>
