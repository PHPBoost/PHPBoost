<?php
/*##################################################
 *                      SitemapExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : December 10, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class SitemapExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('sitemap');
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('sitemap.css');
		return $module_css_files;
	}
	
	public function commands()
	{
		return new CLICommandsList(array('generate-sitemap' => 'CLIGenerateSitemapCommand'));
	}
	
	public function home_page()
	{
		return new SitemapHomePageExtensionPoint();
	}
	
	public function scheduled_jobs()
	{
		return new SitemapScheduledJobs();
	}
	
	public function tree_links()
	{
		return new SitemapTreeLinks();
	}
	
	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/sitemap/index.php')));
	}
}
?>