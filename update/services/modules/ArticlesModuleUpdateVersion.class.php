<?php
/*##################################################
 *                       ArticlesModuleUpdateVersion.class.php
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

class ArticlesModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('articles');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$this->update_notes();
		$this->update_comments();
		$this->update_articles_table();
		$this->update_cats_table();
		$this->rename_cats_rows();
	}
	
	private function update_notes()
	{
		$result = $this->querier->select_rows(PREFIX .'articles', array('id', 'note', 'nbrnote', 'users_note'));
		while ($row = $result->fetch())
		{
			if (!empty($row['note']) && !empty($row['nbrnote']))
			{
				$this->querier->insert(PREFIX . 'average_notes', array(
					'module_name' => 'articles',
					'id_in_module' => $row['id'],
					'average_notes' => $row['note'],
					'number_notes' => $row['nbrnote'],
				));
				
				$note = $row['note'] / $row['nbrnote'];
				$users_note = explode('/', $row['users_note']);
				foreach ($users_note as $user_id)
				{
					$this->querier->insert(PREFIX . 'note', array(
						'module_name' => 'articles',
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
		$result = $this->querier->select('SELECT articles.id, articles.idcat, articles.nbr_com, articles.lock_com, com.*
		FROM ' . PREFIX . 'articles articles
		JOIN ' . PREFIX . 'com com ON com.idprov = articles.id
		WHERE com.script = \'articles\'');
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
					'path' => '/articles/articles.php?id='. $row['id'] .'&cat='. $row['idcat'] .'&com=0',
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
	
	private function update_articles_table()
	{
		$this->drop_articles_columns(array('lock_com', 'nbr_com', 'note', 'nbrnote', 'users_note'));
		
		$this->db_utils->add_column(PREFIX .'articles', 'description', array('type' => 'text', 'length' => 65000));
		$this->db_utils->add_column(PREFIX .'articles', 'sources', array('type' => 'text', 'length' => 65000));
		
		$this->querier->update(PREFIX .'articles', array(
			'description' => '',
			'sources' => serialize(array())
		), 'WHERE 1');
	}
	
	private function drop_articles_columns(array $columns)
	{
		foreach ($columns as $column_name)
		{
			$this->db_utils->drop_column(PREFIX .'articles', $column_name);
		}
	}

	private function update_cats_table()
	{
		$this->db_utils->drop_column(PREFIX .'articles_cats', 'id_left');
		$this->db_utils->drop_column(PREFIX .'articles_cats', 'id_right');
		$this->db_utils->drop_column(PREFIX .'articles_cats', 'level');
		$this->db_utils->drop_column(PREFIX .'articles_cats', 'nbr_articles_visible');
		$this->db_utils->drop_column(PREFIX .'articles_cats', 'nbr_articles_unvisible');
		
		$this->db_utils->add_column(PREFIX .'articles_cats', 'id_parent', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		$this->db_utils->add_column(PREFIX .'articles_cats', 'c_order', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));

		$i = 0;
		$result = $this->querier->select_rows(PREFIX .'articles_cats', array('*'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX .'articles_cats', array(
				'id_parent' => 0,
				'c_order' => $i
			), 'WHERE id=:id', array('id' => $row['id']));
			$i++;
		}
	}
	
	private function rename_cats_rows()
	{
		$rows_change = array(
			'icon' => 'image VARCHAR(255) NULL DEFAULT NULL',
			'contents' => 'description TEXT NULL DEFAULT NULL',
			'aprob' => 'visible TINYINT(1) NOT NULL DEFAULT \'0\'',
			'auth' => 'auth LONGTEXT NULL DEFAULT NULL',
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			$this->querier->inject('ALTER TABLE '. PREFIX .'articles_cats' .' CHANGE '. $old_name .' '. $new_name);
		}
	}
}
?>