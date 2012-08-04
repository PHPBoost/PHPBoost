<?php
/*##################################################
 *                       EventsModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 13, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class EventsModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	
	public function __construct()
	{
		parent::__construct('events');
		$this->querier = PersistenceContext::get_querier();
	}
	
	public function execute()
	{
		$this->update_comments();
		$this->update_tables();
	}
	
	private function update_tables()
	{
		$this->drop_columns(array('lock_com', 'nbr_com'));
	}
	
	private function update_comments()
	{
		$result = $this->querier->select('SELECT events.id, events.nbr_com, events.lock_com, com.*
		FROM ' . PREFIX . 'events events
		JOIN ' . PREFIX . 'com com ON com.idprov = events.id
		WHERE com.script = \'events\'
		ORDER BY id ASC');
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
					'path' => '/user/contribution_panel.php?id='. $row['id'] .'&amp;com=0',
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
	
	private function drop_columns(array $columns)
	{
		$db_utils = PersistenceContext::get_dbms_utils();
		foreach ($columns as $column_name)
		{
			$db_utils->drop_column(PREFIX .'events', $column_name);
		}
	}
}
?>