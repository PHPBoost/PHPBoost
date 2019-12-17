<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 04
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class NewsExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('news');
	}

	public function comments()
	{
		return new CommentsTopics(array(new NewsCommentsTopic()));
	}

	public function feeds()
	{
		return new NewsFeedProvider();
	}

	public function home_page()
	{
		return new NewsHomePageExtensionPoint();
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('news.css');
		return $module_css_files;
	}

	public function scheduled_jobs()
	{
		return new NewsScheduledJobs();
	}

	public function search()
	{
		return new NewsSearchable();
	}

	public function sitemap()
	{
		return new DefaultSitemapCategoriesModule('news');
	}

	public function tree_links()
	{
		return new NewsTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/news/index.php')));
	}
}
?>
