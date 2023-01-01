<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 09 20
 * @since       PHPBoost 3.0 - 2011 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class UserHomeProfileController extends AbstractController
{
	private $lang;
	private $view;
	private $user;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		if (!$this->user->check_level(User::MEMBER_LEVEL))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		$this->build_form();
		return $this->build_response($this->view);
	}

	private function init()
	{
		$this->user = AppContext::get_current_user();
		$this->view = new FileTemplate('user/UserHomeProfileController.tpl');
		$this->lang = LangLoader::get_all_langs();
		$this->view->add_lang($this->lang);
	}

	private function build_form()
	{
		$user_avatar = AppContext::get_session()->get_cached_data('user_avatar');
		$user_accounts_config = UserAccountsConfig::load();
		$contribution_number = $this->get_unread_contributions_number();
		$is_authorized_files_panel = $this->user->check_auth(FileUploadConfig::load()->get_authorization_enable_interface_files(), FileUploadConfig::AUTH_FILES_BIT);
		$group_color = User::get_group_color($this->user->get_groups(), $this->user->get_level());

		$registration_since = !empty($this->user->get_registration_date()) ? Date::to_format($this->user->get_registration_date(), Date::FORMAT_SINCE) : $this->lang['common.unknown'];

		$this->view->put_all(array(
			'C_USER_AUTH_FILES'                     => $is_authorized_files_panel,
			'C_USER_INDEX'                          => true,
			'C_UNREAD_CONTRIBUTION'                 => $contribution_number != 0,
			'C_UNREAD_ALERT'                        => (bool)AdministratorAlertService::get_number_unread_alerts(),
			'C_HAS_PM'                              => $this->user->get_unread_pm() > 0,
			'C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION' => $contribution_number > 0,

			'IS_MODERATOR'                          => $this->user->get_level() >= User::MODERATOR_LEVEL,
			'IS_ADMINISTRATOR'                      => $this->user->get_level() >= User::ADMINISTRATOR_LEVEL,

			'COLSPAN'                               => $is_authorized_files_panel ? 3 : 2,
			'PSEUDO'                                => $this->user->get_display_name(),
			'NUMBER_PM'                             => $this->user->get_unread_pm(),
			'MSG_MBR'                               => FormatingHelper::second_parse(UserAccountsConfig::load()->get_welcome_message()),
			'NUMBER_UNREAD_ALERTS'                  => AdministratorAlertService::get_number_unread_alerts(),
			'NUMBER_UNREAD_CONTRIBUTIONS'           => $contribution_number,

			'U_USER_PM'                             => UserUrlBuilder::personnal_message($this->user->get_id())->rel(),
			'U_CONTRIBUTION_PANEL'                  => UserUrlBuilder::contribution_panel()->rel(),
			'U_MODERATION_PANEL'                    => UserUrlBuilder::moderation_panel()->rel(),
			'U_UPLOAD'                              => UserUrlBuilder::upload_files_panel()->rel(),
			'U_VIEW_PROFILE'                        => UserUrlBuilder::profile($this->user->get_id())->rel(),
			'U_USER_PUBLICATIONS' 					=> UserUrlBuilder::publications($this->user->get_id())->rel(),

			'C_AVATAR_IMG'                          => !empty($user_avatar) || $user_accounts_config->is_default_avatar_enabled(),
			'U_AVATAR_IMG'                          => $user_avatar ? Url::to_rel($user_avatar) : $user_accounts_config->get_default_avatar(),

			'C_USER_GROUP_COLOR'         			=> !empty($group_color),
			'USER_GROUP_COLOR'                      => $group_color,

			'USER_LEVEL_NAME'                       => UserService::get_level_lang($this->user->get_level()),
			'USER_LEVEL_CLASS'                      => UserService::get_level_class($this->user->get_level()),

			'USER_SUBSCRIBE_DATE'					=> $this->lang['user.registered'] . " " . $registration_since
		));

		$modules_with_publications = AppContext::get_extension_provider_service()->get_extension_point(UserExtensionPoint::EXTENSION_POINT);
		foreach (array_merge(array('user' => true), ModulesManager::get_activated_modules_map_sorted_by_localized_name()) as $id => $installed_module)
		{
			if (in_array($id, array_keys($modules_with_publications)))
			{
				$module = $modules_with_publications[$id];
				$module_icon = new File(PATH_TO_ROOT . '/' . $module->get_publications_module_id() . '/' . $module->get_publications_module_id() . '.png');
				$is_picture = false;
				if($module->get_publications_module_icon() != '')
				{
					$thumbnail = $module->get_publications_module_icon();
				}
				else if($module_icon->exists())
				{
					$thumbnail = Url::to_rel($module_icon->get_path());
					$is_picture = true;
				}
				else if($module->get_publications_module_id() != '')
				{
					$thumbnail = ModulesManager::get_module($module->get_publications_module_id())->get_configuration()->get_fa_icon();
				}
				else
					$thumbnail = 'fa fa-fw fa-cube';

				$this->view->assign_block_vars('user_publications', array(
					'C_ICON_IS_PICTURE'   => $is_picture,
					'MODULE_NAME'         => $module->get_publications_module_name(),
					'MODULE_THUMBNAIL'    => $thumbnail,
					'U_MODULE_VIEW'       => $module->get_publications_module_view($this->user->get_id()),
					'PUBLICATIONS_NUMBER' => (int)$module->get_publications_number($this->user->get_id())
				));
			}
		}
	}

	private function get_unread_contributions_number()
	{
		$unread_contributions = UnreadContributionsCache::load();

		// Worth 0 if the user has no contribution. Is > 0 if the number of contributions is known
		// Worth -1 if the user has at least one contribution (but we do not know how much because of the overlaps between the groups)
		$contribution_number = 0;

		if ($this->user->check_level(User::ADMINISTRATOR_LEVEL))
		{
			$contribution_number = $unread_contributions->get_admin_unread_contributions_number();
		}
		elseif ($this->user->check_level(User::MODERATOR_LEVEL))
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
			else if ($unread_contributions->has_user_unread_contributions($this->user->get_id()))
			{
				$contribution_number = -1;
			}
			else
			{
				foreach ($this->user->get_groups() as $group_id)
				{
					if ($unread_contributions->has_group_unread_contributions($group_id))
					{
						$contribution_number = -1;
						break;
					}
				}
			}
		}
		return $contribution_number;
	}

	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['user.dashboard'], $this->lang['user.user']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::home_profile());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user.user'], UserUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['user.dashboard'], UserUrlBuilder::home_profile()->rel());

		return $response;
	}
}
?>
