<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 27
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class ContactExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('contact');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('contact.css');
		return $module_css_files;
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), ContactController::get_view());
	}

	public function tree_links()
	{
		return new ContactTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/contact/index.php')));
	}
}
?>
