<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 06 29
 * @since       PHPBoost 3.0 - 2012 04 16
 * @contributor xela <xela@phpboost.com>
 * @contributor Maxence CAUDERLIER <mxkoder@phpboost.com>
*/

class BugtrackerExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('bugtracker');
	}

	/**
	 * @method Get comments
	 */
	public function comments()
	{
		return new CommentsTopics(array(new BugtrackerCommentsTopic()));
	}

	/**
	 * @method Get css files
	 */
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('bugtracker.css');
		return $module_css_files;
	}

	public function js_files()
	{
		$js_file = new ModuleJsFiles();
		$js_file->adding_running_module_displayed_file('chart.min.js');
		return $js_file;
	}

	/**
	 * @method Get feeds
	 */
	public function feeds()
	{
		return new BugtrackerFeedProvider();
	}

	/**
	 * @method Get home page
	 */
	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), BugtrackerUnsolvedListController::get_view());
	}

	/**
	 * @method Get search form
	 */
	public function search()
	{
		return new BugtrackerSearchable();
	}

	/**
	 * @method Get sitemap
	 */
	public function sitemap()
	{
		return new BugtrackerSitemapExtensionPoint();
	}

	/**
	 * @method Get tree links
	 */
	public function tree_links()
	{
		return new BugtrackerTreeLinks();
	}

	/**
	 * @method Get url mappings
	 */
	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/bugtracker/index.php')));
	}
}
?>
