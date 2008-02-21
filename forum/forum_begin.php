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
if( !$Member->Check_auth($SECURE_MODULE['forum'], ACCESS_MODULE) )
	$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 

load_module_lang('forum'); //Chargement de la langue du module.

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

$Cache->Load_file('forum');

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
require('../forum/forum_functions.php');


############### Header du forum ################
$Template->Set_filenames(array(
	'forum_top' => '../templates/' . $CONFIG['theme'] . '/forum/forum_top.tpl',
	'forum_bottom' => '../templates/' . $CONFIG['theme'] . '/forum/forum_bottom.tpl'
));

$is_guest = ($Member->Get_attribute('user_id') !== -1) ? false : true;
$nbr_msg_not_read = 0;
if( !$is_guest )
{
	//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
	$max_time_msg = forum_limit_time_msg();
	
	//Vérification des autorisations.
	$unauth_cats = '';
	if( is_array($AUTH_READ_FORUM) )
	{
		foreach($AUTH_READ_FORUM as $idcat => $auth)
		{
			if( !$auth )
				$unauth_cats .= $idcat . ',';
		}
		$unauth_cats = !empty($unauth_cats) ? " AND c.id NOT IN (" . trim($unauth_cats, ',') . ")" : '';
	}

	//Si on est sur un topic, on le supprime dans la requête => si ce topic n'était pas lu il ne sera plus dans la liste car désormais lu.
	$clause_topic = '';
	if( strpos(SCRIPT, '/forum/topic.php') !== false )
	{
		$id_get = !empty($_GET['id']) ? numeric($_GET['id']) : '';
		$clause_topic = " AND t.id != '" . $id_get . "'";
	}
	
	//Requête pour compter le nombre de messages non lus.
	$nbr_msg_not_read = $Sql->Query("SELECT COUNT(*)
	FROM ".PREFIX."forum_topics t
	LEFT JOIN ".PREFIX."forum_cats c ON c.id = t.idcat
	LEFT JOIN ".PREFIX."forum_view v ON v.idtopic = t.id AND v.user_id = '" . $Member->Get_attribute('user_id') . "'
	WHERE t.last_timestamp >= '" . $max_time_msg . "' AND (v.last_view_id != t.last_msg_id OR v.last_view_id IS NULL)" . $clause_topic . $unauth_cats, __LINE__, __FILE__);
}

$Template->Assign_vars(array(	
	'C_DISPLAY_UNREAD_DETAILS' => ($Member->Get_attribute('user_id') !== -1) ? true : false,
	'U_TOPIC_TRACK' => '<a class="small_link" href="../forum/track.php' .SID . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a>',
	'U_LAST_MSG_READ' => '<a class="small_link" href="../forum/lastread.php' . SID . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a>',
	'U_MSG_NOT_READ' => '<a class="small_link" href="../forum/unread.php' .SID . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . ($Member->Get_attribute('user_id') !== -1 ? ' (' . $nbr_msg_not_read . ')' : '') . '</a>',
	'U_MSG_SET_VIEW' => '<a class="small_link" href="../forum/action' . transid('.php?read=1', '') . '" title="' . $LANG['mark_as_read'] . '" onClick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
	'L_AUTH_ERROR' => $LANG['e_auth'],
	'L_SEARCH' => $LANG['search'],
	'L_ADVANCED_SEARCH' => $LANG['advanced_search']
));

?>
