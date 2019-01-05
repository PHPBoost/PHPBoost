<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 18
 * @since   	PHPBoost 5.0 - 2017 04 21
*/

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
