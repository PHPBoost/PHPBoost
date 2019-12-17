<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 02 24
 * @since       PHPBoost 4.1 - 2015 09 30
 * @contributor xela <xela@phpboost.com>
*/

class AdminDatabaseDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('common', 'database');
		$this->set_title($lang['module_title']);

		$this->add_link($lang['database.actions.database_management'], DatabaseUrlBuilder::database_management());
		$this->add_link($lang['database.actions.db_sql_queries'], DatabaseUrlBuilder::db_sql_queries());
		$this->add_link(LangLoader::get_message('configuration', 'admin-common'), DatabaseUrlBuilder::configuration());
		$this->add_link(LangLoader::get_message('module.documentation', 'admin-modules-common'), ModulesManager::get_module('database')->get_configuration()->get_documentation());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, $lang['module_title']);
	}
}
?>
