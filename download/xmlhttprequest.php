<?php
/*##################################################
 *                               xmlhttprequest.php
 *                            -------------------
 *   begin                : December 20, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

require_once('../includes/begin.php');
define('TITLE', 'Ajax download');
require_once('../includes/header_no_display.php');
include_once('../download/download_begin.php');

//Notation des images.
if( !empty($_GET['note_pics']) && $session->check_auth($session->data, 0) ) //Utilisateur connecté.
{	
	$id_file = !empty($_POST['id_file']) ? numeric($_POST['id_file']) : '0';
	$note = !empty($_POST['note']) ? numeric($_POST['note']) : 0;
	$idcat = !empty($_POST['idcat']) ? numeric($_POST['idcat']) : 0;
	if( empty($idcat) )
		$CAT_DOWNLOAD[0]['auth'] = $CONFIG_DOWNLOAD['auth_root'];
	
	if( !isset($CAT_DOWNLOAD[$idcat]) )
		echo 0;
	
	//Autorisation en lecture, notation activée, et note comprise dans l'intervalle autorisé.
	$info_download = $sql->query_array('download', 'id', 'users_note', 'nbrnote', 'note', 'auth', "WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
	if( !empty($info_download['id']) && $note >= 0 && $note <= $CONFIG_DOWNLOAD['note_max'] && $groups->check_auth($CAT_DOWNLOAD[$idcat]['auth'], READ_CAT_DOWNLOAD) && $groups->check_auth($info_download['auth'], READ_FILE_DOWNLOAD) )
	{
		if( !in_array($session->data['user_id'], explode('/', $info_download['users_note'])) )
		{			
			$note = (($info_download['note'] * $info_download['nbrnote']) + $note)/($info_download['nbrnote'] + 1);			
			$users_note = !empty($info_download['users_note']) ? $info_download['users_note'] . '/' . $session->data['user_id'] : $session->data['user_id']; //On ajoute l'id de l'utilisateur.
			
			$sql->query_inject("UPDATE ".PREFIX."download SET note = '" . $note . "', nbrnote = nbrnote + 1, users_note = '" . $users_note . "' WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
			echo 'get_note = ' . $note . ';get_nbrnote = ' . ($info_download['nbrnote']+1) . ';';
		}
		else	
			echo -1;
	}
	else 
		echo 0;
}

?>