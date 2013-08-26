<?php
/*##################################################
 *                       ModulesKernelUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 26, 2012
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

class ModulesKernelUpdateVersion extends KernelUpdateVersion
{
	private $querier;
	
	public function __construct()
	{
		parent::__construct('modules');
		$this->querier = PersistenceContext::get_querier();
	}
	
	public function execute()
	{
		$results = $this->querier->select_rows(PREFIX .'modules', array('*'));
		
		$modules_config = ModulesConfig::load();
		foreach ($results as $row)
		{
			try {
				$modules_config->add_module(new Module($row['name'], (bool)$row['activ'], unserialize($row['auth'])));
			} catch (IOException $e) {
			}
		}
		
		$default_modules = array('TinyMCE', 'BBCode', 'LangsSwitcher', 'ThemesSwitcher', 'sitemap');
		foreach ($default_modules as $module_name)
		{
			$modules_config->add_module(new Module($module_name, true));
		}
		
		ModulesConfig::save();
		
		$this->drop_modules_table();
	}
	
	private function drop_modules_table()
	{
		PersistenceContext::get_dbms_utils()->drop(array(PREFIX . 'modules'));
	}
}
?>