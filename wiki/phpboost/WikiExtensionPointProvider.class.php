<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 01 22
 * @since       PHPBoost 2.0 - 2008 02 24
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class WikiExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('wiki');
	}

	public function comments()
	{
		return new CommentsTopics(array(new WikiCommentsTopic()));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('wiki.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new WikiFeedProvider();
	}

	public function home_page()
	{
		return new WikiHomePageExtensionPoint();
	}

	public function search()
	{
		return new WikiSearchable();
	}

	public function sitemap()
	{
		return new WikiSitemapExtensionPoint();
	}

	public function tree_links()
	{
		return new WikiTreeLinks();
	}
}
?>
