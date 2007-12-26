<?php
/*##################################################
 *                              online.php
 *                            -------------------
 *   begin                : November 23, 2006
 *   copyright          : (C) 2006 Viarre Régis
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

include_once('../includes/begin.php'); 
include_once('../online/lang/' . $CONFIG['lang'] . '/online_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
define('TITLE', $LANG['online']);
include_once('../includes/header.php'); 

//Autorisation sur le module.
if( !$groups->check_auth($SECURE_MODULE['online'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}
$template->set_filenames(array(
	'online' => '../templates/' . $CONFIG['theme'] . '/online/online.tpl'
));
	
//Membre connectés..
$nbr_member = $sql->query("SELECT COUNT(*) FROM ".PREFIX."sessions WHERE level != -1 AND session_time > '" . (time() - $CONFIG['site_session_invit']) . "'", __LINE__, __FILE__);
include_once('../includes/pagination.class.php'); 
$pagination = new Pagination();
	
$template->assign_vars(array(
	'PAGINATION' => $pagination->show_pagin('online' . transid('.php?p=%d'), $nbr_member, 'p', 25, 3),
	'L_ONLINE' => $LANG['online']
));

$cache->load_file('online');

$result = $sql->query_while("SELECT s.user_id, s.level, m.login
FROM ".PREFIX."sessions as s
JOIN ".PREFIX."member as m ON (m.user_id = s.user_id)
WHERE s.level != -1 AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
ORDER BY " . $CONFIG_ONLINE['display_order_online'] . "
" . $sql->sql_limit($pagination->first_msg(25, 'p'), 25), __LINE__, __FILE__); //Membres enregistrés.
while( $row = $sql->sql_fetch_assoc($result) )
{
	switch ($row['level']) //Coloration du membre suivant son level d'autorisation. 
	{ 		
		case 0:
		$status = 'member';
		break;
		
		case 1: 
		$status = 'modo';
		break;
		
		case 2: 
		$status = 'admin';
		break;
		
		default:
		$status = 'member';
	} 
	$user_pseudo = !empty($row['login']) ? '<a href="' . HOST . '/member/member.php?id=' . $row['user_id'] . '" class="' . $status . '">' . $row['login'] . '</a><br />': '';
	
	$template->assign_block_vars('show', array(
		'MEMBER' => $user_pseudo		
	));
}
$sql->close($result);

$template->pparse('online'); 

include_once('../includes/footer.php'); 

?>