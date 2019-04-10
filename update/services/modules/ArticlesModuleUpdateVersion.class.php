<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version   	PHPBoost 5.3 - last update: 2019 04 09
 * @since   	PHPBoost 4.0 - 2014 02 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ArticlesModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('articles');
		
		$this->content_tables = array(PREFIX . 'articles');
		$this->delete_old_files_list = array(
			'/phpboost/ArticlesNewContent.class.php',
			'/phpboost/ArticlesNotation.class.php',
			'/templates/ArticlesFormFieldSelectSources.tpl'
		);
		$this->delete_old_folders_list = array('/fields');
	}

	public function execute()
	{
		parent::execute();
		if (ModulesManager::is_module_installed('articles'))
		{
			$tables = $this->db_utils->list_tables(true);

			if (in_array(PREFIX . 'articles', $tables))
				$this->update_articles_table();
		}
	}

	private function update_articles_table()
	{
		$this->querier->inject('ALTER TABLE ' . PREFIX . 'articles CHANGE contents contents MEDIUMTEXT');
	}
}
?>
