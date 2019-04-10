<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.3 - last update: 2019 04 09
 * @since   	PHPBoost 4.0 - 2014 02 11
*/
#################################################*/

class CalendarModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('calendar');
		
		$this->content_tables = array(PREFIX . 'calendar_events_content');
		$this->delete_old_files_list = array('/phpboost/CalendarNewContent.class.php');
	}

	public function execute()
	{
		parent::execute();
		if (ModulesManager::is_module_installed('calendar'))
		{
			$tables = $this->db_utils->list_tables(true);

			if (in_array(PREFIX . 'calendar_events_content', $tables))
				$this->update_calendar_events_content_table();
		}
	}

	private function update_calendar_events_content_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'calendar_events_content');

		if (!isset($columns['cancelled']))
			$this->db_utils->add_column(PREFIX . 'calendar_events_content', 'cancelled', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
	}
}
?>
