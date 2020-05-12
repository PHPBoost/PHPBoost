<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 05 12
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class WebExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), WebDisplayCategoryController::get_view());
	}
}
?>
