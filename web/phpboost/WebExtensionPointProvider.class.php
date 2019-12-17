<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 09
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Kevin MASSY <reidlos@phpboost.com>
*/

class WebExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('web');
	}

	public function comments()
	{
		return new CommentsTopics(array(new WebCommentsTopic()));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('web_mini.css');
		$module_css_files->adding_running_module_displayed_file('web.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new WebFeedProvider();
	}

	public function home_page()
	{
		return new WebHomePageExtensionPoint();
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

	public function sitemap()
	{
		return new DefaultSitemapCategoriesModule('web');
	}

	public function tree_links()
	{
		return new WebTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/web/index.php')));
	}
}
?>
