<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 28
 * @since       PHPBoost 3.0 - 2009 12 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

define('PATH_TO_ROOT', '..');
require_once PATH_TO_ROOT . '/install/environment/InstallEnvironment.class.php';
InstallEnvironment::load_imports();

if (version_compare(phpversion(), ServerConfiguration::MIN_PHP_VERSION, '<') == -1)
{
	die('<h1>Impossible to install PHPBoost</h1><p>At least PHP ' . ServerConfiguration::MIN_PHP_VERSION . ' is needed but your current PHP version is ' . phpversion() . '</p>');
}

InstallEnvironment::init();

$permissions = PHPBoostFoldersPermissions::get_permissions();
if (!$permissions['/cache']->is_writable() || !$permissions['/cache/tpl']->is_writable())
{
	die(LangLoader::get_message('chmod.cache.notWritable', 'install', 'install'));
}

if ($_GET || $_POST)
{
	$arguments_list = $_POST ? $_POST : $_GET;

	if (!empty($arguments_list))
	{
		$argv = array('phpboost', 'install');
		$has_db_pwd = $has_ws_locale = false;
		foreach ($arguments_list as $id => $arg)
		{
			if (!empty($arg) && in_array($id, array('--db-host', '--db-port', '--db-user', '--db-pwd', '--db-schema', '--db-table-prefix', '--ws-server', '--ws-path', '--ws-name', '--ws-slogan', '--ws-desc', '--ws-locale', '--ws-timezone', '--u-login', '--u-pwd', '--u-email')))
			{
				switch ($id)
				{
					case '--db-pwd' :
							$has_db_pwd = true;
							break;

					case '--ws-locale' :
							$has_ws_locale = $arg ? true : false;
							$arg = $has_ws_locale ? ($arg == 'french' ? $arg : 'english') : '';
							break;
				}

				$argv[] = $id;
				$argv[] = $arg;
			}
		}

		if (!$has_ws_locale)
		{
			$lang = TextHelper::substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			switch ($lang)
			{
				case 'fr':
					$locale = 'french';
					break;
				default:
					$locale = 'english';
					break;
			}
			$argv[] = '--ws-locale';
			$argv[] = $locale;
		}

		if ($has_db_pwd)
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
	new UrlControllerMapper('InstallWelcomeController', '`^(?:/welcome)?/?$`'),
	new UrlControllerMapper('InstallLicenseController', '`^/license/?$`'),
	new UrlControllerMapper('InstallServerConfigController', '`^/server/?$`'),
	new UrlControllerMapper('InstallDBConfigController', '`^/database/?$`'),
	new UrlControllerMapper('InstallWebsiteConfigController', '`^/website/?$`'),
	new UrlControllerMapper('InstallCreateAdminController', '`^/admin/?$`'),
	new UrlControllerMapper('InstallFinishController', '`^/finish/?$`')
);
DispatchManager::dispatch($url_controller_mappers);

?>
