<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 08 02
 * @since       PHPBoost 3.0 - 2012 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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
