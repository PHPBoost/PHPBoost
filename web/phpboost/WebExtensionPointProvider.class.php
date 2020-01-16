<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 16
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class WebExtensionPointProvider extends ModuleExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('web');
	}

	public function feeds()
	{
		return new WebFeedProvider();
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), WebDisplayCategoryController::get_view());
	}

	public function menus()
	{
		return new ModuleMenus(array(new WebModuleMiniMenu()));
	}

	public function scheduled_jobs()
	{
		return new WebScheduledJobs();
	}

	public function search()
	{
		return new WebSearchable();
	}
}
?>
