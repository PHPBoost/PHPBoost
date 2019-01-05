<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 18
 * @since   	PHPBoost 4.0 - 2014 02 11
*/
#################################################*/

class CalendarModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('calendar');
	}

	public function execute()
	{
		if (ModulesManager::is_module_installed('calendar'))
		{
			$this->update_content();
		}

		$this->delete_old_files();
	}

	public function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'calendar_events_content');
	}

	private function delete_old_files()
	{
		$file = new File(PATH_TO_ROOT . '/' . $this->module_id . '/phpboost/CalendarNewContent.class.php');
		$file->delete();
	}
}
?>
