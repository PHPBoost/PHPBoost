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

require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');
include_once('../download/download_begin.php');

//Notation.
if( !empty($_GET['note']) && $Member->Check_level(MEMBER_LEVEL) ) //Utilisateur connecté.
{	
	$id = !empty($_POST['id']) ? numeric($_POST['id']) : 0;
	$note = !empty($_POST['note']) ? numeric($_POST['note']) : 0;

	//Initialisation  de la class de gestion des fichiers.
	include_once('../kernel/framework/note.class.php');
	$Note = new Note('download', $id, '', $CONFIG_DOWNLOAD['note_max'], '', NOTE_DISPLAY_NOTE);
	
	if( !empty($note) && !empty($id) )
		echo $Note->Add_note($note); //Ajout de la note.
}

?>