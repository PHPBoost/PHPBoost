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
		}
		
		$this->delete_old_files();
	}
	
	private function update_articles_table()
	{
		$this->querier->inject('ALTER TABLE ' . PREFIX . 'articles CHANGE contents contents MEDIUMTEXT');
	}
	
	private function delete_old_files()
	{
		$file = new File(PATH_TO_ROOT . '/' . $this->module_id . '/phpboost/ArticlesNewContent.class.php');
		$file->delete();
		
		$file = new File(PATH_TO_ROOT . '/' . $this->module_id . '/phpboost/ArticlesNotation.class.php');
		$file->delete();
	}
}
?>
