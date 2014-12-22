<?php
/*##################################################
 *                      UserConfirmRegistrationController.class.php
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

class UserConfirmRegistrationController extends AbstractController
{
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$registration_pass = $request->get_value('registration_pass', '');
		$this->check_activation($registration_pass);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('user-common');
	}

	private function check_activation($registration_pass)
	{
		$user_id = PHPBoostAuthenticationMethod::registration_pass_exists($registration_pass);
		if ($user_id)
		{
			PHPBoostAuthenticationMethod::update_auth_infos($user_id, null, true, null, '');

			$session = AppContext::get_session();
			if ($session != null)
			{
				Session::delete($session);
			}
			AppContext::set_session(Session::create($user_id, true));
			
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
		else
		{
			$controller = new UserErrorController($this->lang['profile'], LangLoader::get_message('process.error', 'status-messages-common'), UserErrorController::WARNING);
			DispatchManager::redirect($controller);
		}
	}
	
	public function get_right_controller_regarding_authorizations()
	{
		if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
		return $this;
	}
}
?>