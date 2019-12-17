<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 23
 * @since       PHPBoost 3.0 - 2009 12 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminErrorsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page, $page = 1)
	{
		parent::__construct($view);

		$lang = LangLoader::get('admin-errors-common');
		$this->set_title($lang['errors']);

		$this->add_link($lang['logged_errors'], AdminErrorsUrlBuilder::logged_errors());
		$this->add_link($lang['404_errors'], AdminErrorsUrlBuilder::list_404_errors());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, '', $page);
	}
}
?>
