<?php
/*##################################################
 *                    SandboxExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : February 13, 2012
 *   copyright            : (C) 2013 Kévin MASSY
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
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
