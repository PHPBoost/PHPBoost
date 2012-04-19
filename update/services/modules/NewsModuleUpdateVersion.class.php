<?php
/*##################################################
 *                       NewsModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 05, 2012
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

class NewsModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('news');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$this->update_comments();
		$this->update_news_table();
		$this->update_cats_table();
		$this->rename_cats_rows();
	}
	
	private function update_comments()
	{
		$result = $this->querier->select('SELECT news.id, news.nbr_com, news.lock_com, com.*
		FROM ' . PREFIX . 'news news
		JOIN ' . PREFIX . 'com com ON com.idprov = news.id
		WHERE com.script = \'news\'');
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
					'path' => '/news/news.php?id='. $row['id'] .'&com=0',
				));
				$id_topic = $topic->get_last_inserted_id();
			}
			
			$this->querier->insert(PREFIX . 'comments', array(
				'id_topic' => $id_topic,
				'user_id' => $row['user_id'],
				'name_visitor' => $row['login'],
				'ip_visitor' => $row['user_ip'],
				'timestamp' => $row['timestamp'],
				'message' => $row['contents']
			));
		}
	}
	
	private function update_news_table()
	{
		$this->db_utils->drop_column(PREFIX .'news', 'nbr_com');
		$this->db_utils->drop_column(PREFIX .'news', 'lock_com');
		
		$this->db_utils->add_column(PREFIX .'news', 'compt', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		$this->db_utils->add_column(PREFIX .'news', 'sources', array('type' => 'text', 'length' => 65000));
		
		$this->querier->update(PREFIX .'news', array(
			'compt' => 0,
			'sources' => serialize(array())
		), 'WHERE 1');
	}

	private function update_cats_table()
	{
		$this->db_utils->add_column(PREFIX .'news_cat', 'id_parent', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		$this->db_utils->add_column(PREFIX .'news_cat', 'c_order', array('type' => 'integer', 'length' => 11, 'unsigned' => 1, 'notnull' => 1, 'default' => 0));
		$this->db_utils->add_column(PREFIX .'news_cat', 'visible', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		$this->db_utils->add_column(PREFIX .'news_cat', 'auth', array('type' => 'text', 'length' => 65000));
		
		$i = 0;
		$result = $this->querier->select_rows(PREFIX .'news_cat', array('*'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX .'news_cat', array(
				'id_parent' => 0,
				'c_order' => $i,
				'visible' => 1,
				'auth' => serialize(array('r-1' => 1, 'r0' => 3, 'r1' => 15))
			), 'WHERE id=:id', array('id' => $row['id']));
			$i++;
		}
	}
	
	private function rename_cats_rows()
	{
		$rows_change = array(
			'icon' => 'image VARCHAR(255) NOT NULL',
			'contents' => 'description TEXT DEFAULT NULL'
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			$this->querier->inject('ALTER TABLE '. PREFIX .'news_cat' .' CHANGE '. $old_name .' '. $new_name);
		}
	}
}
?>