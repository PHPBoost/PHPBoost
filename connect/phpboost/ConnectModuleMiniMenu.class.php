<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 04
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ConnectModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__SUB_HEADER;
	}

	public function display($tpl = false)
	{
		$lang = LangLoader::get('main');

		if (!Url::is_current_url('/login'))
		{
			$tpl = new FileTemplate('connect/connect_mini.tpl');
			$tpl->add_lang(LangLoader::get('user-common'));
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
				$user_avatar = AppContext::get_session()->get_cached_data('user_avatar');
				if (empty($user_avatar))
				{
					$user_avatar = '/templates/'. AppContext::get_current_user()->get_theme() .'/images/'. $user_accounts_config->get_default_avatar_name();
				}
				$total_alert = $user->get_unread_pm() + $contribution_number + ($user->check_level(User::ADMIN_LEVEL) ? AdministratorAlertService::get_number_unread_alerts() : 0);

				$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

				$tpl->put_all(array(
					'C_ADMIN_AUTH' => $user->check_level(User::ADMIN_LEVEL),
					'C_MODERATOR_AUTH' => $user->check_level(User::MODERATOR_LEVEL),
					'C_UNREAD_CONTRIBUTION' => $contribution_number != 0,
					'C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION' => $contribution_number > 0,
					'C_UNREAD_ALERT' => (bool)AdministratorAlertService::get_number_unread_alerts(),
					'C_HAS_PM' => $user->get_unread_pm() > 0,
					'C_USER_GROUP_COLOR' => !empty($user_group_color),
					'NUMBER_UNREAD_CONTRIBUTIONS' => $contribution_number,
					'NUMBER_UNREAD_ALERTS' => AdministratorAlertService::get_number_unread_alerts(),
					'NUMBER_PM' => $user->get_unread_pm(),
					'NUMBER_TOTAL_ALERT' => $total_alert,
					'PSEUDO' => $user->get_display_name(),
					'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
					'USER_GROUP_COLOR' => $user_group_color,
					'U_USER_PROFILE' => UserUrlBuilder::profile($user->get_id())->rel(),
					'U_USER_PM' => UserUrlBuilder::personnal_message($user->get_id())->rel(),
					'U_AVATAR_IMG' => Url::to_rel($user_avatar),
					'L_NBR_PM'  => $user->get_unread_pm() > 0 ? ($user->get_unread_pm() . ' ' . ($user->get_unread_pm() > 1 ? $lang['message_s'] : $lang['message'])) : $lang['private_messaging'],
					'L_MESSAGE' => $user->get_unread_pm() > 1 ? $lang['message_s'] : $lang['message'],
					'L_PM_PANEL' => $lang['private_messaging'],
					'L_ADMIN_PANEL' => $lang['admin_panel'],
					'L_MODO_PANEL' => $lang['modo_panel'],
					'L_PRIVATE_PROFIL' => $lang['my_private_profile'],
					'L_CONTRIBUTION_PANEL' => $lang['contribution_panel']
				));
			}
			else
			{
				$external_authentication = 0;
				
				foreach (AuthenticationService::get_external_auths_activated() as $id => $authentication)
				{
					$tpl->assign_block_vars('external_auth', array(
						'U_CONNECT' => UserUrlBuilder::connect($id)->rel(),
						'ID' => $id,
						'NAME' => $authentication->get_authentication_name(),
						'IMAGE_HTML' => $authentication->get_image_renderer_html(),
						'CSS_CLASS' => $authentication->get_css_class()
					));
					$external_authentication++;
				}
				
				$tpl->put_all(array(
					'C_USER_NOTCONNECTED' => true,
					'C_USER_REGISTER' => UserAccountsConfig::load()->is_registration_enabled(),
					'C_DISPLAY_REGISTER_CONTAINER' => $external_authentication || UserAccountsConfig::load()->is_registration_enabled(),
					'L_REQUIRE_PSEUDO' => $lang['require_pseudo'],
					'L_REQUIRE_PASSWORD' => $lang['require_password'],
					'U_CONNECT' => UserUrlBuilder::connect()->rel(),
					'SITE_REWRITED_SCRIPT' => TextHelper::substr(REWRITED_SCRIPT, TextHelper::strlen(GeneralConfig::load()->get_site_path()))
				));
			}

			return $tpl->render();
		}
		return '';
	}
}
?>
