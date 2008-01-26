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

if( defined('PHP_BOOST') !== true)	
	exit;
	
//Accès au module.
if( !$groups->check_auth($SECURE_MODULE['forum'], ACCESS_MODULE) )
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 

load_module_lang('forum', $CONFIG['lang']); //Chargement de la langue du module.

//Configuration générale du forum
define('FLOOD_FORUM', 0x01);
define('EDIT_MARK_FORUM', 0x02);
define('TRACK_TOPIC_FORUM', 0x04);

//Configuration sur la catégorie.
define('READ_CAT_FORUM', 0x01);
define('WRITE_CAT_FORUM', 0x02);
define('EDIT_CAT_FORUM', 0x04);

//Historique.
define('H_DELETE_MSG', 'delete_msg'); //Suppression d'un message.
define('H_DELETE_TOPIC', 'delete_topic'); //Suppression d'un sujet.
define('H_LOCK_TOPIC', 'lock_topic'); //Verrouillage d'un sujet.
define('H_UNLOCK_TOPIC', 'unlock_topic'); //Déverrouillage d'un sujet.
define('H_MOVE_TOPIC', 'move_topic'); //Déplacement d'un sujet.
define('H_CUT_TOPIC', 'cut_topic'); //Scindement d'un sujet.
define('H_SET_WARNING_USER', 'set_warning_user'); //Modification pourcentage avertissement.		
define('H_BAN_USER', 'ban_user'); //Modification pourcentage avertissement.		
define('H_EDIT_MSG', 'edit_msg'); //Edition message d'un membre.
define('H_EDIT_TOPIC', 'edit_topic'); //Edition sujet d'un membre.
define('H_SOLVE_ALERT', 'solve_alert'); //Résolution d'une alerte.
define('H_WAIT_ALERT', 'wait_alert'); //Mise en attente d'une alerte.
define('H_DEL_ALERT', 'del_alert'); //Suppression d'une alerte.
define('H_READONLY_USER', 'readonly_user'); //Modification lecture seule d'un membre.

$cache->load_file('forum');

//Supprime les menus suivant configuration du site.
if( $CONFIG_FORUM['no_left_column'] == 1 ) 
	define('NO_LEFT_COLUMN', true);
if( $CONFIG_FORUM['no_right_column'] == 1 ) 
	define('NO_RIGHT_COLUMN', true);

//Chargement du css alternatif.
define('ALTERNATIVE_CSS', 'forum');

//Fonction du forum.
require('../forum/forum_functions.php');

?>