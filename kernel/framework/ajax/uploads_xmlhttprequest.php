<?php
/*##################################################
 *                               uploads_xmlhttprequest.php
 *                            -------------------
 *   begin                : January, 25 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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
* @package ajax
*
*/

define('PATH_TO_ROOT', '../../..');
define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.

include_once(PATH_TO_ROOT . '/kernel/begin.php');
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

//Initialisation  de la class de gestion des fichiers.


if (!empty($_GET['new_folder'])) //Ajout d'un dossier dans la gestion des fichiers.
{
    $id_parent = !empty($_POST['id_parent']) ? numeric($_POST['id_parent']) : '0';
    $user_id = !empty($_POST['user_id']) ? numeric($_POST['user_id']) : $User->get_attribute('user_id');
    $name = !empty($_POST['name']) ? strprotect(utf8_decode($_POST['name'])) : '';

    if (!empty($user_id) && $User->get_attribute('user_id') != $user_id)
    {
        if ($User->check_level(ADMIN_LEVEL))
        {
            echo Uploads::Add_folder($id_parent, $user_id, $name);
        }
        else
        {
            echo Uploads::Add_folder($id_parent, $User->get_attribute('user_id'), $name);
        }
    }
    else
    {
        echo Uploads::Add_folder($id_parent, $User->get_attribute('user_id'), $name);
    }
}
elseif (!empty($_GET['rename_folder'])) //Renomme un dossier dans la gestion des fichiers.
{
    $id_folder = !empty($_POST['id_folder']) ? numeric($_POST['id_folder']) : '0';
    $name = !empty($_POST['name']) ? strprotect(utf8_decode($_POST['name'])) : '';
    $user_id = !empty($_POST['user_id']) ? numeric($_POST['user_id']) : $User->get_attribute('user_id');
    $previous_name = !empty($_POST['previous_name']) ? strprotect(utf8_decode($_POST['previous_name'])) : '';

    if (!empty($id_folder) && !empty($name))
    {
        if ($User->get_attribute('user_id') != $user_id)
        {
            if ($User->check_level(ADMIN_LEVEL))
            {
                echo Uploads::Rename_folder($id_folder, $name, $previous_name, $user_id, Uploads::ADMIN_NO_CHECK);
            }
            else
            {
                echo Uploads::Rename_folder($id_folder, $name, $previous_name, $User->get_attribute('user_id'), Uploads::ADMIN_NO_CHECK);
            }
        }
        else
        {
            echo Uploads::Rename_folder($id_folder, $name, $previous_name, $User->get_attribute('user_id'));
        }
    }
    else
    echo 0;
}
elseif (!empty($_GET['rename_file'])) //Renomme un fichier d'un dossier dans la gestion des fichiers.
{
    $id_file = !empty($_POST['id_file']) ? numeric($_POST['id_file']) : '0';
    $user_id = !empty($_POST['user_id']) ? numeric($_POST['user_id']) : $User->get_attribute('user_id');
    $name = !empty($_POST['name']) ? strprotect(utf8_decode($_POST['name'])) : '';
    $previous_name = !empty($_POST['previous_name']) ? strprotect(utf8_decode($_POST['previous_name'])) : '';

    if (!empty($id_file) && !empty($name))
    {
        if ($User->get_attribute('user_id') != $user_id)
        {
            if ($User->check_level(ADMIN_LEVEL))
            {
                echo Uploads::Rename_file($id_file, $name, $previous_name, $user_id, Uploads::ADMIN_NO_CHECK);
            }
            else
            {
                echo Uploads::Rename_file($id_file, $name, $previous_name, $User->get_attribute('user_id'), Uploads::ADMIN_NO_CHECK);
            }
        }
        else
        {
            echo Uploads::Rename_file($id_file, $name, $previous_name, $User->get_attribute('user_id'));
        }
    }
    else
    {
        echo 0;
    }
}

?>