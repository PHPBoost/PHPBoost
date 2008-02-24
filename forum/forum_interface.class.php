<?php
/*##################################################
 *                              forum_interface.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/
 
// Inclusion du fichier contenant la classe ModuleInterface
require_once('../includes/module.class.php');
 
// Classe ForumInterface qui hérite de la classe ModuleInterface
class ForumInterface extends ModuleInterface
{
	## Public Methods ##
    function ForumInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('forum');
    }
	
	function GetMembermsgLink($memberId) //Récupère le lien vers la listes des messages du membre.
    {
        // Mon traitement
        return '../forum/membermsg.php?id=' . $memberId;
    }
}
 
?>