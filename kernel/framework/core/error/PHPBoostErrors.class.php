<?php
/**
 * @package     Core
 * @subpackage  Error
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 02
 * @since       PHPBoost 3.0 - 2009 12 09
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PHPBoostErrors
{
	public static function user_not_authorized(): UserErrorController
	{
		AppContext::get_response()->set_status_code(401);
		return new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), AppContext::get_current_user()->check_level(User::MEMBER_LEVEL) ? LangLoader::get_message('warning.auth', 'warning-lang') : LangLoader::get_message('warning.auth.guest', 'warning-lang'));
}

	public static function unexisting_page(): UserErrorController
	{
		AppContext::get_response()->set_status_code(404);
		return new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.page.unexists', 'warning-lang'));
	}

	public static function unexisting_element(): UserErrorController
	{
		AppContext::get_response()->set_status_code(404);
		return new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.element.unexists', 'warning-lang'));
	}

	public static function unknow(): UserErrorController
	{
		return new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.unknown', 'warning-lang'), UserErrorController::QUESTION);
	}

	public static function unauthorized_action(): UserErrorController
	{
		AppContext::get_response()->set_status_code(401);
		return new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.unauthorized.action', 'warning-lang'));
	}

	public static function registration_disabled(): UserErrorController
	{
		AppContext::get_response()->set_status_code(401);
		return new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.registration.disabled', 'warning-lang'));
	}

	public static function user_in_read_only(): UserErrorController
	{
		return new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.readonly', 'warning-lang'));
	}

	public static function flood(): UserErrorController
	{
		return new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.flood', 'warning-lang'));
	}

	public static function link_flood(int $max_link): UserErrorController
	{
		return new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), sprintf(LangLoader::get_message('warning.link.flood', 'warning-lang'), $max_link));
	}

	public static function module_not_installed(): UserErrorController
	{
		AppContext::get_response()->set_status_code(404);
		return new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.module.uninstalled', 'warning-lang'), UserErrorController::NOTICE);
	}

	public static function module_not_activated(): UserErrorController
	{
		AppContext::get_response()->set_status_code(404);
		return new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.module.disabled', 'warning-lang'), UserErrorController::NOTICE);
	}

	public static function CSRF(): UserErrorController
	{
		return new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.csrf.invalid.token', 'warning-lang'), UserErrorController::NOTICE);
	}
}
?>
