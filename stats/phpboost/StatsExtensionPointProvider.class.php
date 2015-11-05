<?php
/*##################################################
 *                        StatsExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : October 08, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class StatsExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('stats');
	}
	
	public function home_page()
	{
		return new StatsHomePageExtensionPoint();
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('stats.css');
		return $module_css_files;
	}
	
	public function menus()
	{
		return new StatsMenusExtensionPoint();
	}
	
	public function scheduled_jobs()
	{
		return new StatsScheduledJobs();
	}
}
?>