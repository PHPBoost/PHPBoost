<?php
/*##################################################
 *                       NewsModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 05, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
		$this->update_news_table();
		$this->update_cats_table();
	}
	
	private function update_news_table()
	{
		$rows_change = array(
			'idcat' => 'id_category INT(11)',
			'title' => 'name VARCHAR(100)',
			'extend_contents' => 'short_contents TEXT',
			'timestamp' => 'creation_date INT(11)',
			'visible' => 'approbation_type INT(11)',
			'start' => 'start_date INT(11)',
			'end' => 'end_date INT(11)',
			'img' => 'picture_url VARCHAR(250)',
			'user_id' => 'author_user_id INT(11)',
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			$this->querier->inject('ALTER TABLE '. PREFIX .'news' .' CHANGE '. $old_name .' '. $new_name);
		}

		$this->db_utils->drop_column(PREFIX .'news', 'alt');
		$this->db_utils->drop_column(PREFIX .'news', 'compt');
		
		$this->db_utils->add_column(PREFIX .'news', 'rewrited_name', array('type' => 'string', 'length' => 250, 'default' => "''"));
		$this->db_utils->add_column(PREFIX .'news', 'updated_date', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		$this->db_utils->add_column(PREFIX .'news', 'top_list_enabled', array('type' => 'boolean', 'notnull' => 1, 'notnull' => 1, 'default' => 0));
		
		$result = $this->querier->select_rows(PREFIX .'news', array('id', 'name'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'news', array(
				'rewrited_name' => Url::encode_rewrite($row['name'])
			), 'WHERE id=:id', array('id' => $row['id']));
		}
	}
	
	private function update_cats_table()
	{
		$this->querier->inject('RENAME TABLE '. PREFIX .'news_cat' .' TO '. PREFIX .'news_cats');
		$this->db_utils->add_column(PREFIX .'news_cats', 'rewrited_name', array('type' => 'string', 'length' => 250, 'default' => "''"));
	
		$result = $this->querier->select_rows(PREFIX .'news_cats', array('id', 'name'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'news_cats', array(
				'rewrited_name' => Url::encode_rewrite($row['name'])
			), 'WHERE id=:id', array('id' => $row['id']));
		}
	}
}
?>