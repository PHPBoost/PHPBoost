<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 19
 * @since       PHPBoost 3.0 - 2010 02 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminMemberDeleteController extends AdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		$user_id = $request->get_int('id', null);
		$user = UserService::get_user($user_id);

		if (!$user->is_admin() || ($user->is_admin() && UserService::count_admin_members() > 1))
		{
			try
			{
				UserService::delete_by_id($user_id);
			}
			catch (RowNotFoundException $ex) {
				$error_controller = PHPBoostErrors::unexisting_element();
				DispatchManager::redirect($error_controller);
			}

			HooksService::execute_hook_action('delete_user', 'user', array_merge($user->get_properties(), array('title' => $user->get_display_name())));
			AppContext::get_response()->redirect(($request->get_url_referrer() ? $request->get_url_referrer() : AdminMembersUrlBuilder::management()), StringVars::replace_vars(LangLoader::get_message('user.message.success.delete', 'user-lang'), array('name' => $user->get_display_name())));
		}
		else
		{
			$error_controller = PHPBoostErrors::unauthorized_action();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
