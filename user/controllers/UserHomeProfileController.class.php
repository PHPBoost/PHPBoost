<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 23
 * @since       PHPBoost 3.0 - 2011 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class UserHomeProfileController extends AbstractController
{
	private $lang;
	private $tpl;
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
		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->user = AppContext::get_current_user();
		$this->tpl = new FileTemplate('user/UserHomeProfileController.tpl');
		$this->lang = LangLoader::get('user-common');
		$this->tpl->add_lang($this->lang);
	}

	private function build_form()
	{
		$contribution_number = $this->get_unread_contributions_number();
		$is_authorized_files_panel = $this->user->check_auth(FileUploadConfig::load()->get_authorization_enable_interface_files(), FileUploadConfig::AUTH_FILES_BIT);
		$this->tpl->put_all(array(
			'C_USER_AUTH_FILES' => $is_authorized_files_panel,
			'C_USER_INDEX' => true,
			'C_IS_MODERATOR' => $this->user->get_level() >= User::MODERATOR_LEVEL,
			'C_UNREAD_CONTRIBUTION' => $contribution_number != 0,
			'C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION' => $contribution_number > 0,
			'C_UNREAD_ALERT' => (bool)AdministratorAlertService::get_number_unread_alerts(),
			'C_HAS_PM' => $this->user->get_unread_pm() > 0,
			'C_AVATAR_IMG' => !empty(AppContext::get_session()->get_cached_data('user_avatar')),
			'COLSPAN' => $is_authorized_files_panel ? 3 : 2,
			'PSEUDO' => $this->user->get_display_name(),
			'NUMBER_UNREAD_ALERTS' => AdministratorAlertService::get_number_unread_alerts(),
			'NUMBER_UNREAD_CONTRIBUTIONS' => $contribution_number,
			'NUMBER_PM' => $this->user->get_unread_pm(),
			'MSG_MBR' => FormatingHelper::second_parse(UserAccountsConfig::load()->get_welcome_message()),
			'U_USER_PM' => UserUrlBuilder::personnal_message($this->user->get_id())->rel(),
			'U_CONTRIBUTION_PANEL' => UserUrlBuilder::contribution_panel()->rel(),
			'U_MODERATION_PANEL' => UserUrlBuilder::moderation_panel()->rel(),
			'U_UPLOAD' => UserUrlBuilder::upload_files_panel()->rel(),
			'U_AVATAR_IMG' => Url::to_rel(AppContext::get_session()->get_cached_data('user_avatar')),
			'U_VIEW_PROFILE' => UserUrlBuilder::profile($this->user->get_id())->rel()
		));

		$modules = AppContext::get_extension_provider_service()->get_extension_point(UserExtensionPoint::EXTENSION_POINT);
		foreach ($modules as $module)
		{
			$img = $module->get_messages_list_link_img();
			$this->tpl->assign_block_vars('modules_messages', array(
				'C_IMG_USER_MSG'  => !empty($img),
				'IMG_USER_MSG'    => $img,
				'NAME_USER_MSG'   => $module->get_messages_list_link_name(),
				'NUMBER_MESSAGES'  => (int)$module->get_number_messages($this->user->get_id()),
				'U_LINK_USER_MSG' => $module->get_messages_list_url($this->user->get_id())
			));
		}
	}

	private function get_unread_contributions_number()
	{
		$unread_contributions = UnreadContributionsCache::load();

		//Vaut 0 si l'utilisateur n'a aucune contribution. Est > 0 si on connait le nombre de contributions
		//Vaut -1 si l'utilisateur a au moins une contribution (mais on ne sait pas combien à cause des recoupements entre les groupes)
		$contribution_number = 0;

		if ($this->user->check_level(User::ADMIN_LEVEL))
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
		$graphical_environment->set_page_title($this->lang['dashboard'], $this->lang['user']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::home_profile());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user'], UserUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['dashboard'], UserUrlBuilder::home_profile()->rel());

		return $response;
	}
}
?>
