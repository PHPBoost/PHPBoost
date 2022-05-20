<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 04
 * @since       PHPBoost 6.0 - 2021 10 22
*/

class AdminHistoryDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $page_title)
	{
		parent::__construct($view);

		$this->add_link($this->module->get_configuration()->get_name(), HistoryUrlBuilder::display_history());
		$this->add_link(LangLoader::get_message('form.configuration', 'form-lang'), $this->module->get_configuration()->get_admin_main_page());

		$this->get_graphical_environment()->set_page_title($page_title);
	}
}
?>
