<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.3 - last update: 2019 04 09
 * @since   	PHPBoost 3.0 - 2012 04 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class NewsModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('news');
		
		$this->content_tables = array(PREFIX . 'news');
		$this->delete_old_files_list = array(
			'/phpboost/NewsNewContent.class.php',
			'/templates/NewsFormFieldSelectSources.tpl'
		);
		$this->delete_old_folders_list = array('/fields');
	}

	public function execute()
	{
		if (ModulesManager::is_module_installed('news'))
		{
			$tables = $this->db_utils->list_tables(true);

			if (in_array(PREFIX . 'news', $tables))
				$this->update_news_table();
		}
	}

	private function update_news_table()
	{
		$this->querier->inject('ALTER TABLE ' . PREFIX . 'news CHANGE contents contents MEDIUMTEXT');
	}
}
?>
