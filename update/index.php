<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : February 27, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

define('PATH_TO_ROOT', '..');
require_once PATH_TO_ROOT . '/update/environment/UpdateEnvironment.class.php';
UpdateEnvironment::load_imports();

if (version_compare(phpversion(), ServerConfiguration::MIN_PHP_VERSION, '<') == -1)
{
	die('<h1>Impossible to update PHPBoost</h1><p>At least PHP ' . ServerConfiguration::MIN_PHP_VERSION . ' is needed but your current PHP version is ' . phpversion() . '</p>');
}

UpdateEnvironment::init();

$permissions = PHPBoostFoldersPermissions::get_permissions();
if (!$permissions['/cache']->is_writable() || !$permissions['/cache/tpl']->is_writable())
{
	die('Cache folder is not writable (/cache)');
}

if ($_GET || $_POST)
{
	$arguments_list = $_POST ? $_POST : $_GET;
	
	if (!empty($arguments_list))
	{
		$argv = array('phpboost', 'update');
		$update_requested = false;
		foreach ($arguments_list as $id => $arg)
		{
			if ($id == 'update' && !empty($arg))
			{
				$update_requested = true;
				break;
			}
		}
		
		if ($update_requested)
		{
			$launcher = new CLILauncher($argv);
			$result = $launcher->launch();
			if ($result)
			{
				exit(0);
			}
			else
			{
				exit(1);
			}
		}
	}
}

$url_controller_mappers = array(
	new UrlControllerMapper('UpdateIntroductionController', '`^(?:/introduction)?/?$`'),
	new UrlControllerMapper('UpdateServerConfigController', '`^/server/?$`'),
	new UrlControllerMapper('UpdateDBConfigController', '`^/database/?$`'),
	new UrlControllerMapper('UpdateVersionExecuteController', '`^/execute/?$`'),
	new UrlControllerMapper('UpdateFinishController', '`^/finish/?$`')
);
DispatchManager::dispatch($url_controller_mappers);

?>