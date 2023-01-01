<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 28
 * @since       PHPBoost 2.0 - 2008 10 20
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

define('MEDIA_MAX_SEARCH_RESULTS', 100);

class MediaExtensionPointProvider extends ExtensionPointProvider
{
	function __construct()
	{
		parent::__construct('media');
	}

	public function comments()
	{
		return new CommentsTopics(array(new MediaCommentsTopic()));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('media.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new MediaFeedProvider();
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), MediaDisplayCategoryController::get_view());
	}

	public function search()
	{
		return new MediaSearchable();
	}

	public function sitemap()
	{
		return new DefaultSitemapCategoriesModule('media');
	}

	public function tree_links()
	{
		return new MediaTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/media/index.php')));
	}
}
?>
