<?php
/*##################################################
 *                              online.php
 *                            -------------------
 *   begin                : November 23, 2006
 *   copyright            : (C) 2006 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *   
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
require_once('../online/online_begin.php'); 
require_once('../kernel/header.php'); 

$Template->set_filenames(array(
	'online'=> 'online/online.tpl'
));
	
//Membre connectés..
$nbr_member = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_SESSIONS . " WHERE user_id <> -1 AND expiry > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "'", __LINE__, __FILE__);
 
$Pagination = new DeprecatedPagination();
	
$Template->put_all(array(
	'PAGINATION' => $Pagination->display('online' . url('.php?p=%d'), $nbr_member, 'p', 25, 3),
	'L_LOGIN' => $LANG['pseudo'],
	'L_LOCATION' => $LANG['location'],
	'L_LAST_UPDATE' => $LANG['last_update'],
	'L_ONLINE' => $LANG['online']
));

$result = $Sql->query_while("SELECT s.user_id, s.expiry, s.location_script, s.location_title, m.display_name
FROM " . DB_TABLE_SESSIONS . " s
JOIN " . DB_TABLE_MEMBER . " m ON (m.user_id = s.user_id)
WHERE s.expiry > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "'
ORDER BY " . OnlineConfig::load()->get_display_order() . "
" . $Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__); //Membres enregistrés.
while ($row = $Sql->fetch_assoc($result))
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

	$Template->assign_block_vars('users', array(
		'USER' => !empty($row['display_name']) ? '<a href="' . UserUrlBuilder::profile($row['user_id'])->absolute() . '" class="' . $status . '">' . $row['display_name'] . '</a>': $LANG['guest'],
		'LOCATION' => '<a href="' . $row['location_script'] . '">' . stripslashes($row['location_title']) . '</a>',
		'LAST_UPDATE' => gmdate_format('date_format_long', $row['session_time'])
	));	
}
$Sql->query_close($result);

$Template->pparse('online'); 

require_once('../kernel/footer.php'); 

?>