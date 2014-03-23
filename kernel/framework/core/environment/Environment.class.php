<?php
/*##################################################
 *                          Environment.class.php
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

/**
 * @package {@package}
 * This class manages all the environment that PHPBoost need to run.
 * <p>It's able to initialize the environment that contains services (database,
 * users management...) as well as the graphical environment.</p>
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class Environment
{
	private static $running_module_name = '';

	/**
	 * @var GraphicalEnvironment
	 */
	private static $graphical_environment = null;

	/**
	 * Loads all the files that the environment requires
	 */
	public static function load_imports()
	{
		require_once PATH_TO_ROOT . '/kernel/framework/helper/deprecated_helper.inc.php';

		include_once(PATH_TO_ROOT . '/kernel/framework/core/ClassLoader.class.php');
		ClassLoader::init_autoload();
		AppContext::init_bench();
	}

	/**
	 * Inits the environment and all its services.
	 */
	public static function init()
	{
		try
		{
			self::try_init();
		}
		catch (PHPBoostNotInstalledException $ex)
		{
            AppContext::get_response()->redirect('/install/');
		}
	}

	public static function try_init()
	{
		self::init_output_bufferization();
		self::fit_to_php_configuration();
		self::init_services();
		self::load_static_constants();
		DBFactory::load_prefix();

		// TODO Suppress uses of $Sql in the framework
		global $Sql;
		$Sql = PersistenceContext::get_sql();
		/* END DEPRECATED */

		self::load_dynamic_constants();
		self::init_session();

		// TODO move in begin
		/* DEPRECATED VARS */
		global $Session, $User, $Template;
		$Session = AppContext::get_session();
		$User = AppContext::get_current_user();
		$Template = new DeprecatedTemplate();
		/* END DEPRECATED */

		self::load_lang_files();
		self::process_changeday_tasks_if_needed();
		self::compute_running_module_name();
		self::execute_modules_changepage_tasks();
		self::csrf_protect_post_requests();
		self::enable_errors_and_exceptions_management();
	}

	public static function init_http_services()
	{
		AppContext::set_request(new HTTPRequestCustom());
		$response = new HTTPResponseCustom();
		$response->set_default_attributes();
		AppContext::set_response($response);
	}

	public static function init_services()
	{
		self::init_http_services();
		AppContext::init_session();
		AppContext::init_extension_provider_service();
	}

	public static function enable_errors_and_exceptions_management()
	{
		set_error_handler(array(new IntegratedErrorHandler(), 'handle'));
		set_exception_handler(array(new ExceptionHandler(), 'handle'));
	}

	public static function fit_to_php_configuration()
	{
		define('ERROR_REPORTING',   E_ALL | E_NOTICE | E_STRICT);
		@ini_set('display_errors', 'on');
		@ini_set('display_startup_errors', 'on');
		@error_reporting(ERROR_REPORTING);
		set_error_handler(array(new ErrorHandler(), 'handle'));
		set_exception_handler(array(new RawExceptionHandler(), 'handle'));
		Date::set_default_timezone();

		@ini_set('open_basedir', NULL);

		/* TODO deprecated for php 5.4 */
		//Disabling magic quotes if possible
		if (ServerConfiguration::get_phpversion() < '5.3')
		{
			@set_magic_quotes_runtime(0);
		}

		/* TODO deprecated for php 5.4 */
		//If the register globals option is enabled, we clear the automatically assigned variables
		if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on')
		{
			require_once PATH_TO_ROOT . '/kernel/framework/util/unusual_functions.inc.php';
			cancel_register_globals_effect();
		}

		/* TODO deprecated for php 5.4 */
		if (get_magic_quotes_gpc())
		{
			//If magic_quotes_sybase is enabled
			if (ini_get('magic_quotes_sybase') &&
			(strtolower(ini_get('magic_quotes_sybase')) != "off"))
			{
				//We consider the magic quotes as disabled
				define('MAGIC_QUOTES', false);

				//We treat the content: it must be as if the magic_quotes option is disabled
				foreach ($_REQUEST as $var_name => $value)
				{
					$_REQUEST[$var_name] = str_replace('\'\'', '\'', $value);
				}
			}
			//Magic quotes GPC
			else
			{
				define('MAGIC_QUOTES', true);
			}
		}
		else
		{
			define('MAGIC_QUOTES', false);
		}
	}

	public static function load_static_constants()
	{
		//Path from the server root
		define('SCRIPT', 			$_SERVER['PHP_SELF']);
		define('REWRITED_SCRIPT', 	$_SERVER['REQUEST_URI']);

		//Defines for PHP 5.1
		if (!defined('E_RECOVERABLE_ERROR'))
			define('E_RECOVERABLE_ERROR', 4096);
		
		//Get parameters
		define('QUERY_STRING', 		addslashes($_SERVER['QUERY_STRING']));
		define('PHPBOOST', 			true);
		define('E_UNKNOWN', 		0);
		define('E_TOKEN', 			-3);
		define('E_USER_REDIRECT', 	-1); //Deprecated
		define('E_USER_SUCCESS', 	-2);
		define('HTML_UNPROTECT', 	false);

		### Authorizations ###
		define('AUTH_FLOOD', 		'auth_flood');
		define('PM_GROUP_LIMIT', 	'pm_group_limit');
		define('DATA_GROUP_LIMIT', 	'data_group_limit');

		### Variable types ###
		define('GET', 		1);
		define('POST', 		2);
		define('REQUEST', 	3);
		define('COOKIE', 	4);
		define('FILES', 	5);

		define('TBOOL', 			'boolean');
		define('TINTEGER', 			'integer');
		define('TDOUBLE', 			'double');
		define('TFLOAT', 			'double');
		define('TSTRING', 			'string');
		define('TSTRING_PARSE', 	'string_parse');
		define('TSTRING_UNCHANGE', 	'string_unsecure');
		define('TSTRING_HTML', 		'string_html');
		define('TSTRING_AS_RECEIVED', 'string_unchanged');
		define('TARRAY', 			'array');
		define('TUNSIGNED_INT', 	'uint');
		define('TUNSIGNED_DOUBLE', 	'udouble');
		define('TUNSIGNED_FLOAT', 	'udouble');
		define('TNONE', 			'none');

		define('USE_DEFAULT_IF_EMPTY', 1);
	}

	public static function load_dynamic_constants()
	{
		$general_config = GeneralConfig::load();
		$site_path = $general_config->get_site_path();
		define('DIR', $site_path);
		define('HOST', $general_config->get_site_url());
		define('TPL_PATH_TO_ROOT', DIR);
	}

	public static function init_session()
	{
		AppContext::get_session()->load();
		AppContext::get_session()->act();

		AppContext::init_current_user();

		// TODO do we need to keep that feature? It's not supported every where
		if (AppContext::get_session()->supports_cookies())
		{
			define('SID', 'sid=' . AppContext::get_current_user()->get_attribute('session_id') .
				'&amp;suid=' . AppContext::get_current_user()->get_attribute('user_id'));
			define('SID2', 'sid=' . AppContext::get_current_user()->get_attribute('session_id') .
				'&suid=' . AppContext::get_current_user()->get_attribute('user_id'));
		}
		else
		{
			define('SID', '');
			define('SID2', '');
		}

		$current_user = AppContext::get_current_user();
		$user_accounts_config = UserAccountsConfig::load();
		
		$user_theme = ThemesManager::get_theme($current_user->get_theme());
		$default_theme = $user_accounts_config->get_default_theme();
		
		if ($user_theme !== null)
		{
			if ((!$user_theme->check_auth() || !$user_theme->is_activated()) && $user_theme->get_id() !== $default_theme)
			{
				AppContext::get_current_user()->update_theme($default_theme);
			}
		}
		else
		{
			AppContext::get_current_user()->update_theme($default_theme);
		}
		
		$user_lang = LangsManager::get_lang($current_user->get_locale());
		$default_lang = $user_accounts_config->get_default_lang();
		if ($user_lang !== null)
		{
			if ((!$user_lang->check_auth() || !$user_lang->is_activated()) && $user_lang->get_id() !== $default_lang)
			{
				AppContext::get_current_user()->update_lang($default_lang);
			}
		}
		else
		{
			AppContext::get_current_user()->update_lang($default_lang);
		}
	}
	
	public static function init_output_bufferization()
	{
		if (ServerEnvironmentConfig::load()->is_output_gziping_enabled() && !in_array('ob_gzhandler', ob_list_handlers()))
		{
			ob_start('ob_gzhandler');
		}
		else
		{
			ob_start();
		}
	}

	public static function load_lang_files()
	{
		LangLoader::set_locale(get_ulang());

		global $LANG;
		$LANG = array();
		require_once(PATH_TO_ROOT . '/lang/' . get_ulang() . '/main.php');
		require_once(PATH_TO_ROOT . '/lang/' . get_ulang() . '/errors.php');
	}

	public static function process_changeday_tasks_if_needed()
	{
		//If the day changed compared to the last request, we execute the daily tasks
		$last_use_config = LastUseDateConfig::load();
		$last_use_date = $last_use_config->get_last_use_date();
		$current_date = new Date();
		$current_date->set_hours(0);
		$current_date->set_minutes(0);
		$current_date->set_seconds(0);

		if ($last_use_date->is_anterior_to($current_date))
		{
			$last_use_config->set_last_use_date($current_date);
			LastUseDateConfig::save();
			
			self::perform_changeday();
		}
	}

	private static function perform_changeday()
	{
		self::delete_existing_sessions();

		self::clear_all_temporary_cache_files();

		self::execute_modules_changedays_tasks();

		self::remove_old_unactivated_member_accounts();

		self::check_updates();
	}

	private static function delete_existing_sessions()
	{
		AppContext::get_session()->garbage_collector();
	}

	private static function clear_all_temporary_cache_files()
	{
		//We delete all the images generated by the LaTeX formatter
		$cache_image_folder_path = new Folder(PATH_TO_ROOT . '/images/maths/');
		foreach ($cache_image_folder_path->get_files('`\.png$`') as $image)
		{
			if ($image->get_last_modification_date() < self::get_one_week_ago_timestamp())
			{
				$image->delete();
			}
		}
	}

	private static function execute_modules_changedays_tasks()
	{
		$today = new Date();
		$yesterday = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, self::get_yesterday_timestamp());
		$jobs = AppContext::get_extension_provider_service()->get_extension_point(ScheduledJobExtensionPoint::EXTENSION_POINT);
		foreach ($jobs as $job)
		{
			$job->on_changeday($yesterday, $today);
		}
	}

	private static function remove_old_unactivated_member_accounts()
	{
		$user_account_settings = UserAccountsConfig::load();

		$delay_unactiv_max = $user_account_settings->get_unactivated_accounts_timeout() * 3600 * 24;
		//If the user configured a delay and member accounts must be activated
		if ($delay_unactiv_max > 0 && $user_account_settings->get_member_accounts_validation_method() != 2)
		{
			PersistenceContext::get_querier()->delete(DB_TABLE_MEMBER, 'WHERE timestamp < :timestamp AND user_aprob = 0', array(
				'timestamp' => (time() - $delay_unactiv_max
			)));
		}
	}

	private static function check_updates()
	{
		new Updates();
	}
	
	private static function execute_modules_changepage_tasks()
	{
		$jobs = AppContext::get_extension_provider_service()->get_extension_point(ScheduledJobExtensionPoint::EXTENSION_POINT);
		foreach ($jobs as $job)
		{
			$job->on_changepage();
		}
	}

	public static function compute_running_module_name()
	{
		$path = str_replace(DIR, '', SCRIPT);
		$path = trim($path, '/');
		if (strpos($path, '/'))
		{
        	$module_name = explode('/', $path);
        	self::$running_module_name = $module_name[0];
		}
		else
		{
			$general_config = GeneralConfig::load();
			$other_home_page = $general_config->get_other_home_page();
			$module_home_page = $general_config->get_module_home_page();
			
			if (empty($other_home_page) && !empty($module_home_page))
			{
				self::$running_module_name = $general_config->get_module_home_page();
			}
			else
				self::$running_module_name = '';
		}
	}

	/**
	 * @desc Retrieves the identifier (name of the folder) of the module which is currently executed.
	 * @return string The module identifier.
	 */
	public static function get_running_module_name()
	{
		return self::$running_module_name;
	}

	public static function csrf_protect_post_requests()
	{
		// Verify that the user really wanted to do this POST (only for the registered ones)
		if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			AppContext::get_session()->csrf_post_protect();
		}
	}

	/**
	 * @desc Retrieves the site start page.
	 * @return The absolute start page URL.
	 */
	public static function get_home_page()
	{
		$general_config = GeneralConfig::load();
		if ($general_config->get_module_home_page())
		{
			return Url::to_absolute('/index.php');
		}
		return Url::to_absolute($general_config->get_other_home_page());
	}

	/**
	 * @desc Returns the full phpboost version with its build number
	 * @return string the full phpboost version with its build number
	 */
	public static function get_phpboost_version()
	{
		$major_version = GeneralConfig::load()->get_phpboost_major_version();
		$minor_version = self::get_phpboost_minor_version();
		return $major_version . '.' . $minor_version;
	}

	private static function get_phpboost_minor_version()
	{
		$file = new File(PATH_TO_ROOT . '/kernel/.build');
		$build =  $file->read();
		$file->close();
		return trim($build);
	}

	/**
	 * Displays the environment with content of the page.
	 */
	public static function display($content)
	{
		self::get_graphical_environment()->display($content);
	}
	
	public static function set_graphical_environment(GraphicalEnvironment $env)
	{
		self::$graphical_environment = $env;
	}

	public static function destroy()
	{
		PersistenceContext::close_db_connection();

		@ob_end_flush();
	}

	private static function get_yesterday_timestamp()
	{
		return time() - 86400;
	}

	private static function get_one_week_ago_timestamp()
	{
		return time() - 3600 * 24 * 7;
	}

	/**
	 * @return GraphicalEnvironment
	 */
	public static function get_graphical_environment()
	{
		if (self::$graphical_environment === null)
		{
			self::$graphical_environment = new SiteDisplayGraphicalEnvironment();
		}
		return self::$graphical_environment;
	}

	/**
	 * This method is not called automatically but can be called if you know that an action can
	 * take a long time. By default, max execution time is 30 seconds.
	 * Note that according to PHP configuration, this function can fail.
	 */
	public static function try_to_increase_max_execution_time()
	{
		@set_time_limit(600);
	}
}
?>