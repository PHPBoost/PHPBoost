<?php
/*##################################################
 *                          PHPBoostError.class.php
 *                            -------------------
 *   begin                : December 9, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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