<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 12 02
 * @since       PHPBoost 4.1 - 2015 05 22
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSmileysDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page, $page = 1)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs();
		$this->set_title($lang['admin.smileys.management']);

		$this->add_link($lang['admin.smileys.management'], AdminSmileysUrlBuilder::management());
		$this->add_link($lang['admin.add.smileys'], AdminSmileysUrlBuilder::add());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, '', $page);
	}
}
?>
