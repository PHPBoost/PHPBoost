<?php
/*##################################################
 *                             connect_mini.php
 *                            -------------------
 *   begin                : December 10, 2007
 *   copyright            : (C) 2007 Viarre Régis
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
	
	//Vaut 0 si l'utilisateur n'a aucune contribution. Est > 0 si on connait le nombre de contributions
	//Vaut -1 si l'utilisateur a au moins une contribution (mais on ne sait pas combien à cause des recoupements entre les groupes)
	$contribution_number = 0;
	
	//Panneau de contributions, y-a-t'il des contributions que le membre peut lire ?
	if( $Member->Check_level(ADMIN_LEVEL) )
		$contribution_number = $CONTRIBUTION_PANEL_UNREAD['r2'];
	elseif( $Member->Check_level(MODERATOR_LEVEL) )
		$contribution_number = $CONTRIBUTION_PANEL_UNREAD['r1'];
	//On vérifie les groupes et les levels ou tout simplement si il y en a pour les membres
	else
	{
		//Si tous les membres ont une contribution non lue
		if( $CONTRIBUTION_PANEL_UNREAD['r0'] > 0 )
			$contribution_number = -1;
		
		//On regarde si ce membre en particulier en a une
		if( $contribution_number == 0 )
			if( !empty($CONTRIBUTION_PANEL_UNREAD['m' . $id_group]) && $CONTRIBUTION_PANEL_UNREAD['m' . $id_group] == 1 )
				$contribution_number = -1;
		
		//On regarde dans ses groupes
		if( $contribution_number == 0 )
		{
			foreach($Member->get_groups() as $id_group)
			{
				if( !empty($CONTRIBUTION_PANEL_UNREAD['g' . $id_group]) && $CONTRIBUTION_PANEL_UNREAD['g' . $id_group] == 1 )
				{
					$contribution_number = -1;
					break;
				}
			}
		}
	}

	$Template->Assign_vars(array(
		'C_ADMIN_AUTH' => $Member->Check_level(ADMIN_LEVEL),
		'C_MODERATOR_AUTH' => $Member->Check_level(MODERATOR_LEVEL),
		'C_UNREAD_CONTRIBUTION' => $contribution_number != 0,
		'C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION' => $contribution_number > 0,
		'C_UNREAD_ALERT' => (bool)$UNREAD_ALERTS,
		'NUM_UNREAD_CONTRIBUTIONS' => $contribution_number,
		'NUMBER_UNREAD_ALERTS' => $UNREAD_ALERTS,
		'IMG_PM' => $Member->Get_attribute('user_pm') > 0 ? 'new_pm.gif' : 'pm_mini.png',
		'U_MEMBER_PM' => PATH_TO_ROOT . '/member/pm' . transid('.php?pm=' . $Member->Get_attribute('user_id'), '-' . $Member->Get_attribute('user_id') . '.php'),
		'U_MEMBER_ID' => transid('.php?id=' . $Member->Get_attribute('user_id') . '&amp;view=1', '-' . $Member->Get_attribute('user_id') . '.php?view=1'),
		'U_DISCONNECT' => HOST . DIR . '/member/member.php?disconnect=true',
		'L_NBR_PM' => ($Member->Get_attribute('user_pm') > 0 ? ($Member->Get_attribute('user_pm') . ' ' . (($Member->Get_attribute('user_pm') > 1) ? $LANG['message_s'] : $LANG['message'])) : $LANG['connect_private_message']),
		'L_PROFIL' => $LANG['profil'],
		'L_ADMIN_PANEL' => $LANG['admin_panel'],
		'L_MODO_PANEL' => $LANG['modo_panel'],
		'L_PRIVATE_PROFIL' => $LANG['connect_private_profil'],
		'L_DISCONNECT' => $LANG['disconnect'],
		'L_CONTRIBUTION_PANEL' => $LANG['contribution_panel']
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