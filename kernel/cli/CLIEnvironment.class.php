<?php
/*##################################################
 *                          CLIEnvironment.class.php
 *                            -------------------
 *   begin                : February 03, 2010
 *   copyright            : (C) 2010 Loic Rouchon
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

require_once PATH_TO_ROOT . '/kernel/framework/core/environment/Environment.class.php';

class CLIEnvironment extends Environment
{
	public static function load_imports()
	{
		try
		{
			Environment::load_imports();
		}
		catch(Exception $ex)
		{
		}
	}

	public static function setup_server_env()
	{
		$_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_FILENAME'];
		$_SERVER['QUERY_STRING'] = '';
		$_SERVER['REQUEST_URI'] = '';
		$_SERVER['REMOTE_ADDR'] = '';
	}

	public static function init()
	{
		Debug::enabled_current_script_debug();
		Debug::set_plain_text_output_mode();
		set_exception_handler(array('Debug', 'fatal'));
		self::setup_server_env();
		self::fit_to_php_configuration();
		self::load_static_constants();
		self::load_dynamic_constants();
		AppContext::set_request(new HTTPRequestCustom());
		AppContext::set_session(SessionData::admin_session());
		AppContext::set_current_user(new AdminUser());
		AppContext::init_extension_provider_service();
		AppContext::set_response(new HTTPResponseCustom());
	}

	public static function load_dynamic_constants()
	{
		define('HOST', 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST')));
		$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
		define('FILE', $server_path);
		define('DIR', str_replace('/phpboost', '', $server_path));
		define('TPL_PATH_TO_ROOT', PATH_TO_ROOT);
	}
}
?>