<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class CalendarExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('calendar');
	}

	public function comments()
	{
		return new CommentsTopics(array(new CalendarCommentsTopic()));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('calendar.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new CalendarFeedProvider();
	}

	public function home_page()
	{
		return new CalendarHomePageExtensionPoint();
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

	public function sitemap()
	{
		return new DefaultSitemapCategoriesModule('calendar');
	}

	public function tree_links()
	{
		return new CalendarTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/calendar/index.php')));
	}
}
?>
