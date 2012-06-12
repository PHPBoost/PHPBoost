<?php
/*##################################################
 *                      UserConfirmRegistrationController.class.php
 *                            -------------------
 *   begin                : October 07, 2011
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

class UserConfirmRegistrationController extends AbstractController
{
	private $lang;
	
	public function execute(HTTPRequest $request)
	{
		$this->init();
		$key = $request->get_value('key', '');
		$this->check_activation($key);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('user-common');
	}

	private function check_activation($key)
	{
		if (UserService::approbation_pass_exists($key) && !empty($key))
		{
			$condition = 'WHERE approbation_pass = :new_approbation_pass';
			$parameters = array('new_approbation_pass' => $key);
			$user = UserService::get_user($condition, $parameters);
			$user_authentification = UserService::get_user_authentification($condition, $parameters);
	
			UserService::update_approbation_pass($key);
			StatsCache::invalidate();
			
			AppContext::get_session()->start($user->get_id(), $user_authentification->get_password_hashed(), 0, SCRIPT, QUERY_STRING, self::$lang['registration'], 1, true);
			
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
		else
		{
			$controller = new UserErrorController($this->lang['profile'], LangLoader::get_message('process.error', 'errors-common'), UserErrorController::WARNING);
			DispatchManager::redirect($controller);
		}
	}
	
	public function get_right_controller_regarding_authorizations()
	{
		if(AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
		return $this;
	}
}
?>