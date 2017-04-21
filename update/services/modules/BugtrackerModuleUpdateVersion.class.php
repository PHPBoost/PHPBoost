<?php
/*##################################################
 *                       BugtrackerModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 21, 2017
 *   copyright            : (C) 2017 Julien BRISWALTER
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

class BugtrackerModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('bugtracker');
	}
	
	public function execute()
	{
		if (ModulesManager::is_module_installed('bugtracker'))
		{
			$this->update_content();
		}
	}
	
	public function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'bugtracker');
		UpdateServices::update_table_content(PREFIX . 'bugtracker', 'reproduction_method');
		UpdateServices::update_table_content(PREFIX . 'bugtracker_history', 'old_value');
		UpdateServices::update_table_content(PREFIX . 'bugtracker_history', 'new_value');
	}
}
?>
