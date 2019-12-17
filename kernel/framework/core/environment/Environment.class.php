<?php
/**
 * This class manages all the environment that PHPBoost need to run.
 * <p>It's able to initialize the environment that contains services (database,
 * users management...) as well as the graphical environment.</p>
 * @package     Core
 * @subpackage  Environment
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 03 26
 * @since       PHPBoost 3.0 - 2009 09 28
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

class Environment
{
	private static $running_module_name = '';
	private static $home_page_running;

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
		self::redirect_to_update_script_if_needed();
		self::init_output_bufferization();
		self::fit_to_php_configuration();
		self::init_services();
		self::load_static_constants();
		DBFactory::load_prefix();

		self::load_dynamic_constants();
		self::init_session();
		self::set_default_timezone();

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

		//check (if function is enabled) and setup php for working with Unicode data
		if (function_exists('mb_internal_encoding')) { mb_internal_encoding('UTF-8'); }
		if (function_exists('mb_regex_encoding')) { mb_regex_encoding('UTF-8'); }
		if (function_exists('mb_http_output')) { mb_http_output('UTF-8'); }
		if (function_exists('mb_http_input')) { mb_http_input('UTF-8'); }
		if (function_exists('mb_language')) { mb_language('uni'); }
		ob_start('mb_output_handler');

		@ini_set('open_basedir', NULL);
	}

	public static function load_static_constants()
	{
		//Path from the server root
		define('SCRIPT', 			TextHelper::htmlspecialchars($_SERVER['PHP_SELF']));
		define('REWRITED_SCRIPT', 	TextHelper::htmlspecialchars($_SERVER['REQUEST_URI']));

		//Get parameters
		define('QUERY_STRING', 		addslashes($_SERVER['QUERY_STRING']));
		define('PHPBOOST', 			true);

		### Authorizations ###
		define('AUTH_FLOOD', 		'auth_flood');
		define('PM_GROUP_LIMIT', 	'pm_group_limit');
		define('DATA_GROUP_LIMIT', 	'data_group_limit');
	}

	public static function load_dynamic_constants()
	{
		$general_config = GeneralConfig::load();
		$site_path = $general_config->get_site_path();
		define('DIR', $site_path);
		define('HOST', AppContext::get_request()->get_site_url());
		define('TPL_PATH_TO_ROOT', DIR);
	}

	public static function init_session()
	{
		Session::gc();
		$session_data = Session::start();
		AppContext::set_session($session_data);
		AppContext::init_current_user();

		$current_user = AppContext::get_current_user();
		$user_accounts_config = UserAccountsConfig::load();

		$user_theme = ThemesManager::get_theme($current_user->get_theme());
		$default_theme = $user_accounts_config->get_default_theme();

		if ($user_theme === null || (!$user_theme->check_auth() || !$user_theme->is_activated()) && $user_theme->get_id() !== $default_theme)
		{
			AppContext::get_current_user()->update_theme($default_theme);
		}

		$user_lang = LangsManager::get_lang($current_user->get_locale());
		$default_lang = $user_accounts_config->get_default_lang();
		if ($user_lang === null || (!$user_lang->check_auth() || !$user_lang->is_activated()) && $user_lang->get_id() !== $default_lang)
		{
			AppContext::get_current_user()->update_lang($default_lang);
		}
	}

	public static function set_default_timezone()
	{
		Date::set_default_timezone();
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
		$locale = AppContext::get_current_user()->get_locale();
		LangLoader::set_locale($locale);

		global $LANG;
		$LANG = array();
		require_once(PATH_TO_ROOT . '/lang/' . $locale . '/main.php');
		require_once(PATH_TO_ROOT . '/lang/' . $locale . '/errors.php');

		AppContext::get_current_user()->update_visitor_display_name();
	}

	public static function process_changeday_tasks_if_needed()
	{
		//If the day changed compared to the last request, we execute the daily tasks
		$last_use_config = LastUseDateConfig::load();
		$last_use_date = $last_use_config->get_last_use_date();
		$current_date = new Date(Date::DATE_NOW, Timezone::SITE_TIMEZONE);
		$current_date->set_hours(0);
		$current_date->set_minutes(0);
		$current_date->set_seconds(0);

		if ($last_use_date->is_anterior_to($current_date))
		{
			$last_use_config->set_last_use_date($current_date);
			LastUseDateConfig::save();

			self::perform_changeday($current_date);
		}
	}

	private static function perform_changeday(Date $current_date)
	{
		self::clear_all_temporary_cache_files();

		self::execute_modules_changedays_tasks();

		self::update_visit_counter_table();

		self::remove_old_unactivated_member_accounts();

		self::optimize_database_tables($current_date);

		self::clear_feed_cache();

		self::check_updates();
	}

	private static function clear_all_temporary_cache_files()
	{
		//We delete all the images generated by the LaTeX formatter
		$cache_image_folder_path = new Folder(PATH_TO_ROOT . '/images/maths/');
		$files = $cache_image_folder_path->get_files('`\.png$`');
		foreach ($files as $image)
		{
			if ($image->get_last_modification_date() < self::get_one_week_ago_timestamp())
			{
				$image->delete();
			}
		}
	}

	private static function execute_modules_changedays_tasks()
	{
		$today = new Date(Date::DATE_NOW, Timezone::SITE_TIMEZONE);
		$yesterday = new Date(self::get_yesterday_timestamp(), Timezone::SERVER_TIMEZONE);
		$jobs = AppContext::get_extension_provider_service()->get_extension_point(ScheduledJobExtensionPoint::EXTENSION_POINT);
		foreach ($jobs as $job)
		{
			$job->on_changeday($yesterday, $today);
		}
	}

	private static function update_visit_counter_table()
	{
		$now = new Date(Date::DATE_NOW, Timezone::SITE_TIMEZONE);
		$time = $now->format('Y-m-d');

		//We truncate the table containing the visitors of today
		PersistenceContext::get_querier()->delete(DB_TABLE_VISIT_COUNTER, 'WHERE id <> 1');

		//We update the last changeday date
		PersistenceContext::get_querier()->update(DB_TABLE_VISIT_COUNTER, array('time' => $time, 'total' => 1), 'WHERE id = 1');

		//We insert this visitor as a today visitor
		PersistenceContext::get_querier()->insert(DB_TABLE_VISIT_COUNTER, array('ip' => AppContext::get_request()->get_ip_address(), 'time' => $time, 'total' => 0));
	}

	private static function remove_old_unactivated_member_accounts()
	{
		UserService::remove_old_unactivated_member_accounts();
	}

	private static function optimize_database_tables(Date $current_date)
	{
		if (ModulesManager::is_module_installed('database') && ModulesManager::is_module_activated('database'))
		{
			$database_config = DatabaseConfig::load();
			if ($database_config->is_database_tables_optimization_enabled())
			{
				if (($database_config->get_database_tables_optimization_day() == 7 && $current_date->get_day() == 1) || $database_config->get_database_tables_optimization_day() == $current_date->get_day_of_week())
				{
					$tables_to_optimize = array();
					foreach (PersistenceContext::get_dbms_utils()->list_and_desc_tables(true) as $key => $table_info)
					{
						if (NumberHelper::round($table_info['data_free']/1024, 1) != 0)
							$tables_to_optimize[] = $key;
					}
					PersistenceContext::get_dbms_utils()->optimize($tables_to_optimize);
				}
			}
		}
	}

	private static function clear_feed_cache()
	{
		Feed::clear_cache();
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
		$path = TextHelper::substr(REWRITED_SCRIPT, TextHelper::strlen(DIR));
		$path = trim($path, '/');

		$general_config = GeneralConfig::load();
		$other_home_page = trim($general_config->get_other_home_page(), '/');

		if ((!empty($path) && $path != 'index.php') || (!empty($other_home_page) && $path == $other_home_page))
		{
			$module_name = explode('/', $path);
			self::$running_module_name = $module_name[0];

			if ($path == $other_home_page)
				self::$home_page_running = true;
			else
				self::$home_page_running = false;
		}
		else
		{
			self::$home_page_running = true;

			$module_home_page = $general_config->get_module_home_page();

			if (empty($other_home_page) && !empty($module_home_page))
			{
				self::$running_module_name = $module_home_page;
			}
			else
				self::$running_module_name = '';
		}
	}

	/**
	 * Retrieves the identifier (name of the folder) of the module which is currently executed.
	 * @return string The module identifier.
	 */
	public static function get_running_module_name()
	{
		return self::$running_module_name;
	}

	public static function home_page_running()
	{
		return self::$home_page_running;
	}

	public static function csrf_protect_post_requests()
	{
		// Verify that the user really wanted to do this POST
		AppContext::get_session()->csrf_post_protect();
	}

	/**
	 * Redirect to update script when it is present and not installed.
	 */
	private static function redirect_to_update_script_if_needed()
	{
		$folder = new Folder(PATH_TO_ROOT . '/update');
		if ($folder->exists() && !AppContext::get_request()->get_is_localhost() && version_compare(GeneralConfig::load()->get_phpboost_major_version(), UpdateServices::NEW_KERNEL_VERSION, '<'))
		{
			self::load_dynamic_constants();
			AppContext::get_response()->redirect(new Url(HOST . DIR . '/update'));
		}
	}

	/**
	 * Retrieves the site start page.
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
	 * Returns the full phpboost version with its build number
	 * @return string the full phpboost version with its build number
	 */
	public static function get_phpboost_version()
	{
		return GeneralConfig::load()->get_phpboost_major_version() . '.' . self::get_phpboost_minor_version();
	}

	/**
	 * Returns phpboost minor version (build number)
	 * @return string the minor version
	 */
	public static function get_phpboost_minor_version()
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
