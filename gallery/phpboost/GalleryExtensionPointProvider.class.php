<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 05
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
		$module_css_files->adding_always_displayed_file('gallery.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new GalleryFeedProvider();
	}

	public function home_page()
	{
		return new GalleryHomePageExtensionPoint();
	}

	public function menus()
	{
		return new ModuleMenus(array(new GalleryModuleMiniMenu()));
	}

	public function sitemap()
	{
		return new DefaultSitemapCategoriesModule('gallery', 'idcat');
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
