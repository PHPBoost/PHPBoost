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
		if (ModulesManager::is_module_installed('articles'))
		{
			$tables = $this->db_utils->list_tables(true);
			
			if (in_array(PREFIX . 'articles', $tables))
				$this->update_articles_table();
			
			$this->update_content();
			
			$this->update_serialized_data();
		}
		
		$this->delete_old_files();
	}
	
	private function update_articles_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'articles');
		
		if (isset($columns['notation_enabled']))
			$this->db_utils->drop_column(PREFIX . 'articles', 'notation_enabled');
		
		if (!isset($columns['author_custom_name']))
			$this->db_utils->add_column(PREFIX . 'articles', 'author_custom_name', array('type' =>  'string', 'length' => 255, 'default' => "''"));
	}
	
	public function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'articles');
	}
	
	public function update_serialized_data()
	{
		$result = $this->querier->select('SELECT id, sources FROM ' . PREFIX . 'articles');
		while($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'articles', array('sources' => UpdateServices::recount_serialized_bytes($row['sources'])), 'WHERE id=:id', array('id' => $row['id']));
		}
		$result->dispose();
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/controllers/AdminArticlesManageController.class.php'));
		$file->delete();
	}
}
?>
