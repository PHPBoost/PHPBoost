<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 09 13
 * @since       PHPBoost 3.0 - 2010 02 03
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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
