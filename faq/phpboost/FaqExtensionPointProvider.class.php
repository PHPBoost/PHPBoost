<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 09
 * @since       PHPBoost 4.0 - 2014 09 02
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FaqExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('faq');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('faq_mini.css');
		$module_css_files->adding_running_module_displayed_file('faq.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new FaqFeedProvider();
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), FaqCategoryController::get_view());
	}

	public function menus()
	{
		return new ModuleMenus(array(new FaqModuleMiniMenu()));
	}

	public function search()
	{
		return new FaqSearchable();
	}

	public function sitemap()
	{
		return new DefaultSitemapCategoriesModule('faq');
	}

	public function tree_links()
	{
		return new FaqTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/faq/index.php')));
	}
}
?>
