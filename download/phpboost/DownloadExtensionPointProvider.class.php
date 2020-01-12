<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 12
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadExtensionPointProvider extends ModuleExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('download');
	}

	public function feeds()
	{
		return new DownloadFeedProvider();
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), DownloadDisplayCategoryController::get_view());
	}

	public function menus()
	{
		return new ModuleMenus(array(new DownloadModuleMiniMenu()));
	}

	public function scheduled_jobs()
	{
		return new DownloadScheduledJobs();
	}

	public function search()
	{
		return new DownloadSearchable();
	}
}
?>
