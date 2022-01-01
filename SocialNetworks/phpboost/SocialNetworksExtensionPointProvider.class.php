<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 04 16
 * @since       PHPBoost 5.1 - 2018 01 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class SocialNetworksExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('SocialNetworks');
	}

	public function content_sharing_actions_menu_links()
	{
		$social_networks_list = new SocialNetworksList();
		return $social_networks_list->get_sharing_links_list();
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('SocialNetworks.css');
		return $module_css_files;
	}

	public function external_authentications()
	{
		$social_networks_list = new SocialNetworksList();
		return new ExternalAuthenticationsExtensionPoint($social_networks_list->get_external_authentications_list());
	}

	public function menus()
	{
		return new ModuleMenus(array(new SocialNetworksModuleMiniMenu()));
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/SocialNetworks/index.php')));
	}
}
?>
