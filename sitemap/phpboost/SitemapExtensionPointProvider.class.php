<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 27
 * @since       PHPBoost 3.0 - 2009 12 10
 * @contributor xela <xela@phpboost.com>
*/

class SitemapExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('sitemap');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('sitemap.css');
		return $module_css_files;
	}

	public function commands()
	{
		return new CLICommandsList(array('generate-sitemap' => 'CLIGenerateSitemapCommand'));
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), ViewSitemapController::get_view());
	}

	public function scheduled_jobs()
	{
		return new SitemapScheduledJobs();
	}

	public function tree_links()
	{
		return new SitemapTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/sitemap/index.php')));
	}
}
?>
