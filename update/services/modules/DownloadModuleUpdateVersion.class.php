<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 18
 * @since   	PHPBoost 4.0 - 2014 05 22
*/

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

		if (!isset($columns['software_version']))
			$this->db_utils->add_column(PREFIX . 'download', 'software_version', array('type' => 'string', 'length' => 30, 'notnull' => 1, 'default' => "''"));
	}

	public function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'download');
	}

	private function delete_old_files()
	{
		$file = new File(PATH_TO_ROOT . '/' . $this->module_id . '/phpboost/DownloadNewContent.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/' . $this->module_id . '/phpboost/DownloadNotation.class.php');
		$file->delete();
	}
}
?>
