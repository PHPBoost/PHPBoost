<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 27
 * @since       PHPBoost 5.0 - 2017 04 21
 * @contributor xela <xela@phpboost.com>
*/

class BugtrackerModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('bugtracker');

		$this->delete_old_files_list = array(
			'/phpboost/BugtrackerHomePageExtensionPoint.class.php'
		);
	}

	protected function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'bugtracker');
		UpdateServices::update_table_content(PREFIX . 'bugtracker', 'reproduction_method');
		UpdateServices::update_table_content(PREFIX . 'bugtracker_history', 'old_value');
		UpdateServices::update_table_content(PREFIX . 'bugtracker_history', 'new_value');
	}
}
?>
