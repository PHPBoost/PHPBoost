<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 24
 * @since       PHPBoost 3.0 - 2010 02 03
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class InstallationServices
{
	const CONNECTION_SUCCESSFUL = 0;
	const CONNECTION_ERROR = 1;
	const UNABLE_TO_CREATE_DATABASE = 2;
	const UNKNOWN_ERROR = 3;

	private static $token_file_content = '1';

	/**
	 * @var File
	 */
	private $token;

	/**
	 * @var string[string]
	 */
	private $messages;

	/**
	 * @var mixed[string] Distribution configuration
	 */
	private $distribution_config;

	public function __construct($locale = '')
	{
		$this->token = new File(PATH_TO_ROOT . '/cache/.install_token');
		$this->load_distribution_configuration();
		if ($locale)
		{
			$this->set_default_locale($locale);
			LangLoader::set_locale($this->distribution_config['default_lang']);
		}
		$this->messages = LangLoader::get_all_langs('install');
	}

	public static function get_available_langs()
	{
		$langs_folder = new Folder(PATH_TO_ROOT . '/lang');
		$langs_list = $langs_folder->get_folders();

		$available_langs = array();
		foreach ($langs_list as $lang)
		{
			$available_langs[] = $lang->get_name();
		}

		return $available_langs;
	}

	private function set_default_locale($locale)
	{
		$langs = $this->get_available_langs();
		if (!empty($locale) && in_array($locale, $langs))
			$this->distribution_config['default_lang'] = $locale;
	}

	public static function get_default_lang()
	{
		$browser_lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)) : '';
		$browser_lang = !$browser_lang && isset($_SERVER['HTTP_X_COUNTRY_CODE']) ? strtolower($_SERVER['HTTP_X_COUNTRY_CODE']) : $browser_lang;
		$browser_lang = !$browser_lang ? strtolower(AppContext::get_request()->get_location_info_by_ip()) : $browser_lang;
		$distribution_config = parse_ini_file(PATH_TO_ROOT . '/install/distribution.ini');
		$langs = self::get_available_langs();
		
		if ($browser_lang)
		{
			foreach ($langs as $lang)
			{
				$lang_config = parse_ini_file(PATH_TO_ROOT . '/lang/' . $lang . '/config.ini');
				if (($lang == 'english' && $browser_lang == 'en') || (isset($lang_config['identifier']) && $lang_config['identifier'] == $browser_lang))
					$distribution_config['default_lang'] = $lang;
			}
		}
		else if (!in_array($distribution_config['default_lang'], $langs))
			$distribution_config['default_lang'] = $langs[0];
		return $distribution_config['default_lang'];
	}

	public function is_already_installed()
	{
		$tables_list = PersistenceContext::get_dbms_utils()->list_tables();
		return in_array(PREFIX . 'member', $tables_list) || in_array(PREFIX . 'configs', $tables_list);
	}

	public function check_db_connection($host, $port, $login, $password, &$database, $tables_prefix)
	{
		try
		{
			$this->try_db_connection($host, $port, $login, $password, $database, $tables_prefix);
		}
		catch (UnexistingDatabaseException $ex)
		{
			if (!$this->create_database($database))
			{
				DBFactory::reset_db_connection();
				return self::UNABLE_TO_CREATE_DATABASE;
			}
			else
			{
				return $this->check_db_connection($host, $port, $login, $password, $database, $tables_prefix);
			}
		}
		catch (DBConnectionException $ex)
		{
			DBFactory::reset_db_connection();
			return self::CONNECTION_ERROR;
		}
		catch (Exception $ex)
		{
			$exception_handler = new ExceptionHandler();
			$exception_handler->handle($ex);
			DBFactory::reset_db_connection();
			return self::UNKNOWN_ERROR;
		}
		return self::CONNECTION_SUCCESSFUL;
	}

	private function try_db_connection($host, $port, $login, $password, $database, $tables_prefix)
	{
		defined('PREFIX') or define('PREFIX', $tables_prefix);
		$db_connection_data = array(
			'dbms' => DBFactory::MYSQL,
			'dsn' => 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database,
			'driver_options' => array(),
			'host' => $host,
			'login' => $login,
			'password' => $password,
			'database' => $database,
			'port' => $port
		);
		$db_connection = new MySQLDBConnection();
		DBFactory::init_factory($db_connection_data['dbms']);
		DBFactory::set_db_connection($db_connection);
		$db_connection->connect($db_connection_data);
	}

	private function create_database($database)
	{
		try {
			$database = str_replace(array('/', '\\', '.', ' ', '"', '\''), '_', $database);
			$database = PersistenceContext::get_dbms_utils()->create_database($database);
			$databases_list = PersistenceContext::get_dbms_utils()->list_databases();
			PersistenceContext::close_db_connection();
			return in_array($database, $databases_list);
		} catch (SQLQuerierException $e) {
			return false;
		}
	}

	public function create_phpboost_tables($dbms, $host, $port, $database, $login, $password, $tables_prefix)
	{
		$db_connection_data = $this->initialize_db_connection($dbms, $host, $port, $database, $login, $password, $tables_prefix);
		$this->create_tables();
		$this->write_connection_config_file($db_connection_data, $tables_prefix);
		$this->generate_installation_token();
		$this->regenerate_cache();
		return true;
	}

	public function configure_website($server_url, $server_path, $site_name, $site_slogan = '', $site_desc = '', $site_timezone = '')
	{
		$this->get_installation_token();
		$this->generate_website_configuration($server_url, $server_path, $site_name, $site_slogan, $site_desc, $site_timezone);
		$this->install_modules($this->get_modules_not_installed());
		$this->install_themes($this->get_themes_not_installed());
		$this->install_langs($this->get_langs_not_installed());
		$this->add_menus();
		$this->add_extended_fields();
		return true;
	}

	public function create_admin($display_name, $login, $password, $email, $create_session = true, $auto_connect = true)
	{
		$this->get_installation_token();
		$this->create_first_admin($display_name, $login, $password, $email, $create_session, $auto_connect);
		$this->delete_installation_token();
		return true;
	}

	private function load_distribution_configuration()
	{
		$this->distribution_config = parse_ini_file(PATH_TO_ROOT . '/install/distribution.ini');
	}

	private function generate_website_configuration($server_url, $server_path, $site_name, $site_slogan = '', $site_desc = '', $site_timezone = '')
	{
		$locale = LangLoader::get_locale();
		$user = new AdminUser();
		$user->set_locale($locale);
		AppContext::set_current_user($user);
		$this->save_general_config($server_url, $server_path, $site_name, $site_slogan, $site_desc, $site_timezone);
		$this->configure_default_editor();
		$this->configure_default_captcha();
		$this->save_server_environnement_config();
		$this->init_graphical_config();
		$this->init_debug_mode();
		$this->init_user_accounts_config($locale);
	}

	private function save_general_config($server_url, $server_path, $site_name, $site_slogan, $site_description, $site_timezone)
	{
		$general_config = GeneralConfig::load();
		$general_config->set_site_url($server_url);
		$general_config->set_site_path('/' . ltrim($server_path, '/'));
		$general_config->set_site_name($site_name);
		$general_config->set_site_slogan($site_slogan);
		$general_config->set_site_description($site_description);
		$general_config->set_module_home_page($this->distribution_config['module_home_page']);
		$general_config->set_site_install_date(new Date());
		$general_config->set_site_timezone($site_timezone);
		GeneralConfig::save();
	}

	public function save_server_environnement_config()
	{
		$server_configuration = new ServerConfiguration();
		$server_environment_config = ServerEnvironmentConfig::load();

		try
		{
			if ($server_configuration->has_url_rewriting())
			{
				$server_environment_config->set_url_rewriting_enabled(true);
			}
		}
		catch (UnsupportedOperationException $ex)
		{
			$server_environment_config->set_url_rewriting_enabled(false);
		}

		if (function_exists('ob_gzhandler') && @extension_loaded('zlib'))
		{
			$server_environment_config->set_output_gziping_enabled(true);
		}

		if (DataStoreFactory::is_apc_available())
		{
			DataStoreFactory::set_apc_enabled(true);
		}

		ServerEnvironmentConfig::save();
	}

	private function init_graphical_config()
	{
		$graphical_environment_config = GraphicalEnvironmentConfig::load();
		$graphical_environment_config->set_page_bench_enabled($this->distribution_config['bench']);
		GraphicalEnvironmentConfig::save();
	}

	private function init_debug_mode()
	{
		if ($this->distribution_config['debug'])
		{
			Debug::enabled_debug_mode();
		}
		else
		{
			Debug::disable_debug_mode();
		}
	}

	private function init_user_accounts_config($locale)
	{
		$user_accounts_config = UserAccountsConfig::load();
		$user_accounts_config->set_default_lang($locale);
		$user_accounts_config->set_default_theme($this->distribution_config['default_theme']);
		UserAccountsConfig::save();
	}

	private function get_modules_not_installed()
	{
		$modules_not_installed = array();
		$modules_folder = new Folder(PATH_TO_ROOT);
		foreach ($modules_folder->get_folders() as $folder)
		{
			$folder_name = $folder->get_name();
			if (!in_array($folder_name, array('admin', 'cache', 'images', 'install', 'kernel', 'lang', 'syndication', 'templates', 'update', 'upload', 'user')) && $folder->get_files('/config\.ini/') && !ModulesManager::is_module_installed($folder_name))
			{
				try
				{
					$modules_not_installed[$folder_name] = new Module($folder_name);
				}
				catch (IOException $ex)
				{
					continue;
				}
			}
		}

		@uasort($modules_not_installed, array(__CLASS__, 'callback_sort_modules_by_name'));

		return $modules_not_installed;
	}

	private static function callback_sort_modules_by_name(Module $module1, Module $module2)
	{
		if (TextHelper::strtolower($module1->get_configuration()->get_name()) > TextHelper::strtolower($module2->get_configuration()->get_name()))
		{
			return 1;
		}
		return -1;
	}

	private function install_modules(array $modules_to_install)
	{
		foreach ($modules_to_install as $module_id => $module)
		{
			ModulesManager::install_module($module_id, true, false);
		}

		if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled())
		{
			HtaccessFileCache::regenerate();
			NginxFileCache::regenerate();
		}
	}

	private function get_themes_not_installed()
	{
		$themes_not_installed = array();
		$folder_containing_phpboost_themes = new Folder(PATH_TO_ROOT .'/templates/');
		foreach ($folder_containing_phpboost_themes->get_folders() as $folder)
		{
			$folder_name = $folder->get_name();
			if ($folder_name != '__default__' && $folder->get_files('/config\.ini/') && !ThemesManager::get_theme_existed($folder_name))
			{
				try
				{
					$themes_not_installed[$folder_name] = new Theme($folder_name);
				}
				catch (IOException $ex)
				{
					continue;
				}
			}
		}

		@uasort($themes_not_installed, array(__CLASS__, 'callback_sort_themes_by_name'));

		return $themes_not_installed;
	}

	private static function callback_sort_themes_by_name(Theme $theme1, Theme $theme2)
	{
		if (TextHelper::strtolower($theme1->get_configuration()->get_name()) > TextHelper::strtolower($theme2->get_configuration()->get_name()))
		{
			return 1;
		}
		return -1;
	}

	private function install_themes(array $themes_to_install)
	{
		foreach ($themes_to_install as $theme)
		{
			ThemesManager::install($theme->get_id());
		}
	}

	private function get_langs_not_installed()
	{
		$langs_not_installed = array();
		$folder_containing_phpboost_langs = new Folder(PATH_TO_ROOT .'/lang/');
		foreach($folder_containing_phpboost_langs->get_folders() as $folder)
		{
			$folder_name = $folder->get_name();
			if ($folder->get_files('/config\.ini/') && !LangsManager::get_lang_existed($folder_name))
			{
				try
				{
					$langs_not_installed[$folder_name] = new Lang($folder_name);
				}
				catch (IOException $ex)
				{
					continue;
				}
			}
		}

		@uasort($langs_not_installed, array(__CLASS__, 'callback_sort_langs_by_name'));

		return $langs_not_installed;
	}

	private static function callback_sort_langs_by_name(Lang $lang1, Lang $lang2)
	{
		if (TextHelper::strtolower($lang1->get_configuration()->get_name()) > TextHelper::strtolower($lang2->get_configuration()->get_name()))
		{
			return 1;
		}
		return -1;
	}

	private function install_langs(array $langs_to_install)
	{
		foreach ($langs_to_install as $lang)
		{
			LangsManager::install($lang->get_id());
		}
	}

	private function configure_default_editor()
	{
		if (isset($this->distribution_config['default_editor']))
		{
			$content_formatting_config = ContentFormattingConfig::load();
			$content_formatting_config->set_default_editor($this->distribution_config['default_editor']);
			ContentFormattingConfig::save();
		}
	}

	private function configure_default_captcha()
	{
		if (isset($this->distribution_config['default_captcha']))
		{
			$content_management_config = ContentManagementConfig::load();
			$content_management_config->set_used_captcha_module($this->distribution_config['default_captcha']);
			ContentManagementConfig::save();
		}
	}

	private function add_menus()
	{
		MenuService::enable_all(true);
		$modules_menu = MenuService::website_modules();
		MenuService::move($modules_menu, Menu::BLOCK_POSITION__LEFT, false);
		MenuService::set_position($modules_menu, -$modules_menu->get_block_position());
	}

	private function add_extended_fields()
	{
		$lang = LangLoader::get('user-lang');

		// Sex
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['user.extended.field.sex']);
		$extended_field->set_field_name('user_sex');
		$extended_field->set_description($lang['user.extended.field.sex.clue']);
		$extended_field->set_field_type('MemberUserSexExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(false);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);

		// Biography
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['user.extended.field.biography']);
		$extended_field->set_field_name('user_biography');
		$extended_field->set_description($lang['user.extended.field.biography.clue']);
		$extended_field->set_field_type('MemberLongTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(false);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);

		// Mail notofication when receiving PM
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['user.extended.field.pm.to.mail']);
		$extended_field->set_field_name('user_pmtomail');
		$extended_field->set_description($lang['user.extended.field.pm.to.mail.clue']);
		$extended_field->set_field_type('MemberUserPMToMailExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(false);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);

		// Birth Date
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['user.extended.field.birth.date']);
		$extended_field->set_field_name('user_born');
		$extended_field->set_description($lang['user.extended.field.birth.date.clue']);
		$extended_field->set_field_type('MemberUserBornExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(false);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);

		// Avatar
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['user.extended.field.avatar']);
		$extended_field->set_field_name('user_avatar');
		$extended_field->set_description($lang['user.extended.field.avatar.clue']);
		$extended_field->set_field_type('MemberUserAvatarExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);

		// Website
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['user.extended.field.website']);
		$extended_field->set_field_name('user_website');
		$extended_field->set_description($lang['user.extended.field.website.clue']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		$extended_field->set_regex(5);
		ExtendedFieldsService::add($extended_field);
	}

	public function regenerate_cache()
	{
		AppContext::get_cache_service()->clear_cache();
	}

	private function initialize_db_connection($dbms, $host, $port, $database, $login, $password, $tables_prefix)
	{
		defined('PREFIX') or define('PREFIX', $tables_prefix);
		$db_connection_data = array(
			'dbms' => $dbms,
			'dsn' => 'mysql:host=' . $host . ';port=' . $port . 'dbname=' . $database,
			'driver_options' => array(),
			'host' => $host,
			'port' => $port,
			'login' => $login,
			'password' => $password,
			'database' => $database,
		);
		$this->connect_to_database($dbms, $db_connection_data, $database);
		return $db_connection_data;
	}

	private function connect_to_database($dbms, array $db_connection_data, $database)
	{
		DBFactory::init_factory($dbms);
		$connection = DBFactory::new_db_connection();
		DBFactory::set_db_connection($connection);
		try
		{
			$connection->connect($db_connection_data);
		}
		catch (UnexistingDatabaseException $exception)
		{
			PersistenceContext::get_dbms_utils()->create_database($database);
			PersistenceContext::close_db_connection();
			$connection = DBFactory::new_db_connection();
			$connection->connect($db_connection_data);
			DBFactory::set_db_connection($connection);
		}
	}

	private function create_tables()
	{
		$kernel = new KernelSetup();
		$kernel->install();
	}

	private function write_connection_config_file(array $db_connection_data, $tables_prefix)
	{
		$db_config_content = '<?php' . "\n" .
			'$db_connection_data = ' . var_export($db_connection_data, true) . ";\n\n" .
			'defined(\'PREFIX\') or define(\'PREFIX\' , \'' . $tables_prefix . '\');'. "\n" .
			'defined(\'PHPBOOST_INSTALLED\') or define(\'PHPBOOST_INSTALLED\', true);' . "\n" .
			'require_once PATH_TO_ROOT . \'/kernel/db/tables.php\';' . "\n" .
		'?>';

		$db_config_file = new File(PATH_TO_ROOT . '/kernel/db/config.php');
		$db_config_file->write($db_config_content);
		$db_config_file->close();
	}

	private function create_first_admin($display_name, $login, $password, $email, $create_session, $auto_connect)
	{
		$user_id = $this->create_first_admin_account($display_name, $login, $password, $email, LangLoader::get_locale(), $this->distribution_config['default_theme'], GeneralConfig::load()->get_site_timezone());
		$this->configure_mail_sender_system($email);
		$this->configure_accounts_policy();
		$this->send_installation_mail($display_name, $password, $email);
		if ($create_session)
		{
			$this->connect_admin($user_id, $auto_connect);
		}
	}

	private function create_first_admin_account($display_name, $login, $password, $email, $locale, $theme, $timezone)
	{
		$user = new User();
		$user->set_display_name($display_name);
		$user->set_level(User::ADMINISTRATOR_LEVEL);
		$user->set_email($email);
		$user->set_locale($locale);
		$user->set_theme($theme);
		$auth_method = new PHPBoostAuthenticationMethod($login, $password);
		return UserService::create($user, $auth_method);
	}

	private function configure_mail_sender_system($administrator_email)
	{
		$mail_config = MailServiceConfig::load();
		$mail_config->set_administrators_mails(array($administrator_email));
		$mail_config->set_default_mail_sender($administrator_email);
		MailServiceConfig::save();
	}

	private function configure_accounts_policy()
	{
		$user_account_config = UserAccountsConfig::load();
		$user_account_config->set_registration_enabled($this->distribution_config['allow_members_registration']);
		UserAccountsConfig::save();
	}

	private function send_installation_mail($login, $password, $email)
	{
		$general_config = GeneralConfig::load();
		AppContext::get_mail_service()->send_from_properties($email, $this->messages['install.admin.created.email.object'], sprintf($this->messages['install.admin.created.email.unlock.code'], stripslashes($login),
		stripslashes($login), UserUrlBuilder::forget_password()->absolute(), $general_config->get_site_url() . $general_config->get_site_path()));
	}

	private function connect_admin($user_id, $auto_connect)
	{
		$session = Session::create($user_id, $auto_connect);
		AppContext::set_session($session);
	}

	private function generate_installation_token()
	{
		$this->token->write(self::$token_file_content);
	}

	private function get_installation_token()
	{
		$is_token_valid = false;
		try
		{
			$is_token_valid = $this->token->exists() && $this->token->read() == self::$token_file_content;
		}
		catch (IOException $ioe)
		{
			$is_token_valid = false;
		}

		if (!$is_token_valid)
		{
			throw new InstallTokenNotFoundException($this->token->get_path_from_root());
		}
	}

	private function delete_installation_token()
	{
		$this->token->delete();
	}
}
?>
