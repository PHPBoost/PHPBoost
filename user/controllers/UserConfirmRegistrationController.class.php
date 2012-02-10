<?php
/*##################################################
 *                      UserConfirmRegistrationController.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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
			UserService::update_approbation_pass($key);
			
			StatsCache::invalidate();
			
			$controller = new UserErrorController($this->lang['profile'], $this->lang['registration.confirm.success'], UserErrorController::SUCCESS);
			DispatchManager::redirect($controller);
		}
		else
		{
			$controller = new UserErrorController($this->lang['profile'], $this->lang['registration.confirm.error'], UserErrorController::WARNING);
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