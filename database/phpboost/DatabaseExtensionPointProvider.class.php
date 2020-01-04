<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class DatabaseExtensionPointProvider extends ExtensionPointProvider
{
	function __construct()
	{
		parent::__construct('database');
	}

	public function commands()
	{
		return new CLICommandsList(array('dump' => 'CLIDumpCommand', 'restoredb' => 'CLIRestoreDBCommand'));
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('database.css');
		return $module_css_files;
	}

	public function tree_links()
	{
		return new DatabaseTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/database/index.php')));
	}
}
?>
