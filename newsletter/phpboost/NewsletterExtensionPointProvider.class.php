<?php
/*##################################################
 *                              NewsletterExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 Régis Viarre
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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