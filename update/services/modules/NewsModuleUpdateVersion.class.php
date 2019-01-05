<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 18
 * @since   	PHPBoost 3.0 - 2012 04 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class NewsModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;

	public function __construct()
	{
		parent::__construct('news');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}

	public function execute()
	{
		if (ModulesManager::is_module_installed('news'))
		{
			$tables = $this->db_utils->list_tables(true);

			if (in_array(PREFIX . 'news', $tables))
				$this->update_news_table();

			$this->update_content();
		}

		$this->delete_old_files();
	}

	private function update_news_table()
	{
		$this->querier->inject('ALTER TABLE ' . PREFIX . 'news CHANGE contents contents MEDIUMTEXT');
	}

	public function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'news');
	}

	private function delete_old_files()
	{
		$folder = new Folder(PATH_TO_ROOT . '/' . $this->module_id . '/fields');
		if ($folder->exists())
			$folder->delete();

		$file = new File(PATH_TO_ROOT . '/' . $this->module_id . '/phpboost/NewsNewContent.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/' . $this->module_id . '/templates/NewsFormFieldSelectSources.tpl');
		$file->delete();
	}
}
?>
