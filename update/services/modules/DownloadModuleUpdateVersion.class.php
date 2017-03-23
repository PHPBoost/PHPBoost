<?php
/*##################################################
 *                       DownloadModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : May 22, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('download');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		if (ModulesManager::is_module_installed('download'))
		{
			$tables = $this->db_utils->list_tables(true);
			
			if (in_array(PREFIX . 'download', $tables))
				$this->update_download_table();
			
			$this->update_content();
		}
		
		$this->delete_old_files();
	}
	
	private function update_download_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'download');
		
		if (!isset($columns['number_view']))
			$this->db_utils->add_column(PREFIX . 'download', 'number_view', array('type' => 'integer', 'length' => 11, 'default' => 0));
		
		$rows_change = array(
			'author_display_name' => 'author_custom_name VARCHAR(255)'
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			if (isset($columns[$old_name]) && isset($columns[$new_name]))
				$this->db_utils->drop_column(PREFIX . 'download', $columns[$new_name]);
			
			if (isset($columns[$old_name]))
				$this->querier->inject('ALTER TABLE ' . PREFIX . 'download CHANGE ' . $old_name . ' ' . $new_name);
		}
	}
	
	public function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'download');
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/controllers/AdminDownloadManageController.class.php'));
		$file->delete();
	}
}
?>
