<?php
/*##################################################
 *                       ArticlesModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 17, 2014
 *   copyright            : (C) 2014 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
		$this->update_articles_table();
		$this->update_cats_table();
	}
	
	private function update_articles_table()
	{
		$rows_change = array(
			'idcat' => 'id_category INT(11)',
			'views' => 'number_view INT(11)',
			'timestamp' => 'date_created INT(11)',
			'visible' => 'published INT(11)',
			'start' => 'publishing_start_date INT(11)',
			'end' => 'publishing_end_date INT(11)',
			'icon' => 'picture_url VARCHAR(250)',
			'user_id' => 'author_user_id INT(11)',
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			$this->querier->inject('ALTER TABLE '. PREFIX .'articles' .' CHANGE '. $old_name .' '. $new_name);
		}
		
		$this->db_utils->add_column(PREFIX .'articles', 'rewrited_title', array('type' => 'string', 'length' => 250, 'default' => "''"));
		$this->db_utils->add_column(PREFIX .'articles', 'author_name_displayed', array('type' => 'boolean', 'notnull' => 1, 'default' => 1));
		$this->db_utils->add_column(PREFIX .'articles', 'date_updated', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
                $this->db_utils->add_column(PREFIX .'articles', 'notation_enabled', array('type' => 'boolean', 'notnull' => 1, 'default' => 1));
		
		$result = $this->querier->select_rows(PREFIX .'articles', array('id', 'title'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'articles', array(
				'rewrited_title' => Url::encode_rewrite($row['title'])
			), 'WHERE id=:id', array('id' => $row['id']));
		}
	}
	
	private function update_cats_table()
	{
		$this->db_utils->add_column(PREFIX .'articles_cats', 'rewrited_name', array('type' => 'string', 'length' => 250, 'default' => "''"));
	
		$result = $this->querier->select_rows(PREFIX .'articles_cats', array('id', 'name'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'articles_cats', array(
				'rewrited_name' => Url::encode_rewrite($row['name'])
			), 'WHERE id=:id', array('id' => $row['id']));
		}
	}
}
?>