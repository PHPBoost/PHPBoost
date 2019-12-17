<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 09
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class DownloadExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('download');
	}

	public function comments()
	{
		return new CommentsTopics(array(new DownloadCommentsTopic()));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('download_mini.css');
		$module_css_files->adding_running_module_displayed_file('download.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new DownloadFeedProvider();
	}

	public function home_page()
	{
		return new DownloadHomePageExtensionPoint();
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

	public function sitemap()
	{
		return new DefaultSitemapCategoriesModule('download');
	}

	public function tree_links()
	{
		return new DownloadTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/download/index.php')));
	}
}
?>
