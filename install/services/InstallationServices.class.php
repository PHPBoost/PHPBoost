<?php
/*##################################################
 *                          InstallationServices.class.php
 *                            -------------------
 *   begin                : February 3, 2010
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

class InstallationServices
{
	const CONNECTION_SUCCESSFUL = 0;
	const CONNECTION_ERROR = 1;
	const UNABLE_TO_CREATE_DATABASE = 2;
	const UNKNOWN_ERROR = 3;

	private static $token_file_content = '1';
	private static $min_php_version = '5.1.2';
	private static $phpboost_major_version = '3.1';

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

	public function __construct()
	{
		$this->token = new File(PATH_TO_ROOT . '/cache/.install_token');
		$this->messages = LangLoader::get('install', 'install');
        $this->load_distribution_configuration();
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
			'dsn' => 'mysql:host=' . $host . ';port=' . $port . 'dbname=' . $database,
			'driver_options' => array(),
			'host' => $host,
			'login' => $login,
			'password' => $password,
			'database' => $database,
		);
		$db_connection = new MySQLDBConnection();
		DBFactory::init_factory($db_connection_data['dbms']);
		DBFactory::set_db_connection($db_connection);
		$db_connection->connect($db_connection_data);
	}

	private function create_database($database)
	{
		$database = PersistenceContext::get_dbms_utils()->create_database($database);
		$databases_list = PersistenceContext::get_dbms_utils()->list_databases();
		PersistenceContext::close_db_connection();
		return in_array($database, $databases_list);
	}

	public function create_phpboost_tables($dbms, $host, $port, $database, $login, $password, $tables_prefix)
	{
		$db_connection_data = $this->initialize_db_connection($dbms, $host, $port, $database, $login,
		$password, $tables_prefix);
        $this->create_tables();
		$this->write_connection_config_file($db_connection_data, $tables_prefix);
		$this->generate_installation_token();
		return true;
	}

	public function configure_website($server_url, $server_path, $site_name, $site_desc = '', $site_keyword = '', $site_timezone = '')
	{
		$this->get_installation_token();
		$modules_to_install = $this->distribution_config['modules'];
		$this->generate_website_configuration($server_url, $server_path, $site_name, $site_desc, $site_keyword, $site_timezone);
		$this->install_modules($modules_to_install);
		$this->add_menus();
		$this->generate_cache();
		return true;
	}

	public function create_admin($login, $password, $email, $create_session = true, $auto_connect = true)
	{
		$this->get_installation_token();
		$this->create_first_admin($login, $password, $email, $create_session, $auto_connect);
		$this->delete_installation_token();
		return true;
	}

    private function load_distribution_configuration()
    {
        $this->distribution_config = parse_ini_file(PATH_TO_ROOT . '/install/distribution.ini');
    }

	private function generate_website_configuration($server_url, $server_path, $site_name, $site_desc = '', $site_keyword = '', $site_timezone = '')
	{
		$locale = LangLoader::get_locale();
		$user = new AdminUser();
		$user->set_user_lang($locale);
		AppContext::set_user($user);
		$this->save_general_config($server_url, $server_path, $site_name, $site_desc, $site_keyword, $site_timezone);
		$this->init_graphical_config();
		$this->init_server_environment_config();
		$this->init_user_accounts_config($locale);
		$this->install_locale($locale);
		$this->configure_theme($this->distribution_config['theme'], $locale);
	}

	private function save_general_config($server_url, $server_path, $site_name, $site_description, $site_keywords, $site_timezone)
	{
		$general_config = GeneralConfig::load();
		$general_config->set_site_url($server_url);
		$general_config->set_site_path('/' . ltrim($server_path, '/'));
		$general_config->set_site_name($site_name);
		$general_config->set_site_description($site_description);
		$general_config->set_site_keywords($site_keywords);
		$general_config->set_home_page($this->distribution_config['start_page']);
		$general_config->set_phpboost_major_version(self::$phpboost_major_version);
		$general_config->set_site_install_date(new Date());
		$general_config->set_site_timezone((int)$site_timezone);
		GeneralConfig::save();
	}

	private function init_graphical_config()
	{
		$graphical_environment_config = GraphicalEnvironmentConfig::load();
		$graphical_environment_config->set_page_bench_enabled($this->distribution_config['bench']);
		GraphicalEnvironmentConfig::save();
	}

	private function init_server_environment_config()
	{
		$server_environment_config = ServerEnvironmentConfig::load();
		$server_environment_config->set_debug_mode_enabled($this->distribution_config['debug']);
		ServerEnvironmentConfig::save();
	}

	private function init_user_accounts_config($locale)
	{
		$user_accounts_config = UserAccountsConfig::load();
		$user_accounts_config->set_default_lang($locale);
		$user_accounts_config->set_default_theme($this->distribution_config['theme']);
		UserAccountsConfig::save();
	}

	private function install_locale($locale)
	{
		PersistenceContext::get_querier()->inject("INSERT INTO " . DB_TABLE_LANG . " (lang, activ, secure) VALUES (:config_lang, 1, -1)", array(
			'config_lang' => $locale));
	}

	private function configure_theme($theme, $locale)
	{
		$info_theme = load_ini_file(PATH_TO_ROOT . '/templates/' . $theme . '/config/', $locale);
		PersistenceContext::get_querier()->inject(
			"INSERT INTO " . DB_TABLE_THEMES . " (theme, activ, secure, left_column, right_column)
			VALUES (:theme, 1, :auth, :left_column, :right_column)", array(
                'theme' => $theme,
                'auth' => serialize(array('r-1' => 1, 'r0' => 1, 'r1' => 1)),
				'left_column' => $info_theme['left_column'],
				'right_column' => $info_theme['right_column']
		));
	}

	private function install_modules(array $modules_to_install)
	{
		foreach ($modules_to_install as $module_name)
		{
			ModulesManager::install_module($module_name, true);
		}
	}

	private function add_menus()
	{
		MenuService::enable_all(true);
		$modules_menu = MenuService::website_modules(LinksMenu::VERTICAL_MENU);
		MenuService::move($modules_menu, Menu::BLOCK_POSITION__LEFT, false);
		MenuService::change_position($modules_menu, -$modules_menu->get_block_position());
		MenuService::generate_cache();
	}

	private function generate_cache()
	{
		AppContext::get_cache_service()->clear_phpboost_cache();
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

	private function create_first_admin($login, $password, $email, $create_session, $auto_connect)
	{
		global $Cache;
		$Cache = new Cache();
		$admin_unlock_code = $this->generate_admin_unlock_code();
		$this->update_first_admin_account($login, $password, $email, LangLoader::get_locale(), $this->distribution_config['theme'], GeneralConfig::load()->get_site_timezone());
		$this->configure_mail_sender_system($email);
		$this->configure_accounts_policy();
		$this->send_installation_mail($login, $password, $email, $admin_unlock_code);
		if ($create_session)
		{
			$this->connect_admin($password, $auto_connect);
		}
		StatsCache::invalidate();
	}

	private function update_first_admin_account($login, $password, $email, $locale, $theme, $timezone)
	{
		$columns = array(
            'login' => $login,
            'password' => strhash($password),
            'level' => 2,
            'user_mail' => $email,
            'user_lang' => $locale,
            'user_theme' => $theme,
            'user_show_mail' => 1,
            'timestamp' => time(),
            'user_aprob' => 1,
            'user_timezone' => $timezone
		);
		PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, $columns, 'WHERE user_id=1');
	}

	private function generate_admin_unlock_code()
	{
		$admin_unlock_code = substr(strhash(uniqid(mt_rand(), true)), 0, 12);
		$general_config = GeneralConfig::load();
		$general_config->set_admin_unlocking_key($admin_unlock_code);
		GeneralConfig::save();
		return $admin_unlock_code;
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

	private function send_installation_mail($login, $password, $email, $unlock_admin)
	{
		$mail = new Mail();
		$mail->set_sender($email, 'admin');
		$mail->add_recipient($email);
		$mail->set_subject($this->messages['admin.created.email.object']);
		$mail->set_content(sprintf($this->messages['admin.created.email.unlockCode'], stripslashes($login),
		stripslashes($login), $password, $unlock_admin, HOST . DIR));
		AppContext::get_mail_service()->try_to_send($mail);
	}

	private function connect_admin($password, $auto_connect)
	{
		$Session = new Session();
		PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('last_connect' => time()), 'WHERE user_id=1');
		$Session->start(1, $password, 2, '/install/install.php', '', $this->messages['page_title'], $auto_connect);
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
			throw new TokenNotFoundException($this->token->get_path_from_root());
		}
	}

	private function delete_installation_token()
	{
		$this->token->delete();
	}
}
?>