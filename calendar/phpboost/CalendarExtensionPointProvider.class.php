<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 06
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class CalendarExtensionPointProvider extends ModuleExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('calendar');
	}

	public function feeds()
	{
		return new CalendarFeedProvider();
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), CalendarDisplayCategoryController::get_view());
	}

	public function menus()
	{
		return new ModuleMenus(array(new CalendarModuleMiniMenu()));
	}

	public function scheduled_jobs()
	{
		return new CalendarScheduledJobs();
	}

	public function search()
	{
		return new CalendarSearchable();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(
			new DispatcherUrlMapping('/calendar/index.php', 'events_list/today/?$', '', 'events_list/&display_current_day_events=1', true),
			new DispatcherUrlMapping('/calendar/index.php')
		));
	}
}
?>
