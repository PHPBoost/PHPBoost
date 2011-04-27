<?php
/*##################################################
 *                       MemberHomeProfileController.class.php
 *                            -------------------
 *   begin                : September 18, 2010 2009
 *   copyright            : (C) 2010 Kévin MASSY
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

class MemberHomeProfileController extends AbstractController
{
	private $lang;

	private $tpl;

	public function execute(HTTPRequest $request)
	{
		$this->init();

		$user_id = AppContext::get_user()->get_attribute('user_id');
		if ($this->user_exist($user_id))
		{
			$this->build_form($user_id);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}
		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->tpl = new FileTemplate('member/MemberHomeProfileController.tpl');
		$this->lang = LangLoader::get('main');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_form($user_id)
	{
		$msg_mbr = FormatingHelper::second_parse(UserAccountsConfig::load()->get_welcome_message());

		$user = AppContext::get_user();$user = AppContext::get_user();
		$is_auth_files = $user->check_auth(FileUploadConfig::load()->get_authorization_enable_interface_files(), AUTH_FILES);
	
		$this->tpl->put_all(array(
			'C_USER_INDEX' => true,
			'C_IS_MODERATOR' => $user->get_attribute('level') >= MODERATOR_LEVEL,
			'SID' => SID,
			'LANG' => get_ulang(),
			'COLSPAN' => $is_auth_files ? 3 : 2,
			'USER_NAME' => $user->get_attribute('login'),
			'PM' => $user->get_attribute('user_pm'),
			'IMG_PM' => ($user->get_attribute('user_pm') > 0) ? 'new_pm.gif' : 'pm.png',
			'MSG_MBR' => $msg_mbr,
			'U_USER_ID' => url('.php?id=' . $user_id . '&amp;edit=true'),
			'U_USER_PM' => url('.php?pm=' . $user_id, '-' . $user_id . '.php'),
			'U_CONTRIBUTION_PANEL' => url('contribution_panel.php'),
			'U_MODERATION_PANEL' => url('moderation_panel.php'),
			'L_PROFIL' => $this->lang['profile'],
			'L_WELCOME' => $this->lang['welcome'],
			'L_PROFIL_EDIT' => $this->lang['profile_edition'],
			'L_FILES_MANAGEMENT' => $this->lang['files_management'],
			'L_PRIVATE_MESSAGE' => $this->lang['private_message'],
			'L_CONTRIBUTION_PANEL' => $this->lang['contribution_panel'],
			'L_MODERATION_PANEL' => $this->lang['moderation_panel']
		));
		
		//Affichage du lien vers l'interface des fichiers.
		if ($is_auth_files)
		{
			$this->tpl->put_all(array(
				'C_USER_AUTH_FILES' => true
			));
		}
	}

	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['profile']);
		return $response;
	}
	
	private function user_exist($user_id)
	{
		return PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE user_aprob = 1 AND user_id = '" . $user_id . "'") > 0 ? true : false;
	}
}

?>