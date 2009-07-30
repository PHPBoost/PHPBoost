<?php
/*##################################################
 *                               xmlhttprequest.php
 *                            -------------------
 *   begin                : April 09, 2008
 *   copyright          : (C) 2008 Viarre Régis
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
include_once('../articles/articles_begin.php');
require_once('../kernel/header_no_display.php');

//Notation.
if (!empty($_GET['note']) && $User->check_level(MEMBER_LEVEL)) //Utilisateur connecté.
{	
	$id = retrieve(POST, 'id', 0);
	$note = retrieve(POST, 'note', 0);

	//Initialisation  de la class de gestion des fichiers.
	import('content/note');
	$Note = new Note('articles', $id, '', $CONFIG_ARTICLES['note_max'], '', NOTE_DISPLAY_NOTE);
	
	if (!empty($note) && !empty($id))
		echo $Note->add($note); //Ajout de la note.
}
else
	echo -2;

?>