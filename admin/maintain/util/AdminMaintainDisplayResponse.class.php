<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 22
 * @since       PHPBoost 4.1 - 2014 09 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminMaintainDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$title = LangLoader::get_message('admin.maintenance', 'admin-lang');
		$this->set_title($title);

		$this->add_link($title, AdminMaintainUrlBuilder::maintain());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
