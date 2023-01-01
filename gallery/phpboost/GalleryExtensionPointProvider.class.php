<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 29
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

if (defined('PHPBOOST') !== true) exit;

class GalleryExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('gallery');
	}

	public function comments()
	{
		return new CommentsTopics(array(new GalleryCommentsTopic()));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('gallery.css');
		$module_css_files->adding_always_displayed_file('gallery_mini.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new GalleryFeedProvider();
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), GalleryDisplayCategoryController::get_view());
	}

	public function menus()
	{
		return new ModuleMenus(array(new GalleryModuleMiniMenu()));
	}

	public function sitemap()
	{
		return new DefaultSitemapCategoriesModule('gallery');
	}

	public function tree_links()
	{
		return new GalleryTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/gallery/index.php')));
	}
}
?>
