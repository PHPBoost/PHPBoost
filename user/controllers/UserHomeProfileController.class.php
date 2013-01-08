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
	private $main_lang;
	private $tpl;
	private $user;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$user_id = $this->user->get_id();
		if (!UserService::user_exists('WHERE user_id=:user_id', array('user_id' => $user_id)))
		{
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}
		$this->build_form($user_id);
		return $this->build_response($this->tpl, $user_id);
	}

	private function init()
	{
		$this->user = AppContext::get_current_user();
		$this->tpl = new FileTemplate('user/UserHomeProfileController.tpl');
		$this->lang = LangLoader::get('user-common');
		$this->main_lang = LangLoader::get('main');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_form($user_id)
	{
		$is_authorized_files_panel = $this->user->check_auth(FileUploadConfig::load()->get_authorization_enable_interface_files(), AUTH_FILES);
		$this->tpl->put_all(array(
			'C_USER_AUTH_FILES' => $is_authorized_files_panel,
			'C_USER_INDEX' => true,
			'C_IS_MODERATOR' => $this->user->get_attribute('level') >= User::MODERATOR_LEVEL,
			'SID' => SID,
			'LANG' => get_ulang(),
			'COLSPAN' => $is_authorized_files_panel ? 3 : 2,
			'USER_NAME' => $this->user->get_attribute('login'),
			'PM' => $this->user->get_attribute('user_pm'),
			'IMG_PM' => ($this->user->get_attribute('user_pm') > 0) ? 'new_pm.gif' : 'pm.png',
			'MSG_MBR' => FormatingHelper::second_parse(UserAccountsConfig::load()->get_welcome_message()),
			'U_USER_ID' => UserUrlBuilder::profile($user_id)->absolute(),
			'U_USER_PM' => UserUrlBuilder::personnal_message($user_id)->absolute(),
			'U_CONTRIBUTION_PANEL' => UserUrlBuilder::contribution_panel()->absolute(),
			'U_MODERATION_PANEL' => UserUrlBuilder::moderation_panel()->absolute(),
			'U_UPLOAD' => UserUrlBuilder::upload_files_panel()->absolute(),
			'U_EDIT_PROFILE' => UserUrlBuilder::edit_profile()->absolute(),
			'L_PROFIL' => $this->lang['profile'],
			'L_WELCOME' => $this->main_lang['welcome'],
			'L_PROFIL_EDIT' => $this->lang['profile.edit'],
			'L_FILES_MANAGEMENT' => $this->main_lang['files_management'],
			'L_PRIVATE_MESSAGE' => $this->main_lang['private_message'],
			'L_CONTRIBUTION_PANEL' => $this->main_lang['contribution_panel'],
			'L_MODERATION_PANEL' => $this->main_lang['moderation_panel']
		));
	}

	private function build_response(View $view, $user_id)
	{
		$response = new UserDisplayResponse();
		$response->set_page_title($this->lang['profile']);
		$response->add_breadcrumb($this->lang['user'], UserUrlBuilder::users()->absolute());
		$response->add_breadcrumb($this->lang['profile'], UserUrlBuilder::profile($user_id)->absolute());
		return $response->display($view);
	}
}
?>