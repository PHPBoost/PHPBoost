<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 10 24
 * @since   	PHPBoost 3.0 - 2011 03 11
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class NewsletterExtensionPointProvider extends ExtensionPointProvider
{
	function __construct()
	{
		parent::__construct('newsletter');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('newsletter_mini.css');
		return $module_css_files;
	}

	public function extended_field()
	{
		return new ExtendedFields(array(new RegisterNewsletterExtendedField()));
	}

	public function home_page()
	{
		return new NewsletterHomePageExtensionPoint();
	}

	public function menus()
	{
		return new ModuleMenus(array(new NewsletterModuleMiniMenu()));
	}

	public function sitemap()
	{
		return new NewsletterSitemapExtensionPoint();
	}

	public function tree_links()
	{
		return new NewsletterTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/newsletter/index.php')));
	}
}
?>
