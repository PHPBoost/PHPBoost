<?php
/*##################################################
 *                          environment.class.php
 *                            -------------------
 *   begin                : September 28, 2009
 *   copyright            : (C) 2009 Benoit Sautel, Loïc Rouchon
 *   email                : ben.popeye@phpboost.com, loic.rouchon@phpboost.com
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

throw new Exception();
	}

	public static function init()
	{
		Debug::enabled_current_script_debug();
		Debug::set_plain_text_output_mode();
		self::setup_server_env();
		self::fit_to_php_configuration();
		self::load_static_constants();
		//        self::load_dynamic_constants();
	}

	public static function load_dynamic_constants()
	{
		define('HOST', 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST')));
		$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
		define('FILE', $server_path);
		define('DIR', str_replace('/install/install-cli.php', '', $server_path));
		define('SID', '');
		define('TPL_PATH_TO_ROOT', PATH_TO_ROOT);
	}

	public function __destroy()
	{
		echo "\ntototot\n";
	}
}
?>