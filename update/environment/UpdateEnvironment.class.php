<?php
/*##################################################
 *                          UpdateEnvironment.class.php
 *                            -------------------
 *   begin                : February 27, 2012
 *   copyright            : (C) 2012 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class UpdateEnvironment extends Environment
{
	public static function load_imports()
	{
		Environment::load_imports();
	}

	public static function init()
	{
		Environment::fit_to_php_configuration();
		Environment::init_http_services();
		Environment::load_static_constants();
		self::load_dynamic_constants();
		self::init_output_bufferization();
		self::set_locale();
		self::init_admin_role();
	}

	public static function init_output_bufferization()
	{
		ob_start();
	}

	public static function load_dynamic_constants()
	{
		define('HOST', 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST')));
		$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
		define('FILE', $server_path);
		define('DIR', str_replace('/update/', '', $server_path));
		define('SID', '');
		define('TPL_PATH_TO_ROOT', PATH_TO_ROOT);
	}

	private static function set_locale()
	{
		$locale = AppContext::get_request()->get_getstring('locale', 'french');
		LangLoader::set_locale($locale);
	}

	private static function init_admin_role()
	{
		AppContext::set_current_user(new AdminUser());
		AppContext::set_session(new AdminSession());
	}

	public static function destroy()
	{
		ob_end_flush();
	}
}
?>