<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 03
 * @since       PHPBoost 4.0 - 2013 03 19
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ArticlesExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('articles');
	}

	public function comments()
	{
		return new CommentsTopics(array(new ArticlesCommentsTopic()));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('articles.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new ArticlesFeedProvider();
	}

	public function home_page()
	{
		return new ArticlesHomePageExtensionPoint();
	}

	public function scheduled_jobs()
	{
		return new ArticlesScheduledJobs();
	}

	public function search()
	{
		return new ArticlesSearchable();
	}

	public function sitemap()
	{
		return new DefaultSitemapCategoriesModule('articles');
	}

	public function tree_links()
	{
		return new ArticlesTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/articles/index.php')));
	}
}
?>
