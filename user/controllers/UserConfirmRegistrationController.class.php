<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2011 10 07
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
		$this->lang = LangLoader::get_all_langs();
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
			$controller = new UserErrorController($this->lang['user.profile'], $this->lang['warning.process.error'], UserErrorController::WARNING);
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
