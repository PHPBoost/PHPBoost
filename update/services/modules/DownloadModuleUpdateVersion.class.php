<?php
/*##################################################
 *                       DownloadModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 06, 2012
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

class DownloadModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	
	public function __construct()
	{
		parent::__construct('download');
		$this->querier = PersistenceContext::get_querier();
	}
	
	public function execute()
	{
		$this->update_notes();
		$this->update_comments();
		$this->update_tables();
	}
	
	private function update_tables()
	{
		$this->drop_columns(array('lock_com', 'nbr_com', 'note', 'nbrnote', 'users_note'));
	}
	
	private function update_notes()
	{
		$result = $this->querier->select_rows(PREFIX .'download', array('id', 'note', 'nbrnote', 'users_note'));
		while ($row = $result->fetch())
		{
			if (!empty($row['note']) && !empty($row['nbrnote']))
			{
				$this->querier->insert(PREFIX . 'average_notes', array(
					'module_name' => 'download',
					'id_in_module' => $row['id'],
					'average_notes' => $row['note'],
					'number_notes' => $row['nbrnote'],
				));
				
				$note = $row['note'] / $row['nbrnote'];
				$users_note = explode('/', $row['users_note']);
				foreach ($users_note as $user_id)
				{
					$this->querier->insert(PREFIX . 'note', array(
						'module_name' => 'download',
						'id_in_module' => $row['id'],
						'user_id' => $user_id,
						'note' => $note
					));
				}
			}
		}
	}
	
	private function update_comments()
	{
		$result = $this->querier->select('SELECT download.id, download.nbr_com, download.lock_com, com.*
		FROM ' . PREFIX . 'download download
		JOIN ' . PREFIX . 'com com ON com.idprov = download.id
		WHERE com.script = \'download\'
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
					'path' => '/download/download.php?id='. $row['id'] .'&com=0',
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
			$db_utils->drop_column(PREFIX .'download', $column_name);
		}
	}
}
?>