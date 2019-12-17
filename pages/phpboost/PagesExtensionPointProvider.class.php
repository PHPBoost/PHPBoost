<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 06 29
 * @since       PHPBoost 2.0 - 2008 02 24
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PagesExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct() //Constructeur de la classe
	{
		parent::__construct('pages');
	}

	public function comments()
	{
		return new CommentsTopics(array(new PagesCommentsTopic()));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('pages.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new PagesFeedProvider();
	}

	public function home_page()
	{
		return new PagesHomePageExtensionPoint();
	}

	public function search()
	{
		return new PagesSearchable();
	}

	public function sitemap()
	{
		return new PagesSitemapExtensionPoint();
	}

	public function tree_links()
	{
		return new PagesTreeLinks();
	}
}
?>
