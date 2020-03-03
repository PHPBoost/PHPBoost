<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 03
 * @since       PHPBoost 3.0 - 2013 02 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class SandboxExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('sandbox');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('sandbox.css');
		$module_css_files->adding_always_displayed_file('sandbox_mini.css');
		$module_css_files->adding_always_displayed_file('/templates/default/theme/icoboost/icoboost.css');

		if (ModulesManager::is_module_installed('wiki')  && ModulesManager::is_module_activated('wiki'))
			$module_css_files->adding_running_module_displayed_file('wiki.css', 'wiki');

		return $module_css_files;
	}

	public function menus()
	{
		return new ModuleMenus(array(new SandboxModuleMiniMenu()));
	}

	public function comments()
	{
		return new CommentsTopics(array(new SandboxCommentsTopic()));
	}

	public function tree_links()
	{
		return new SandboxTreeLinks();
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/sandbox/index.php')));
	}

}
?>
