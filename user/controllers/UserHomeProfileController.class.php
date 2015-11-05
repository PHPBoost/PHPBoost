<?php
/*##################################################
 *                       UserHomeProfileController.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
			'COLSPAN' => $is_authorized_files_panel ? 3 : 2,
			'PSEUDO' => $this->user->get_display_name(),
			'NUMBER_UNREAD_ALERTS' => AdministratorAlertService::get_number_unread_alerts(),
			'NUMBER_UNREAD_CONTRIBUTIONS' => $contribution_number,
			'NUMBER_PM' => $this->user->get_unread_pm(),
			'MSG_MBR' => FormatingHelper::second_parse(UserAccountsConfig::load()->get_welcome_message()),
			'U_USER_ID' => UserUrlBuilder::profile($this->user->get_id())->rel(),
			'U_USER_PM' => UserUrlBuilder::personnal_message($this->user->get_id())->rel(),
			'U_CONTRIBUTION_PANEL' => UserUrlBuilder::contribution_panel()->rel(),
			'U_MODERATION_PANEL' => UserUrlBuilder::moderation_panel()->rel(),
			'U_UPLOAD' => UserUrlBuilder::upload_files_panel()->rel(),
			'U_VIEW_PROFILE' => UserUrlBuilder::profile($this->user->get_id())->rel()
		));
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
		$graphical_environment->set_page_title($this->lang['profile'], $this->lang['user']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user'], UserUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['profile'], UserUrlBuilder::profile($this->user->get_id())->rel());
		
		return $response;
	}
}
?>