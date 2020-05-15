<?php
/**
 * @package     Core
 * @subpackage  Error
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 05 16
 * @since       PHPBoost 3.0 - 2009 12 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PHPBoostErrors
{
	public static function user_not_authorized()
	{
		AppContext::get_response()->set_status_code(401);
		return new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), AppContext::get_current_user()->check_level(User::MEMBER_LEVEL) ? LangLoader::get_message('error.auth', 'status-messages-common') : LangLoader::get_message('error.auth.guest', 'status-messages-common'));
}

	public static function unexisting_page()
	{
		AppContext::get_response()->set_status_code(404);
		return new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error.page.unexist', 'status-messages-common'));
	}

	public static function unexisting_element()
	{
		AppContext::get_response()->set_status_code(404);
		return new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('element.unexist', 'status-messages-common'));
	}

	public static function unknow()
	{
		return new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error.unknow', 'status-messages-common'), UserErrorController::QUESTION);
	}

	public static function unauthorized_action()
	{
		AppContext::get_response()->set_status_code(401);
		return new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error.action.unauthorized', 'status-messages-common'));
	}

	public static function registration_disabled()
	{
		AppContext::get_response()->set_status_code(401);
		return new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error.auth.registration_disabled', 'status-messages-common'));
	}

	public static function user_in_read_only()
	{
		return new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('e_readonly', 'errors'));
	}

	public static function flood()
	{
		return new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('e_flood', 'errors'));
	}

	public static function link_flood($max_link)
	{
		return new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), sprintf(LangLoader::get_message('e_l_flood', 'errors'), $max_link));
	}

	public static function module_not_installed()
	{
		AppContext::get_response()->set_status_code(404);
		return new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error.module.uninstalled', 'status-messages-common'), UserErrorController::NOTICE);
	}

	public static function module_not_activated()
	{
		AppContext::get_response()->set_status_code(404);
		return new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error.module.unactivated', 'status-messages-common'), UserErrorController::NOTICE);
	}

	public static function CSRF()
	{
		return new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('csrf_invalid_token', 'status-messages-common'), UserErrorController::NOTICE);
	}
}
?>
