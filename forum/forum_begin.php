<?php
/*##################################################
 *                             forum_begin.php
 *                            -------------------
 *   begin                : October 18, 2007
 *   copyright          : (C) 2007 Viarre régis
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

if( defined('PHPBOOST') !== true)	
	exit;
	
//Accès au module.
if( !$Member->Check_auth($SECURE_MODULE['forum'], ACCESS_MODULE) )
	$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 

load_module_lang('forum'); //Chargement de la langue du module.

require_once('../forum/forum_defines.php');

$Cache->Load_file('forum');

//Vérification des autorisations sur toutes les catégories.
$AUTH_READ_FORUM = array();
if( is_array($CAT_FORUM) )
{
	foreach($CAT_FORUM as $idcat => $key)
	{
		if( $Member->Check_auth($CAT_FORUM[$idcat]['auth'], READ_CAT_FORUM) )
			$AUTH_READ_FORUM[$idcat] = true;
		else
			$AUTH_READ_FORUM[$idcat] = false;
	}
}

//Supprime les menus suivant configuration du site.
if( $CONFIG_FORUM['no_left_column'] == 1 ) 
	define('NO_LEFT_COLUMN', true);
if( $CONFIG_FORUM['no_right_column'] == 1 ) 
	define('NO_RIGHT_COLUMN', true);

//Chargement du css alternatif.
define('ALTERNATIVE_CSS', 'forum');

//Fonction du forum.
require_once('../forum/forum_functions.php');

?>
