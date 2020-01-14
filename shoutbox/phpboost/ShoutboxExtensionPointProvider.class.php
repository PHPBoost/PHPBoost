<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 14
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class ShoutboxExtensionPointProvider extends ModuleExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('shoutbox');
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), ShoutboxHomeController::get_view());
	}

	public function menus()
	{
		return new ModuleMenus(array(new ShoutboxModuleMiniMenu()));
	}

	public function scheduled_jobs()
	{
		return new ShoutboxScheduledJobs();
	}

	public function tree_links()
	{
		return new DefaultTreeLinks($this->get_id(), false);
	}
}
?>
