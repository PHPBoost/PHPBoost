<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 29
 * @since       PHPBoost 2.0 - 2008 02 24
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

define('FORUM_MAX_SEARCH_RESULTS', 50);

class ForumExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('forum');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('forum.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new ForumFeedProvider();
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), ForumHomeController::get_view());
	}

	public function scheduled_jobs()
	{
		return new ForumScheduledJobs();
	}

	public function search()
	{
		return new ForumSearchable();
	}

	public function sitemap()
	{
		return new DefaultSitemapCategoriesModule('forum');
	}

	public function tree_links()
	{
		return new ForumTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/forum/index.php')));
	}

	public function user()
	{
		return new ForumUserExtensionPoint();
	}
}
?>
