<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 17
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ConnectModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__SUB_HEADER;
	}

	public function display($view = false)
	{
		if (!Url::is_current_url('/login'))
		{
			$view = new FileTemplate('connect/connect_mini.tpl');
			$view->add_lang(LangLoader::get('user-lang'));
			$user = AppContext::get_current_user();
			MenuService::assign_positions_conditions($view, $this->get_block());

			if ($user->check_level(User::MEMBER_LEVEL)) // if user is connected.
			{
				$unread_contributions = UnreadContributionsCache::load();

				// = 0 if user has no contribution.
				// > 0 if the number of contribution is known
				// = -1 if user has at least one contribution but unknown number of contribution because of the overlap between groups
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

				$total_alert = $user->get_unread_pm() + $contribution_number + ($user->check_level(User::ADMIN_LEVEL) ? AdministratorAlertService::get_number_unread_alerts() : 0);

				$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

				$view->put_all(array(
					'C_UNREAD_CONTRIBUTIONS'  => $contribution_number != 0,
					'C_SEVERAL_CONTRIBUTIONS' => $contribution_number > 1,
					'C_UNREAD_ALERTS'         => (bool)AdministratorAlertService::get_number_unread_alerts(),
					'C_HAS_PM'                => $user->get_unread_pm() > 0,
					'C_SEVERAL_PM'			  => $user->get_unread_pm() > 1,
					'C_USER_GROUP_COLOR'      => !empty($user_group_color),
					'C_USER_AVATAR'           => $user_avatar || $user_accounts_config->is_default_avatar_enabled(),

					'ALERTS_TOTAL_NUMBER'         => $total_alert,
					'UNREAD_CONTRIBUTIONS_NUMBER' => $contribution_number,
					'UNREAD_ALERTS_NUMBER'        => AdministratorAlertService::get_number_unread_alerts(),
					'PM_NUMBER'                   => $user->get_unread_pm(),
					'USER_DISPLAYED_NAME'         => $user->get_display_name(),
					'USER_LEVEL_CLASS'            => UserService::get_level_class($user->get_level()),
					'USER_GROUP_COLOR'            => $user_group_color,

					'U_USER_PROFILE' => UserUrlBuilder::profile($user->get_id())->rel(),
					'U_USER_PM'      => UserUrlBuilder::personnal_message($user->get_id())->rel(),
					'U_USER_AVATAR'  => $user_avatar ? Url::to_rel($user_avatar) : $user_accounts_config->get_default_avatar(),
				));
			}
			else
			{
				$external_authentication = 0;

				foreach (AuthenticationService::get_external_auths_activated() as $id => $authentication)
				{
					$view->assign_block_vars('external_auth', array(
						'U_SIGN_IN'  => UserUrlBuilder::connect($id)->rel(),
						'ID'         => $id,
						'NAME'       => $authentication->get_authentication_name(),
						'IMAGE_HTML' => $authentication->get_image_renderer_html(),
						'CSS_CLASS'  => $authentication->get_css_class()
					));
					$external_authentication++;
				}

				$view->put_all(array(
					'C_REGISTRATION_ENABLED' => UserAccountsConfig::load()->is_registration_enabled(),
					'C_REGISTRATION_DISPLAYED' => $external_authentication || UserAccountsConfig::load()->is_registration_enabled(),

					'SITE_REWRITED_SCRIPT' => TextHelper::substr(REWRITED_SCRIPT, TextHelper::strlen(GeneralConfig::load()->get_site_path())),

					'U_SIGN_IN' => UserUrlBuilder::connect()->rel(),
				));
			}

			return $view->render();
		}
		return '';
	}
}
?>
