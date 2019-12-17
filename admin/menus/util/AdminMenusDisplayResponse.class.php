<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 11 07
 * @since       PHPBoost 3.0 - 2009 10 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminMenusDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view)
	{
		global $LANG;

		parent::__construct($view);

		$view->add_lang($LANG);
		$this->set_title($LANG['menus_management']);

		$this->add_link($LANG['menu_configurations'], MenuUrlBuilder::menu_configuration_list()->relative());
		$this->add_link($LANG['menus'], MenuUrlBuilder::menu_list()->relative());
	}
}
?>
