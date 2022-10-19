<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 12 22
 * @since       PHPBoost 3.0 - 2011 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

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
		$module_css_files->adding_always_displayed_file('upload.css');
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
