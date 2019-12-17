<?php
/**
 * @package     Core
 * @subpackage  Error
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 04
 * @since       PHPBoost 3.0 - 2009 12 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PHPBoostErrors
{
	private $lang;

	public static function user_not_authorized()
	{
		AppContext::get_response()->set_status_code(401);
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), AppContext::get_current_user()->check_level(User::MEMBER_LEVEL) ? LangLoader::get_message('error.auth', 'status-messages-common') : LangLoader::get_message('error.auth.guest', 'status-messages-common'));
		return $controller;
	}

	public static function unexisting_page()
    {
    	AppContext::get_response()->set_status_code(404);
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error.page.unexist', 'status-messages-common'));
		return $controller;
    }

	public static function unexisting_element()
	{
		AppContext::get_response()->set_status_code(404);
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('element.unexist', 'status-messages-common'));
		return $controller;
	}

	public static function unknow()
    {
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error.unknow', 'status-messages-common'), UserErrorController::QUESTION);
		return $controller;
    }

	public static function unauthorized_action()
	{
		AppContext::get_response()->set_status_code(401);
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error.action.unauthorized', 'status-messages-common'));
		return $controller;
	}

	public static function registration_disabled()
	{
		AppContext::get_response()->set_status_code(401);
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error.auth.registration_disabled', 'status-messages-common'));
		return $controller;
	}

	public static function user_in_read_only()
	{
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $lang['e_readonly']);
		return $controller;
	}

	public static function flood()
	{
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $lang['e_flood']);
		return $controller;
	}

	public static function link_flood($max_link)
	{
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), sprintf($lang['e_l_flood'], $max_link));
		return $controller;
	}

	public static function module_not_installed()
	{
		AppContext::get_response()->set_status_code(404);
		$controller = new UserErrorController(
		LangLoader::get_message('error', 'status-messages-common'),
		LangLoader::get_message('error.module.uninstalled', 'status-messages-common'),
		UserErrorController::NOTICE);
		return $controller;
	}

	public static function module_not_activated()
	{
		AppContext::get_response()->set_status_code(404);
		$controller = new UserErrorController(
		LangLoader::get_message('error', 'status-messages-common'),
		LangLoader::get_message('error.module.unactivated', 'status-messages-common'),
		UserErrorController::NOTICE);
		return $controller;
	}

	public static function CSRF()
	{
		$controller = new UserErrorController(
		LangLoader::get_message('error', 'status-messages-common'),
		LangLoader::get_message('csrf_invalid_token', 'status-messages-common'),
		UserErrorController::NOTICE);
		return $controller;
	}
}
?>
