<?php
/*##################################################
 *                          environment.class.php
 *                            -------------------
 *   begin                : September 28, 2009
 *   copyright            : (C) 2009 Benoit Sautel, Loic Rouchon
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

class InstallEnvironment extends Environment
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
		define('DIR', str_replace('/install/install.php', '', $server_path));
		define('TPL_PATH_TO_ROOT', PATH_TO_ROOT);
	}

	private static function set_locale()
	{
		$locale = AppContext::get_request()->get_getstring('locale', 'french');
		LangLoader::set_locale($locale);
	}

	private static function init_admin_role()
	{
		AppContext::set_session(new AdminSessionData());
		AppContext::set_user(new AdminUser());
	}

	public static function load_distribution_properties($prefered_lang)
	{
		global $DISTRIBUTION_MODULES;

		//If the distribution properties exist in the prefered language
		if (is_file('distribution/' . $prefered_lang . '.php'))
		{
			//We load them
			include('distribution/' . $prefered_lang . '.php');
		}
		else
		{
			//We try to load another lang

			$distribution_folder = new Folder('distribution');
			$distribution_files = $distribution_folder->get_files('`distribution_[a-z_-]+\.php`i');
			if (count($distribution_files) > 0)
			{
				include('distribution/distribution_' . $distribution_files[0]->get_name() . '.php');
			}
			else
			{
				//We couldn't load anything, we just have to define them to default values
				//Name of the distribution (localized)
				define('DISTRIBUTION_NAME', 'Default distribution');

				//Description of the distribution (localized)
				define('DISTRIBUTION_DESCRIPTION', 'This distribution is the default distribution. You will manage to install PHPBoost with the default configuration but it will install only the kernel without any module.');

				//Distribution default theme
				define('DISTRIBUTION_THEME', 'base');

				//Home page
				define('DISTRIBUTION_START_PAGE', DispatchManager::get_url('/member', '/member')->absolute());

				//Can people register?
				define('DISTRIBUTION_ENABLE_USER', false);

				//Debug mode?
				define('DISTRIBUTION_ENABLE_DEBUG_MODE', true);

				//Enable bench?
				define('DISTRIBUTION_ENABLE_BENCH', false);

				//Modules list
				$DISTRIBUTION_MODULES = array();
			}
		}
	}

	public static function destroy()
	{
		ob_end_flush();
	}
}
?>