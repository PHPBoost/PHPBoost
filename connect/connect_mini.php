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

if (defined('PHPBOOST') !== true) exit;

function connect_mini($position, $block)
{
    global $User, $LANG, $CONFIG_USER, $CONTRIBUTION_PANEL_UNREAD, $ADMINISTRATOR_ALERTS, $Session;
    
    $tpl = new Template('connect/connect_mini.tpl');
    import('core/menu_service');
    MenuService::assign_positions_conditions($tpl, $block);
    if ($User->check_level(MEMBER_LEVEL)) //Connecté.
    {
    	//Vaut 0 si l'utilisateur n'a aucune contribution. Est > 0 si on connait le nombre de contributions
    	//Vaut -1 si l'utilisateur a au moins une contribution (mais on ne sait pas combien à cause des recoupements entre les groupes)
    	$contribution_number = 0;
    	
    	//Panneau de contributions, y-a-t'il des contributions que le membre peut lire ?
    	if ($User->check_level(ADMIN_LEVEL))
    		$contribution_number = $CONTRIBUTION_PANEL_UNREAD['r2'];
    	elseif ($User->check_level(MODERATOR_LEVEL))
    		$contribution_number = $CONTRIBUTION_PANEL_UNREAD['r1'];
    	//On vérifie les groupes et les levels ou tout simplement si il y en a pour les membres
    	else
    	{
    		//Si tous les membres ont une contribution non lue
    		if ($CONTRIBUTION_PANEL_UNREAD['r0'] > 0)
    			$contribution_number = -1;
    		
    		//On regarde si ce membre en particulier en a une
    		if ($contribution_number == 0)
    			if (!empty($CONTRIBUTION_PANEL_UNREAD['m' . $User->get_attribute('user_id')]) && $CONTRIBUTION_PANEL_UNREAD['m' . $User->get_attribute('user_id')] == 1)
    				$contribution_number = -1;
    		
    		//On regarde dans ses groupes
    		if ($contribution_number == 0)
    		{
    			foreach ($User->get_groups() as $id_group)
    			{
    				if (!empty($CONTRIBUTION_PANEL_UNREAD['g' . $id_group]) && $CONTRIBUTION_PANEL_UNREAD['g' . $id_group] == 1)
    				{
    					$contribution_number = -1;
    					break;
    				}
    			}
    		}
    	}
    
    	import('events/AdministratorAlertService');
    	
    	$tpl->assign_vars(array(
    		'C_ADMIN_AUTH' => $User->check_level(ADMIN_LEVEL),
    		'C_MODERATOR_AUTH' => $User->check_level(MODERATOR_LEVEL),
    		'C_UNREAD_CONTRIBUTION' => $contribution_number != 0,
    		'C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION' => $contribution_number > 0,
    		'C_UNREAD_ALERT' => (bool)AdministratorAlertService::get_number_unread_alerts(),
    		'NUM_UNREAD_CONTRIBUTIONS' => $contribution_number,
    		'NUMBER_UNREAD_ALERTS' => AdministratorAlertService::get_number_unread_alerts(),
    		'IMG_PM' => $User->get_attribute('user_pm') > 0 ? 'new_pm.gif' : 'pm_mini.png',
    		'U_USER_PM' => TPL_PATH_TO_ROOT . '/member/pm' . url('.php?pm=' . $User->get_attribute('user_id'), '-' . $User->get_attribute('user_id') . '.php'),
    		'U_USER_ID' => url('.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', '-' . $User->get_attribute('user_id') . '.php?view=1'),
    		'U_DISCONNECT' => HOST . DIR . '/member/member.php?disconnect=true&amp;token=' . $Session->get_token(),
    		'L_NBR_PM' => ($User->get_attribute('user_pm') > 0 ? ($User->get_attribute('user_pm') . ' ' . (($User->get_attribute('user_pm') > 1) ? $LANG['message_s'] : $LANG['message'])) : $LANG['private_messaging']),
    		'L_PROFIL' => $LANG['profile'],
    		'L_ADMIN_PANEL' => $LANG['admin_panel'],
    		'L_MODO_PANEL' => $LANG['modo_panel'],
    		'L_PRIVATE_PROFIL' => $LANG['my_private_profile'],
    		'L_DISCONNECT' => $LANG['disconnect'],
    		'L_CONTRIBUTION_PANEL' => $LANG['contribution_panel']
    	));
    }
    else
    {
    	$tpl->assign_vars(array(
    		'C_USER_REGISTER' => (bool)$CONFIG_USER['activ_register'],
    		'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
			'L_REQUIRE_PASSWORD' => $LANG['require_password'],
			'L_CONNECT' => $LANG['connect'],
    		'L_PSEUDO' => $LANG['pseudo'],
    		'L_PASSWORD' => $LANG['password'],
    		'L_AUTOCONNECT' => $LANG['autoconnect'],
    		'L_FORGOT_PASS' => $LANG['forget_pass'],
    		'L_REGISTER' => $LANG['register'],
    		'U_CONNECT' => (QUERY_STRING != '') ? '?' . str_replace('&', '&amp;', QUERY_STRING) . '&amp;' : '',
    		'U_REGISTER' => TPL_PATH_TO_ROOT . '/member/register.php' . SID
    	));
    }
    
    return $tpl->parse(Template::TEMPLATE_PARSER_STRING);
}
?>