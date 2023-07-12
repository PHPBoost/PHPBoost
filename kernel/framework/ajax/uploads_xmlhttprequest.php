<?php
/**
 * @package     Ajax
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 07 12
 * @since       PHPBoost 1.6 - 2007 01 25
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '../../..');

include_once(PATH_TO_ROOT . '/kernel/begin.php');
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

//Initialisation  de la class de gestion des fichiers.

$user = AppContext::get_current_user();
$request = AppContext::get_request();

$new_folder = $request->get_getint('new_folder', 0);
$rename_folder = $request->get_getint('rename_folder', 0);
$rename_file = $request->get_getint('rename_file', 0);

$user_id = $request->get_postint('user_id', $user->get_id());
$name = TextHelper::strprotect(mb_convert_encoding($request->get_postvalue('name', ''), 'ISO-8859-1', 'UTF-8'));
$previous_name = TextHelper::strprotect(mb_convert_encoding($request->get_postvalue('previous_name', ''), 'ISO-8859-1', 'UTF-8'));

if (!empty($new_folder)) //Ajout d'un dossier dans la gestion des fichiers.
{
	$id_parent = $request->get_postint('id_parent', 0);

	if (!empty($user_id) && $user->get_id() != $user_id)
	{
		if ($user->check_level(User::ADMINISTRATOR_LEVEL))
		{
			echo Uploads::Add_folder($id_parent, $user_id, $name);
		}
		else
		{
			echo Uploads::Add_folder($id_parent, $user->get_id(), $name);
		}
	}
	else
	{
		echo Uploads::Add_folder($id_parent, $user->get_id(), $name);
	}
}
elseif (!empty($rename_folder)) //Renomme un dossier dans la gestion des fichiers.
{
	$id_folder = $request->get_postint('id_folder', 0);

	if (!empty($id_folder) && !empty($name))
	{
		if ($user->get_id() != $user_id)
		{
			if ($user->check_level(User::ADMINISTRATOR_LEVEL))
			{
				echo Uploads::Rename_folder($id_folder, $name, $previous_name, $user_id, Uploads::ADMIN_NO_CHECK);
			}
			else
			{
				echo Uploads::Rename_folder($id_folder, $name, $previous_name, $user->get_id(), Uploads::ADMIN_NO_CHECK);
			}
		}
		else
		{
			echo Uploads::Rename_folder($id_folder, $name, $previous_name, $user->get_id());
		}
	}
	else
	echo 0;
}
elseif (!empty($rename_file)) //Renomme un fichier d'un dossier dans la gestion des fichiers.
{
	$id_file = $request->get_postint('id_file', 0);

	if (!empty($id_file) && !empty($name))
	{
		if ($user->get_id() != $user_id)
		{
			if ($user->check_level(User::ADMINISTRATOR_LEVEL))
			{
				echo Uploads::Rename_file($id_file, $name, $previous_name, $user_id, Uploads::ADMIN_NO_CHECK);
			}
			else
			{
				echo Uploads::Rename_file($id_file, $name, $previous_name, $user->get_id(), Uploads::ADMIN_NO_CHECK);
			}
		}
		else
		{
			echo Uploads::Rename_file($id_file, $name, $previous_name, $user->get_id());
		}
	}
	else
	{
		echo 0;
	}
}
else
	echo -1;
?>
