<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 3.0 - 2012 10 18
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminBugtrackerDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $page_title)
	{
		parent::__construct($view);
		$lang = LangLoader::get_all_langs();

		$this->add_link($lang['form.configuration'], $this->module->get_configuration()->get_admin_main_page());
		$this->add_link($lang['form.authorizations'], BugtrackerUrlBuilder::authorizations());
		$this->add_link($lang['form.documentation'], $this->module->get_configuration()->get_documentation());

		$this->get_graphical_environment()->set_page_title($page_title);
	}
}
?>
