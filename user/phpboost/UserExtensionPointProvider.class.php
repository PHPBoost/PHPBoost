<?php
/*##################################################
 *                              UserExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : October 09, 2011
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

class UserExtensionPointProvider extends ExtensionPointProvider
{
   public function __construct()
	{
		parent::__construct('user');
	}

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_always_displayed_file('user.css');
		return $module_css_files;
	}

	public function commands()
	{
		return new CLICommandsList(array('user' => 'CLIUserManagementCommand'));
	}
	
	public function user()
	{
		return new UserUserExtensionPoint();
	}
	
	public function tree_links()
	{
		return new UserTreeLinks();
	}
	
	public function url_mappings()
	{
		return new UrlMappings(array(
			new DispatcherUrlMapping('/user/index.php', '([\w/-_]*)$'),
			new DispatcherUrlMapping('/user/index.php', 'login/?$', 'root', 'login/'),
			new DispatcherUrlMapping('/user/index.php', 'aboutcookie/?$', 'root', 'aboutcookie/'),
			new DispatcherUrlMapping('/user/index.php', 'registration/?$', 'root', 'registration/'),
			new DispatcherUrlMapping('/user/index.php', 'registration/confirm/?([a-z0-9]+)?/?$', 'root', 'registration/confirm/$1'),
			new DispatcherUrlMapping('/user/index.php', 'password/lost/?$', 'root', 'password/lost/'),
			new DispatcherUrlMapping('/user/index.php', 'password/change/?([a-z0-9]+)?/?$', 'root', 'password/change/$1'),
			new DispatcherUrlMapping('/user/index.php', 'error/403/?$', 'root', 'error/403/'),
			new DispatcherUrlMapping('/user/index.php', 'error/404/?$', 'root', 'error/404/')
		));
	}
	
	public function comments()
	{
		return new CommentsTopics(array(
			new UserEventsCommentsTopic()
		));
	}
}
?>
