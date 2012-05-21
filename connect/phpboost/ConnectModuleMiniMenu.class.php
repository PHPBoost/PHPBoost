<?php
/*##################################################
 *                          ConnectModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : October 08, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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

class ConnectModuleMiniMenu extends ModuleMiniMenu
{    
    public function get_default_block()
    {
    	return self::BLOCK_POSITION__SUB_HEADER;
    }

	public function display($tpl = false)
    {
    	global $LANG;

	    $tpl = new FileTemplate('connect/connect_mini.tpl');
	    $user = AppContext::get_current_user();
	    MenuService::assign_positions_conditions($tpl, $this->get_block());
	    if ($user->check_level(User::MEMBER_LEVEL)) //Connecté.
	    {
	    	$unread_contributions = UnreadContributionsCache::load();
	
	    	//Vaut 0 si l'utilisateur n'a aucune contribution. Est > 0 si on connait le nombre de contributions
	    	//Vaut -1 si l'utilisateur a au moins une contribution (mais on ne sait pas combien à cause des recoupements entre les groupes)
	    	$contribution_number = 0;
	
	    	if ($user->check_level(User::ADMIN_LEVEL))
	    	{
	    		$contribution_number = $unread_contributions->get_admin_unread_contributions_number();
	    	}
	    	elseif ($user->check_level(User::MODERATOR_LEVEL))
	    	{
	    		if ($unread_contributions->have_moderators_unread_contributions())
	    		{
	    			$contribution_number = -1;
	    		}
	    	}
	    	else
	    	{
	    		if ($unread_contributions->have_members_unread_contributions())
	    		{
	    			$contribution_number = -1;
	    		}
	    		else if ($unread_contributions->has_user_unread_contributions($user->get_id()))
	    		{
	    			$contribution_number = -1;
	    		}
	    		else
	    		{
	    			foreach ($user->get_groups() as $group_id)
	    			{
	    				if ($unread_contributions->has_group_unread_contributions($group_id))
	    				{
	    					$contribution_number = -1;
	    					break;
	    				}
	    			}
	    		}
	    	}
	    	
			$user_accounts_config = UserAccountsConfig::load();
	    	$user_avatar = MemberExtendedFieldsService::return_field_member('user_avatar', $user->get_id());
	    	if (empty($user_avatar))
			{
				$user_avatar = '/templates/'. get_utheme() .'/images/'. $user_accounts_config->get_default_avatar_name();
			}

	    	$tpl->put_all(array(
	    		'C_ADMIN_AUTH' => $user->check_level(User::ADMIN_LEVEL),
	    		'C_MODERATOR_AUTH' => $user->check_level(User::MODERATOR_LEVEL),
	    		'C_UNREAD_CONTRIBUTION' => $contribution_number != 0,
	    		'C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION' => $contribution_number > 0,
	    		'C_UNREAD_ALERT' => (bool)AdministratorAlertService::get_number_unread_alerts(),
	    		'NUM_UNREAD_CONTRIBUTIONS' => $contribution_number,
	    		'NUMBER_UNREAD_ALERTS' => AdministratorAlertService::get_number_unread_alerts(),
	    		'IMG_PM' => $user->get_attribute('user_pm') > 0 ? 'new_pm.gif' : 'pm_mini.png',
	    		'U_CONTRIBUTION' => UserUrlBuilder::contribution_panel()->absolute(),
	    		'U_ADMINISTRATION' => UserUrlBuilder::administration()->absolute(),
	    		'U_HOME_PROFILE' => UserUrlBuilder::home_profile()->absolute(),
	    		'U_USER_PM' => UserUrlBuilder::personnal_message($user->get_attribute('user_id'))->absolute(),
	    		'U_USER_ID' => UserUrlBuilder::home_profile()->absolute(),
	    		'U_DISCONNECT' => UserUrlBuilder::disconnect()->absolute(),
	    		'U_AVATAR_IMG' => Url::to_rel($user_avatar),
	    		'L_NBR_PM' => ($user->get_attribute('user_pm') > 0 ? ($user->get_attribute('user_pm') . ' ' . (($user->get_attribute('user_pm') > 1) ? $LANG['message_s'] : $LANG['message'])) : $LANG['private_messaging']),
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
	    	$tpl->put_all(array(
	    		'C_USER_REGISTER' => (bool)UserAccountsConfig::load()->is_registration_enabled(),
	    		'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
				'L_REQUIRE_PASSWORD' => $LANG['require_password'],
				'L_CONNECT' => $LANG['connect'],
	    		'L_PSEUDO' => $LANG['pseudo'],
	    		'L_PASSWORD' => $LANG['password'],
	    		'L_AUTOCONNECT' => $LANG['autoconnect'],
	    		'L_FORGOT_PASS' => LangLoader::get_message('forget-password', 'user-common'),
	    		'L_REGISTER' => $LANG['register'],
	    		'U_CONNECT' => (QUERY_STRING != '') ? '?' . str_replace('&', '&amp;', QUERY_STRING) . '&amp;' : '',
	    		'U_REGISTER' => UserUrlBuilder::registration()->absolute()
	    	));
	    }
	
	    return $tpl->render();
    }
}
?>