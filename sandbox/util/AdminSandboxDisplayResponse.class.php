<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 09
 * @since       PHPBoost 5.1 - 2017 06 16
*/

class AdminSandboxDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $page_title)
	{
		parent::__construct($view);

		$this->add_link(LangLoader::get_message('form.configuration', 'form-lang'), $this->module->get_configuration()->get_admin_main_page());
		$this->add_link(LangLoader::get_message('sandbox.forms', 'common', 'sandbox'), SandboxUrlBuilder::admin_builder());
		$this->add_link(LangLoader::get_message('sandbox.components', 'common', 'sandbox'), SandboxUrlBuilder::admin_component());

		$env = $this->get_graphical_environment();
		$env->set_page_title($page_title);
	}
}
?>
