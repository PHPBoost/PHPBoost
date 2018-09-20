<?php
/*##################################################
 *                       PagesModuleUpdateVersion.class.php
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
		if (ModulesManager::is_module_installed('pages'))
		{
			$tables = $this->db_utils->list_tables(true);
			
			if (in_array(PREFIX . 'pages', $tables))
				$this->update_pages_table();
		}
	}
	
	private function update_pages_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'pages');
		
		$this->querier->inject('ALTER TABLE ' . PREFIX . 'pages CHANGE contents contents MEDIUMTEXT');
	}
}
?>
