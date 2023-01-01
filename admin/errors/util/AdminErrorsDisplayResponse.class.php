<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 02
 * @since       PHPBoost 3.0 - 2009 12 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminErrorsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page, $page = 1)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs();
		$this->set_title($lang['admin.errors']);

		$this->add_link($lang['admin.logged.errors'], AdminErrorsUrlBuilder::logged_errors());
		$this->add_link($lang['admin.404.errors'], AdminErrorsUrlBuilder::list_404_errors());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, '', $page);
	}
}
?>
