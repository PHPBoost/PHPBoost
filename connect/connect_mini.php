<?php
/*##################################################
 *                               connect_mini.php
 *                            -------------------
 *   begin                : December 10, 2007
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

if( defined('PHP_BOOST') !== true) exit;

if( $session->check_auth($session->data, 0) ) //Connecté.
{
	$template->set_filenames(array(
		'connect_mini' => '../templates/' . $CONFIG['theme'] . '/connect/connect_mini.tpl'
	));

	$l_message = ($session->data['user_pm'] > 1) ? $LANG['message_s'] : $LANG['message'];
	$user_pm = ($session->data['user_pm'] >= 1) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/new_pm.gif"  class="valign_middle" alt="" /> <a href="../member/pm' . transid('.php?pm=' . $session->data['user_id'], '-' . $session->data['user_id'] . '.php') . '" class="small_link">' . $session->data['user_pm'] . ' ' . $l_message . '</a>' : '<img src="../templates/' . $CONFIG['theme'] . '/images/pm_mini.png" alt="" class="valign_middle" /> <a href="../member/pm' . transid('.php?pm=' . $session->data['user_id'], '-' . $session->data['user_id'] . '.php') . '" class="small_link">' . $LANG['connect_private_message'] . '</a>';
	
	$template->assign_vars(array(
		'C_CONNECTED' => true,
		'C_DISCONNECTED' => false,
		'THEME' => $CONFIG['theme'],
		'U_MEMBER_ID' => transid('.php?id=' . $session->data['user_id'] . '&amp;view=1', '-' . $session->data['user_id'] . '.php?view=1'),
		'U_MEMBER_MP' => $user_pm,
		'U_ADMIN' => ($session->data['level'] == 2) ? '<li><img src="../templates/' . $CONFIG['theme'] . '/images/admin/ranks_mini.png" alt="" style="vertical-align:middle" /> <a href="../admin/admin_index.php" class="small_link">' . $LANG['admin_panel'] . '</a></li>' : '',
		'U_MODO' => ($session->data['level'] >= 1) ? '<li><img src="../templates/' . $CONFIG['theme'] . '/images/admin/modo_mini.png" alt="" style="vertical-align:middle" /> <a href="../member/moderation_panel.php" class="small_link">' . $LANG['modo_panel'] . '</a></li>' : '',
		'L_PROFIL' => $LANG['profil'],
		'L_PRIVATE_PROFIL' => $LANG['connect_private_profil'],
		'L_DISCONNECT' => $LANG['disconnect']
	));
	
	$template->pparse('connect_mini'); 
}
else
{
	$template->set_filenames(array(
		'connect_mini' => '../templates/' . $CONFIG['theme'] . '/connect/connect_mini.tpl'
	));
	
	$template->assign_vars(array(
		'C_CONNECTED' => false,
		'C_DISCONNECTED' => true,
		'U_CONNECT' => HOST . SCRIPT . ((QUERY_STRING != '') ? '?' . QUERY_STRING : ''),
		'L_CONNECT' => $LANG['connect'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_AUTOCONNECT' => $LANG['autoconnect'],
		'U_REGISTER' => $CONFIG_MEMBER['activ_register'] ? '<a href="../member/register.php">' . $LANG['register'] . '</a>' : ''
	));
	
	$template->pparse('connect_mini'); 
}

?>