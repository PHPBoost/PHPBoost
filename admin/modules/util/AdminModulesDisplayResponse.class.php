<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 06
 * @since       PHPBoost 3.0 - 2011 09 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminModulesDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs();
		$this->set_title($lang['addon.modules.management']);

		$this->add_link($lang['addon.modules.installed'], AdminModulesUrlBuilder::list_installed_modules());
		$this->add_link($lang['addon.modules.add'], AdminModulesUrlBuilder::add_module());
		$this->add_link($lang['addon.modules.update'], AdminModulesUrlBuilder::update_module());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}


?>
