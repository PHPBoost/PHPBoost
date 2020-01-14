<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 14
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GuestbookExtensionPointProvider extends ModuleExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('guestbook');
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), GuestbookController::get_view());
	}

	public function menus()
	{
		return new ModuleMenus(array(new GuestbookModuleMiniMenu()));
	}

	public function tree_links()
	{
		return new DefaultTreeLinks($this->get_id(), false);
	}
}
?>
