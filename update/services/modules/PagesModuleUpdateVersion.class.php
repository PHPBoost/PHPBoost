<?php
/*##################################################
 *                       PagesModuleUpdateVersion.class.php
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

class PagesModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('pages');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$this->update_comments();
		$this->update_tables();
	}
	
	private function update_tables()
	{
		$this->drop_columns(array('lock_com', 'nbr_com'));
		$this->db_utils->add_column(PREFIX .'pages', 'display_print_link', array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0));
	}
	
	private function update_comments()
	{
		$result = $this->querier->select('SELECT pages.id, pages.nbr_com, pages.lock_com, com.*
		FROM ' . PREFIX . 'pages pages
		JOIN ' . PREFIX . 'com com ON com.idprov = pages.id
		WHERE com.script = \'pages\'
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
					'path' => '/pages/pages.php?id='. $row['id'] .'&amp;com=0',
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
		foreach ($columns as $column_name)
		{
			$this->db_utils->drop_column(PREFIX .'pages', $column_name);
		}
	}
}
?>