<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 18
 * @since   	PHPBoost 4.0 - 2014 05 22
*/

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

			$this->update_content();
		}
	}

	private function update_pages_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'pages');

		$this->querier->inject('ALTER TABLE ' . PREFIX . 'pages CHANGE contents contents MEDIUMTEXT');
	}

	public function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'pages');
	}
}
?>
