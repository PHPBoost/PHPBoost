<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 10 07
*/

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
