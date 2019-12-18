<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 18
 * @since       PHPBoost 5.3 - 2019 12 18
*/

class DefaultAdminDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view)
	{
		parent::__construct($view);

		$this->add_link(LangLoader::get_message('configuration', 'admin-common'), $this->module->get_configuration()->get_admin_main_page());
		
		if ($this->module->get_configuration()->get_documentation())
			$this->add_link(LangLoader::get_message('module.documentation', 'admin-modules-common'), $this->module->get_configuration()->get_documentation());

		$this->get_graphical_environment()->set_page_title($this->title);
	}
}
?>
