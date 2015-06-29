<?php
/*##################################################
 *                     ForumExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
 *   copyright            : (C) 2007 Régis Viarre, Loic Rouchon
 *   email                : crowkait@phpboost.com, loic.rouchon@phpboost.com
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

define('FORUM_MAX_SEARCH_RESULTS', 50);

class ForumExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct() //Constructeur de la classe ForumInterface
	{
		parent::__construct('forum');
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('forum.css');
		return $module_css_files;
	}

	public function feeds()
	{
		return new ForumFeedProvider();
	}
	
	public function home_page()
	{
		return new ForumHomePageExtensionPoint();
	}

	public function scheduled_jobs()
	{
		return new ForumScheduledJobs();
	}

	public function search()
	{
		return new ForumSearchable();
	}
	
	public function tree_links()
	{
		return new ForumTreeLinks();
	}
	
	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/forum/index.php')));
	}

	public function user()
	{
		return new ForumUserExtensionPoint();
	}
}
?>