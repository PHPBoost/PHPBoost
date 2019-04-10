<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.3 - last update: 2019 04 09
 * @since   	PHPBoost 4.0 - 2014 05 22
*/

class PagesModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('pages');
		
		$this->content_tables = array(PREFIX . 'pages');
	}

	public function execute()
	{
		parent::execute();
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
