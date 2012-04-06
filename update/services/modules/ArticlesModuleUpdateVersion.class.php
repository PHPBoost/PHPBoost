<?php
/*##################################################
 *                       ArticlesModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 06, 2012
 *   copyright            : (C) 2012 Kévin MASSY
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
		$this->update_articles_table();
		$this->update_cats_table();
		$this->rename_cats_rows();
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