<?php
/*##################################################
 *                              online.php
 *                            -------------------
 *   begin                : November 23, 2006
 *   copyright          : (C) 2006 Viarre R�gis
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

require_once('../includes/begin.php'); 
require_once('../online/online_begin.php'); 
require_once('../includes/header.php'); 

$template->set_filenames(array(
	'online' => '../templates/' . $CONFIG['theme'] . '/online/online.tpl'
));
	
//Membre connect�s..
$nbr_member = $sql->query("SELECT COUNT(*) FROM ".PREFIX."sessions WHERE level <> -1 AND session_time > '" . (time() - $CONFIG['site_session_invit']) . "'", __LINE__, __FILE__);
include_once('../includes/pagination.class.php'); 
$pagination = new Pagination();
	
$template->assign_vars(array(
	'PAGINATION' => $pagination->show_pagin('online' . transid('.php?p=%d'), $nbr_member, 'p', 25, 3),
	'L_LOGIN' => $LANG['pseudo'],
	'L_LOCATION' => $LANG['location'],
	'L_LAST_UPDATE' => $LANG['last_update'],
	'L_ONLINE' => $LANG['online']
));

$result = $sql->query_while("SELECT s.user_id, s.level, s.session_time, s.session_script, s.session_script_get, 
s.session_script_title, m.login
FROM ".PREFIX."sessions s
JOIN ".PREFIX."member m ON (m.user_id = s.user_id)
WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
ORDER BY " . $CONFIG_ONLINE['display_order_online'] . "
" . $sql->sql_limit($pagination->first_msg(25, 'p'), 25), __LINE__, __FILE__); //Membres enregistr�s.
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

	$row['session_script_get'] = !empty($row['session_script_get']) ? '?' . $row['session_script_get'] : '';
	$template->assign_block_vars('users', array(
		'MEMBER' => !empty($row['login']) ? '<a href="' . HOST . '/member/member.php?id=' . $row['user_id'] . '" class="' . $status . '">' . $row['login'] . '</a>': $LANG['guest'],
		'LOCATION' => '<a href="' . HOST . DIR . $row['session_script'] . $row['session_script_get'] . '">' . stripslashes($row['session_script_title']) . '</a>',
		'LAST_UPDATE' => gmdate_format('date_format_long', $row['session_time'])
	));	
}
$sql->close($result);

$template->pparse('online'); 

require_once('../includes/footer.php'); 

?>