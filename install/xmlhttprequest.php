<?php

define('PATH_TO_ROOT', '..');
require_once PATH_TO_ROOT . '/install/install_environment.class.php';

InstallEnvironment::load_imports();

/* Deprecated */

$Errorh = new Errors(); //!\\Initialisation  de la class des erreurs//!\\
/* End deprecated */

InstallEnvironment::init();

$lang = !empty($_GET['lang']) ? trim($_GET['lang']) : 'french';
if (!@include_once('lang/' . $lang . '/install_' . $lang . '.php'))
	include_once('lang/french/install_french.php');
$chmod = retrieve(GET, 'chmod', false);
$db = retrieve(GET, 'db', false);

if ($chmod)
{
	//Mise à jour du cache.
	@clearstatcache();

	$chmod_dir = array('../cache', '../cache/backup', '../cache/syndication', '../cache/tpl', '../images/avatars', '../images/group', '../images/maths', '../images/smileys', '../kernel/db', '../lang', '../menus', '../templates', '../upload');

	//Vérifications et le cas échéants changements des autorisations en écriture.
	foreach ($chmod_dir as $dir)
	{
		$is_writable = $is_dir = true;
		if (file_exists($dir) && is_dir($dir))
		{
			if (!is_writable($dir))
			{
				$is_writable = (@chmod($dir, 0777)) ? true : false;
			}
		}
		else
		{
			$is_dir = $is_writable = ($fp = @mkdir($dir, 0777)) ? true : false;
		}
		$found = ($is_dir === true) ? '<div class="success_block">' . $LANG['existing'] . '</div>' : '<div class="failure_block">' . $LANG['unexisting'] . '</div>';
		$writable = ($is_writable === true) ? '<div class="success_block">' . $LANG['writable'] . '</div>' : '<div class="failure_block">' . $LANG['unwritable'] . '</div>';

		echo '<dl>
			<dt><label>' . str_replace('..' , '', $dir) . '</label></dt>
			<dd>
				' . $found . '
				' . $writable . '
			</dd>
		</dl>';
	}

}
elseif ($db)
{

	//Assignation des variables et erreurs
	$host = retrieve(POST, 'host', 'localhost');
	$login = retrieve(POST, 'login', '');
	$password = retrieve(POST, 'password', '');
	$database = retrieve(POST, 'database', '');
	$tables_prefix = str_replace('.', '_', retrieve(POST, 'prefix', 'phpboost_'));

	include_once('functions.php');

	if (!empty($host) && !empty($login) && !empty($database))
	{
		echo check_database_config($host, $login, $password, $database, $tables_prefix);
	}
	else
	{
		echo DB_UNKNOW_ERROR;
	}
}

InstallEnvironment::destroy();
?>