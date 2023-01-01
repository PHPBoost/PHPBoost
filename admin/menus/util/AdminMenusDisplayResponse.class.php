<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 02
 * @since       PHPBoost 3.0 - 2009 10 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminMenusDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view)
	{
		$lang = LangLoader::get_all_langs();

		parent::__construct($view);

		$this->set_title($lang['menu.menus.management']);

		$this->add_link($lang['menu.configuration'], MenuUrlBuilder::menu_configuration_list()->relative());
		$this->add_link($lang['menu.menus'], MenuUrlBuilder::menu_list()->relative());
	}
}
?>
