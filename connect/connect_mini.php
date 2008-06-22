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

if( defined('PHPBOOST') !== true) exit;

if( $Member->Check_level(MEMBER_LEVEL) ) //Connecté.
{
	$Template->Set_filenames(array(
		'connect_mini'=> 'connect/connect_mini.tpl'
	));

	$Template->Assign_vars(array(
		'C_ADMIN_AUTH' => $Member->Check_level(ADMIN_LEVEL),
		'C_MODO_AUTH' => $Member->Check_level(MODO_LEVEL),
		'IMG_PM' => $Member->Get_attribute('user_pm') > 0 ? 'new_pm.gif' : 'pm_mini.png',
		'U_MEMBER_PM' => PATH_TO_ROOT . '/member/pm' . transid('.php?pm=' . $Member->Get_attribute('user_id'), '-' . $Member->Get_attribute('user_id') . '.php'),
		'U_MEMBER_ID' => transid('.php?id=' . $Member->Get_attribute('user_id') . '&amp;view=1', '-' . $Member->Get_attribute('user_id') . '.php?view=1'),
		'U_DISCONNECT' => HOST . DIR . '/member/member.php?disconnect=true',
		'L_NBR_PM' => ($Member->Get_attribute('user_pm') > 0 ? ($Member->Get_attribute('user_pm') . ' ' . (($Member->Get_attribute('user_pm') > 1) ? $LANG['message_s'] : $LANG['message'])) : $LANG['connect_private_message']),
		'L_PROFIL' => $LANG['profil'],
		'L_ADMIN_PANEL' => $LANG['admin_panel'],
		'L_MODO_PANEL' => $LANG['modo_panel'],
		'L_PRIVATE_PROFIL' => $LANG['connect_private_profil'],
		'L_DISCONNECT' => $LANG['disconnect']
	));
}
else
{
	$Template->Set_filenames(array(
		'connect_mini'=> 'connect/connect_mini.tpl'
	));
	
	$Template->Assign_vars(array(
		'C_MEMBER_REGISTER' => $CONFIG_MEMBER['activ_register'] ? true : false,
		'L_CONNECT' => $LANG['connect'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_PASSWORD' => $LANG['password'],
		'L_AUTOCONNECT' => $LANG['autoconnect'],
		'L_FORGOT_PASS' => $LANG['forget_pass'],
		'L_REGISTER' => $LANG['register'],
		'U_CONNECT' => (QUERY_STRING != '') ? '?' . str_replace('&', '&amp;', QUERY_STRING) : '',
		'U_REGISTER' => PATH_TO_ROOT . '/member/register.php' . SID
	));
}

?>