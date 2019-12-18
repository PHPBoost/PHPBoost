<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 18
 * @since       PHPBoost 3.0 - 2012 10 18
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class AdminBugtrackerDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $page_title)
	{
		parent::__construct($view);

		$this->add_link(LangLoader::get_message('configuration', 'admin-common'), $this->module->get_configuration()->get_admin_main_page());
		$this->add_link(LangLoader::get_message('authorizations', 'common'), BugtrackerUrlBuilder::authorizations());
		$this->add_link(LangLoader::get_message('module.documentation', 'admin-modules-common'), $this->module->get_configuration()->get_documentation());

		$this->get_graphical_environment()->set_page_title($page_title);
	}
}
?>
