<?php
/*##################################################
 *                               xmlhttprequest.php
 *                            -------------------
 *   begin                : August 30, 2007
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

define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/begin.php');
include_once('../gallery/gallery_begin.php');
require_once('../kernel/header_no_display.php');

//Notation.
if (!empty($_GET['note']) && $User->check_level(USER_LEVEL)) //Utilisateur connecté.
{	
	$id = retrieve(POST, 'id', 0);
	$note = retrieve(POST, 'note', 0);

	//Initialisation  de la class de gestion des fichiers.
	include_once('../kernel/framework/content/note.class.php');
	$Note = new Note('gallery', $id, '', $CONFIG_GALLERY['note_max'], '', NOTE_DISPLAY_NOTE);
	
	if (!empty($note) && !empty($id))
		echo $Note->add($note); //Ajout de la note.
}
	
if ($User->check_level(MODO_LEVEL)) //Modo
{	
	if (!empty($_GET['rename_pics'])) //Renomme une image.
	{
		//Initialisation  de la class de gestion des fichiers.
		include_once('../gallery/gallery.class.php');
		$Gallery = new Gallery;
		
		$id_file = retrieve(POST, 'id_file', 0);
		$name = !empty($_POST['name']) ? strprotect(utf8_decode($_POST['name'])) : '';
		$previous_name = !empty($_POST['previous_name']) ? strprotect(utf8_decode($_POST['previous_name'])) : '';
		
		if (!empty($id_file))
			echo $Gallery->Rename_pics($id_file, $name, $previous_name);
		else 
			echo -1;
	}
	elseif (!empty($_GET['aprob_pics']))
	{
		//Initialisation  de la class de gestion des fichiers.
		include_once('../gallery/gallery.class.php');
		$Gallery = new Gallery;
		
		$id_file = retrieve(POST, 'id_file', 0);
		if (!empty($id_file))
		{
			echo $Gallery->Aprob_pics($id_file);
			//Régénération du cache des photos aléatoires.
			$Cache->Generate_module_file('gallery');
		}
		else 
			echo 0;
	}
}

?>