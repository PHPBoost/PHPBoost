<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 13
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

	public function get_formated_title()
	{
		return LangLoader::get_message('user.sign.in', 'user-lang');
	}

	public function display($view = false)
	{
		if (!Url::is_current_url('/login'))
		{
			$view = new FileTemplate('connect/connect_mini.tpl');
			$view->add_lang(LangLoader::get_all_langs());
			$user = AppContext::get_current_user();
			MenuService::assign_positions_conditions($view, $this->get_block());

			if ($user->check_level(User::MEMBER_LEVEL)) // if user is connected.
			{
				$unread_contributions = UnreadContributionsCache::load();

				// = 0 if user has no contribution.
				// > 0 if the number of contribution is known
				// = -1 if user has at least one contribution but unknown number of contribution because of the overlap between groups
				$contribution_number = 0;

				if ($user->check_level(User::ADMINISTRATOR_LEVEL))
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

				$total_alert = $user->get_unread_pm() + $contribution_number + ($user->check_level(User::ADMINISTRATOR_LEVEL) ? AdministratorAlertService::get_number_unread_alerts() : 0);

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

					'U_USER_PUBLICATIONS' => UserUrlBuilder::publications($user->get_id())->rel(),
					'U_USER_PROFILE'      => UserUrlBuilder::profile($user->get_id())->rel(),
					'U_USER_PM'           => UserUrlBuilder::personnal_message($user->get_id())->rel(),
					'U_USER_AVATAR'       => $user_avatar ? Url::to_rel($user_avatar) : $user_accounts_config->get_default_avatar(),
				));

				$this->display_additional_menus($view, $user);
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

	private function display_additional_menus($view, $user)
	{
		foreach (AppContext::get_extension_provider_service()->get_extension_point(ConnectMenuLink::EXTENSION_POINT) as $id => $provider)
		{
			if ($provider && $provider instanceof ConnectMenuLink)
			{
				$view->assign_block_vars('additional_menus', array(
					'C_DISPLAY'              => $user->get_level() >= $provider->get_display_level(),
					'C_UNREAD_ELEMENTS'      => $provider->get_unread_elements_number() > 0,
					'C_ICON'                 => $provider->get_icon() != '',
					'ICON'                   => $provider->get_icon(),
					'LABEL'                  => $provider->get_label(),
					'LEVEL_CLASS'            => UserService::get_level_class($provider->get_display_level()),
					'MENU_NAME'              => $provider->get_menu_name(),
					'UNREAD_ELEMENTS_NUMBER' => $provider->get_unread_elements_number(),
					'URL'                    => $provider->get_url() instanceof Url ? $provider->get_url()->rel() : Url::to_rel($provider->get_url())
				));
			}
		}
	}
}
?>
