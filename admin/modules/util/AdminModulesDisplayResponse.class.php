<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2015 11 07
 * @since       PHPBoost 3.0 - 2011 09 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminModulesDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('admin-modules-common');
		$this->set_title($lang['modules.module_management']);

		$this->add_link($lang['modules.installed_modules'], AdminModulesUrlBuilder::list_installed_modules());
		$this->add_link($lang['modules.add_module'], AdminModulesUrlBuilder::add_module());
		$this->add_link($lang['modules.update_module'], AdminModulesUrlBuilder::update_module());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}


?>
