<?php
/*##################################################
 *                       UpdateServices.class.php
 *                            -------------------
 *   begin                : February 29, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class UpdateServices
{
	const CONNECTION_SUCCESSFUL = 0;
	const CONNECTION_ERROR = 1;
	const UNEXISTING_DATABASE = 2;
	const UNKNOWN_ERROR = 3;
	
	// New version number
	const NEW_KERNEL_VERSION = '5.0';
	
	private static $token_file_content = '1';
	
	private static $directory = '/update/services';
	private static $configuration_pattern = '`ConfigUpdateVersion\.class\.php$`';
	private static $module_pattern = '`ModuleUpdateVersion\.class\.php$`';
	
	/**
	 * @var DBMSUtils
	 */
	private static $db_utils;
	
	/**
	 * @var DBQuerier
	 */
	private static $db_querier;
	
	/**
	 * @var Token
	 */
	private $token;
	
	/**
	 * @var File
	 */
	private $update_followed_file;
	
	/**
	 * @var string[string]
	 */
	private $messages;
	
	public function __construct($locale = '')
	{
		$this->token = new File(PATH_TO_ROOT . '/cache/.update_token');
		$this->update_followed_file = new File(PATH_TO_ROOT . '/update/update_followed.txt');
		$this->update_followed_file->delete();
		
		self::$db_utils = PersistenceContext::get_dbms_utils();
		self::$db_querier = PersistenceContext::get_querier();
		
		if (!empty($locale))
		{
			LangLoader::set_locale($locale);
		}
		
		$this->messages = LangLoader::get('update', 'update');
	}
	
	public function is_already_installed($tables_prefix)
	{
		$tables_list = self::$db_utils->list_tables();
		return in_array($tables_prefix . 'member', $tables_list) || in_array($tables_prefix . 'configs', $tables_list);
	}
	
	public function check_db_connection($host, $port, $login, $password, &$database, $tables_prefix)
	{
		try
		{
			$this->try_db_connection($host, $port, $login, $password, $database, $tables_prefix);
		}
		catch (UnexistingDatabaseException $ex)
		{
			DBFactory::reset_db_connection();
			return self::UNEXISTING_DATABASE;
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
			self::$db_utils->create_database($database);
			PersistenceContext::close_db_connection();
			$connection = DBFactory::new_db_connection();
			$connection->connect($db_connection_data);
			DBFactory::set_db_connection($connection);
		}
	}
	
	public function create_connection($dbms, $host, $port, $database, $login, $password, $tables_prefix)
	{
		$db_connection_data = $this->initialize_db_connection($dbms, $host, $port, $database, $login,
		$password, $tables_prefix);
		$this->write_connection_config_file($db_connection_data, $tables_prefix);
		$this->generate_cache();
		$this->generate_update_token();
		return true;
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
	
	public function execute()
	{
		$this->get_update_token();
		
		AppContext::get_cache_service()->clear_cache();
		
		Environment::try_to_increase_max_execution_time();
		
		// Mise en maintenance du site s'il ne l'est pas déjà
		$this->put_site_under_maintenance();
		
		// Suppression des fichiers qui ne sont plus présent dans la nouvelle version pour éviter les conflits
		$this->delete_old_files();
		
		// Désinstallation du module UrlUpdater pour éviter les problèmes
		if (ModulesManager::is_module_installed('UrlUpdater'))
			ModulesManager::uninstall_module('UrlUpdater');
		
		// Suppression du captcha PHPBoostCaptcha
		$this->delete_phpboostcaptcha();
		
		// Désinstallation des anciens menus (dans /menus)
		$this->delete_old_menus();
		
		// Mise à jour des tables du noyau
		$this->update_kernel_tables();
		
		// Mise à jour de la version du noyau
		$this->update_kernel_version();
		
		// Mise à jour des modules
		$this->update_modules();
		
		// Mise à jour des thèmes
		$this->update_themes();
		
		// Mise à jour des langues
		$this->update_langs();
		
		// installation du module UrlUpdater pour la réécriture des Url des modules mis à jour
		ModulesManager::install_module('UrlUpdater');
		
		// Fin de la mise à jour : régénération du cache
		$this->delete_update_token();
		$this->generate_cache();
		HtaccessFileCache::regenerate();
	}
	
	public function put_site_under_maintenance()
	{
		$maintenance_config = MaintenanceConfig::load();
		
		if (!$maintenance_config->is_under_maintenance())
		{
			$maintenance_config->enable_maintenance();
			$maintenance_config->set_unlimited_maintenance(true);
			MaintenanceConfig::save();
		}
	}
	
	private function delete_old_menus()
	{
		$menus_folder = new Folder(Url::to_rel('/menus'));
		if ($menus_folder->exists())
		{
			foreach ($menus_folder->get_folders() as $menu)
			{
				$menu_id = 0;
				try {
					$menu_id = self::$db_querier->get_column_value(DB_TABLE_MENUS, 'id', 'WHERE title LIKE :title', array('title' => $menu->get_name() . '%'));
				} catch (RowNotFoundException $e) {}
				
				if (!empty($menu_id))
				{
					self::$db_querier->delete(DB_TABLE_MENUS, 'WHERE id = :id', array('id' => $menu_id));
					$this->add_information_to_file('menu ' . $menu->get_name(), 'has been uninstalled because : incompatible with new version');
				}
			}
		}
	}
	
	private function delete_phpboostcaptcha()
	{
		$module_id = 'PHPBoostCaptcha';
		self::$db_querier->delete(DB_TABLE_CONFIGS, "WHERE name = :name", array('name' => $module_id));
		ModulesConfig::load()->remove_module_by_id($module_id);
		ModulesConfig::save();
		
		$folder = new Folder(Url::to_rel('/' . $module_id));
		if ($folder->exists())
			$folder->delete();
		
		$tables = self::$db_utils->list_tables(true);
		if (in_array(PREFIX . 'verif_code', $tables))
			self::$db_utils->drop(array(PREFIX . 'verif_code'));
	}
	
	private function update_kernel_tables()
	{
		// Création des nouvelles tables pour l'authentification
		$tables = self::$db_utils->list_tables(true);
		
		// Modification de la table member
		$columns = self::$db_utils->desc_table(PREFIX . 'member');
		
		if (!in_array(PREFIX . 'authentication_method', $tables) || isset($columns['login']))
		{
			self::$db_utils->drop(array(PREFIX . 'authentication_method'));
			$fields = array(
				'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
				'method' => array('type' => 'string', 'length' => 32, 'default' => "''"),
				'identifier' => array('type' => 'string', 'length' => 128, 'default' => "''"),
				'data' => array('type' => 'text', 'length' => 65000)
			);

			$options = array(
				'indexes' => array(
					'method' => array('type' => 'unique', 'fields' => array('method', 'identifier'))
			));
			self::$db_utils->create_table(PREFIX . 'authentication_method', $fields, $options);
		}
		
		if (!in_array(PREFIX . 'internal_authentication', $tables) || isset($columns['login']))
		{
			self::$db_utils->drop(array(PREFIX . 'internal_authentication'));
			$fields = array(
				'user_id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
				'login' => array('type' => 'string', 'length' => 255, 'default' => "''"),
				'password' => array('type' => 'string', 'length' => 64, 'default' => "''"),
				'registration_pass' => array('type' => 'string', 'length' => 30, 'notnull' => 1, 'default' => 0),
				'change_password_pass' => array('type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => "''"),
				'connection_attemps' => array('type' => 'boolean', 'length' => 4, 'notnull' => 1, 'default' => 0),
				'last_connection' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
				'approved' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0)
			);

			$options = array(
				'primary' => array('user_id'),
				'indexes' => array('login' => array('type' => 'unique', 'fields' => 'login'))
			);
			self::$db_utils->create_table(PREFIX . 'internal_authentication', $fields, $options);
		}
		
		if (!in_array(PREFIX . 'internal_authentication_failures', $tables) || isset($columns['login']))
		{
			self::$db_utils->drop(array(PREFIX . 'internal_authentication_failures'));
			$fields = array(
				'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
				'session_id' => array('type' => 'string', 'length' => 64, 'default' => "''"),
				'login' => array('type' => 'string', 'length' => 255, 'default' => "''"),
				'connection_attemps' => array('type' => 'boolean', 'length' => 4, 'notnull' => 1, 'default' => 0),
				'last_connection' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			);
			$options = array(
				'primary' => array('id'),
				'indexes' => array('session_id' => array('type' => 'key', 'fields' => 'session_id'))
			);
			self::$db_utils->create_table(PREFIX . 'internal_authentication_failures', $fields, $options);
		}
		
		// Insertions des mots de passe des membres actuels dans la nouvelle table
		if (isset($columns['login']))
		{
			$result = self::$db_querier->select_rows(PREFIX . 'member', array('user_id', 'login', 'password', 'approbation_pass', 'change_password_pass', 'last_connect', 'user_aprob'));
			while ($row = $result->fetch())
			{
				self::$db_querier->insert(PREFIX . 'authentication_method', array(
					'user_id' => $row['user_id'],
					'method' => PHPBoostAuthenticationMethod::AUTHENTICATION_METHOD,
					'identifier' => $row['user_id']
				));
				
				self::$db_querier->insert(PREFIX . 'internal_authentication', array(
					'user_id' => $row['user_id'],
					'login' => $row['login'],
					'password' => $row['password'],
					'registration_pass' => $row['approbation_pass'],
					'change_password_pass' => $row['change_password_pass'],
					'connection_attemps' => 0,
					'last_connection' => $row['last_connect'],
					'approved' => $row['user_aprob']
				));
			}
			$result->dispose();
		}
		
		$rows_change = array(
			'login' => 'display_name VARCHAR(255) NOT NULL DEFAULT \'\'',
			'timestamp' => 'registration_date INT(11) NOT NULL DEFAULT 0',
			'user_groups' => 'groups TEXT',
			'user_lang' => 'locale VARCHAR(25) NOT NULL DEFAULT \'\'',
			'user_theme' => 'theme VARCHAR(50) NOT NULL DEFAULT \'\'',
			'user_mail' => 'email VARCHAR(50) NOT NULL DEFAULT \'\'',
			'user_show_mail' => 'show_email INT(4) NOT NULL DEFAULT 1',
			'user_editor' => 'editor VARCHAR(15) NOT NULL DEFAULT \'\'',
			'user_timezone' => 'timezone VARCHAR(50) NOT NULL DEFAULT \'\'',
			'user_msg' => 'posted_msg INT(6) NOT NULL DEFAULT 0',
			'user_pm' => 'unread_pm INT(6) NOT NULL DEFAULT 0',
			'user_warning' => 'warning_percentage INT(6) NOT NULL DEFAULT 0',
			'user_readonly' => 'delay_readonly INT(11) NOT NULL DEFAULT 0',
			'user_ban' => 'delay_banned INT(11) NOT NULL DEFAULT 0',
			'last_connect' => 'last_connection_date INT(11) NOT NULL DEFAULT 0',
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			if (isset($columns[$old_name]))
				self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'member CHANGE ' . $old_name . ' ' . $new_name);
		}
		
		if (isset($columns['password']))
			self::$db_utils->drop_column(PREFIX . 'member', 'password');
		if (isset($columns['test_connect']))
			self::$db_utils->drop_column(PREFIX . 'member', 'test_connect');
		if (isset($columns['approbation_pass']))
			self::$db_utils->drop_column(PREFIX . 'member', 'approbation_pass');
		if (isset($columns['change_password_pass']))
			self::$db_utils->drop_column(PREFIX . 'member', 'change_password_pass');
		if (isset($columns['user_aprob']))
			self::$db_utils->drop_column(PREFIX . 'member', 'user_aprob');
		
		if (!isset($columns['autoconnect_key']))
			self::$db_utils->add_column(PREFIX . 'member', 'autoconnect_key', array('type' => 'string', 'length' => 64, 'default' => "''"));
		
		if (isset($columns['login']))
			self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'member DROP KEY `user_id`');
		if ((isset($columns['display_name']) && !$columns['display_name']['key']) || !isset($columns['display_name']))
			self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'member ADD UNIQUE KEY `display_name` (`display_name`)');
		
		if ((isset($columns['email']) && !$columns['email']['key']) || !isset($columns['email']))
		{
			$result = self::$db_querier->select('SELECT email, GROUP_CONCAT(user_id) AS user_id_list, GROUP_CONCAT(display_name) AS display_name_list FROM ' . PREFIX . 'member GROUP BY email
			HAVING COUNT(0) > 1
			ORDER BY registration_date ASC');
			
			while ($row = $result->fetch())
			{
				$user_id_list = explode(',', $row['user_id_list']);
				$ids_number = count($row['user_id_list']);
				$i = 1;
				foreach ($user_id_list as $user_id)
				{
					if ($i < $ids_number)
					{
						self::$db_querier->update(PREFIX . 'member', array('email' => $row['email'] . '_' . KeyGenerator::generate_key(5)), 'WHERE user_id=:user_id', array('user_id' => $user_id));
						$i++;
					}
				}
				
				$this->add_information_to_file('The mail address ' . $row['email'], ' is duplicate in your database (user_id : ' . $row['user_id_list'] . ') for the users ' . $row['display_name_list'] . '. The oldest accounts have been automatically modified, please tell the users to update them or deleted them if they are no more used.');
			}
			$result->dispose();
			
			try {
				self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'member ADD UNIQUE KEY `email` (`email`)');
			} catch (Exception $e) {
			}
		}
		
		// Modification des tables extended fields
		$columns = self::$db_utils->desc_table(PREFIX . 'member_extended_fields');
		
		if (!isset($columns['user_pmtomail']))
		{
			foreach ($columns as $id => $properties)
			{
				if ($id != 'user_id')
					self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'member_extended_fields CHANGE ' . $id . ' ' . $id . ' TEXT');
			}
			
			self::$db_utils->add_column(PREFIX . 'member_extended_fields', 'user_pmtomail', array('type' => 'string', 'length' => 512, 'notnull' => 1, 'default' => "''"));
			self::$db_querier->insert(PREFIX . 'member_extended_fields_list', array(
				'position' => 1,
				'name' => LangLoader::get_message('type.user_pmtomail', 'admin-user-common'),
				'field_name' => 'user_pmtomail',
				'description' => '',
				'field_type' => 'MemberUserPMToMailExtendedField',
				'possible_values' => 's:0:"";',
				'default_value' => '',
				'required' => 0,
				'display' => 0,
				'regex' => 0,
				'freeze' => 1,
				'auth' => serialize(array('r-1' => 2, 'r0' => 2, 'r1' => 3))
			));
		}
		
		// Modification de la table sessions
		$columns = self::$db_utils->desc_table(PREFIX . 'sessions');
		
		$rows_change = array(
			'session_ip' => 'ip VARCHAR(64) NOT NULL DEFAULT \'\'',
			'session_time' => 'timestamp INT(11) NOT NULL DEFAULT 0',
			'session_script' => 'location_script VARCHAR(100) NOT NULL DEFAULT \'\'',
			'session_script_title' => 'location_title VARCHAR(100) NOT NULL DEFAULT \'\'',
			'modules_parameters' => 'cached_data TEXT'
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			if (isset($columns[$old_name]))
				self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'sessions CHANGE ' . $old_name . ' ' . $new_name);
		}
		
		if (isset($columns['level']))
			self::$db_utils->drop_column(PREFIX . 'sessions', 'level');
		if (isset($columns['session_script_get']))
			self::$db_utils->drop_column(PREFIX . 'sessions', 'session_script_get');
		if (isset($columns['session_flag']))
			self::$db_utils->drop_column(PREFIX . 'sessions', 'session_flag');
		if (isset($columns['user_theme']))
			self::$db_utils->drop_column(PREFIX . 'sessions', 'user_theme');
		if (isset($columns['user_lang']))
			self::$db_utils->drop_column(PREFIX . 'sessions', 'user_lang');
		
		if (!isset($columns['data']))
			self::$db_utils->add_column(PREFIX . 'sessions', 'data', array('type' => 'text', 'length' => 65000));
		
		self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'sessions DROP KEY `user_id`');
		self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'sessions ADD KEY `user_id` (`user_id`)');
		if ((isset($columns['timestamp']) && !$columns['timestamp']['key']) || !isset($columns['timestamp']))
			self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'sessions ADD KEY `timestamp` (`timestamp`)');
	}
	
	public function update_kernel_version()
	{
		$general_config = GeneralConfig::load();
		$general_config->set_phpboost_major_version(self::NEW_KERNEL_VERSION);
		GeneralConfig::save();
	}
	
	public function update_modules()
	{
		$modules_config = ModulesConfig::load();
		foreach (ModulesManager::get_installed_modules_map() as $id => $module)
		{
			if (ModulesManager::module_is_upgradable($id))
			{
				ModulesManager::upgrade_module($id, false);
				$module->set_installed_version($module->get_configuration()->get_version());
			}
			else
			{
				if ($module->get_configuration()->get_compatibility() != self::NEW_KERNEL_VERSION)
				{
					ModulesManager::update_module($id, false, false);
					$this->add_information_to_file('module ' . $id, 'has been disabled because : incompatible with new version');
				}
			}
			
			$modules_config->update($module);
		}
		ModulesConfig::save();
		
		$configs_module_class = $this->get_class(PATH_TO_ROOT . self::$directory . '/modules/config/', self::$configuration_pattern, 'config');
		$update_module_class = $this->get_class(PATH_TO_ROOT . self::$directory . '/modules/', self::$module_pattern, 'module');
		
		$classes_list = array_merge($configs_module_class, $update_module_class);
		foreach ($classes_list as $class)
		{
			try {
				$object = new $class['name']();
				$object->execute();
				$success = true;
				$message = '';
			} catch (Exception $e) {
				$success = false;
				$message = $e->getMessage();
			}
			$this->add_error_to_file($class['type'] . ' ' . $object->get_module_id(), $success, $message);
		}
		
		AppContext::get_cache_service()->clear_cache();
		
		if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled())
		{
			HtaccessFileCache::regenerate();
		}
	}
	
	public function update_themes()
	{
		$active_themes_number = 0;
		foreach (ThemesManager::get_installed_themes_map() as $id => $theme)
		{
			if ($theme->get_configuration()->get_compatibility() == self::NEW_KERNEL_VERSION)
			{
				$active_themes_number++;
			}
			else
			{
				ThemesManager::uninstall($id);
				$this->add_information_to_file('theme ' . $id, 'has been uninstalled because : incompatible with new version');
			}
		}
		
		if (empty($active_themes_number))
		{
			ThemesManager::install('base');
			
			$user_accounts_config = UserAccountsConfig::load();
			$user_accounts_config->set_default_theme('base');
			UserAccountsConfig::save();
		}
	}
	
	public function update_langs()
	{
		$active_langs_number = 0;
		foreach (LangsManager::get_installed_langs_map() as $id => $lang)
		{
			if ($lang->get_configuration()->get_compatibility() == self::NEW_KERNEL_VERSION)
			{
				$active_langs_number++;
			}
			else
			{
				LangsManager::uninstall($id);
				$this->add_information_to_file('lang ' . $id, 'has been uninstalled because : incompatible with new version');
			}
		}
		
		if (empty($active_langs_number))
		{
			LangsManager::install('french');
			
			$user_accounts_config = UserAccountsConfig::load();
			$user_accounts_config->set_default_lang('french');
			UserAccountsConfig::save();
		}
	}
	
	private function get_class($directory, $pattern, $type)
	{
		$classes = array();
		$folder = new Folder($directory);
		foreach ($folder->get_files($pattern) as $file)
		{
			$classes[] = array(
				'name' => $file->get_name_without_extension(),
				'type' => $type
			);
		}
		return $classes;
	}
	
	private function generate_cache()
	{
		AppContext::get_cache_service()->clear_cache();
		AppContext::init_extension_provider_service();
	}
	
	private function add_error_to_file($step_name, $success, $message)
	{
		$success_message = $success ? 'Ok !' : 'Error :';
		$this->update_followed_file->append($step_name.' '.$success_message.' '. $message. "\r\n");
	}
	
	private function add_information_to_file($step_name, $message)
	{
		$this->update_followed_file->append($step_name.' '. $message. "\r\n");
	}
	
	public function generate_update_token()
	{
		$this->token->write(self::$token_file_content);
	}
	
	private function get_update_token()
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
			throw new UpdateTokenNotFoundException($this->token->get_path_from_root());
		}
	}
	
	private function delete_update_token()
	{
		$this->token->delete();
	}
	
	public static function database_config_file_checked()
	{
		if (file_exists(PATH_TO_ROOT . '/kernel/db/config.php'))
		{
			@include_once(PATH_TO_ROOT . '/kernel/db/config.php');
			
			if (defined('PREFIX') && !empty($db_connection_data))
			{
				if (!empty($db_connection_data['host']) && !empty($db_connection_data['login']) && !empty($db_connection_data['database']))
				{
					return true;
				}
			}
		}
		return false;
	}
	
	private function delete_old_files()
	{
		$this->delete_old_files_admin();
		$this->delete_old_files_kernel();
		$this->delete_old_files_lang();
		$this->delete_old_files_templates();
		$this->delete_old_files_user();
	}
	
	private function delete_old_files_admin()
	{
		$file = new File(Url::to_rel('/admin/AbstractAdminFormPageController.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/admin_files_config.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/admin_maintain.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/admin_phpinfo.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/admin_smileys.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/admin_smileys_add.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/admin_system_report.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/config/controllers/SendMailUnlockAdminController.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/controllers/AdminLoginController.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/member/controllers/AdminExtendedFieldMemberRepositionController.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/member/controllers/AdminMemberEditController.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/services/AdminLoginService.class.php'));
		$file->delete();
	}
	
	private function delete_old_files_kernel()
	{
		$file = new File(Url::to_rel('/kernel/cli/environment/AdminSession.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/cli/environment/CLISession.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/builder/form/field/constraint/FormFieldConstraintLength.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/builder/form/field/constraint/FormFieldConstraintLoginExist.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/builder/table/AbstractHTMLTableModel.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/content/DeprecatedCategoriesManager.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/content/notation/NotationDAO.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/content/notation/NotationScaleIsEmptyException.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/core/environment/DeprecatedEnvironment.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/core/error/ErrorViewBuilder.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/db/Sql.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/db/SqlParameterExtractor.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/io/image/GDNotAvailableException.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/io/image/MimeTypeNotSupportedException.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/io/optimization/cache/CSSCacheManager.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/io/template/DeprecatedTemplate.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/phpboost/member/Session.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/phpboost/member/extended-fields/MemberExtendedFieldsDAO.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/phpboost/member/extended-fields/MemberExtendedFieldsFactory.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/phpboost/menu/MenuFilter.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/phpboost/user/UserAuthentification.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/util/unusual_functions.inc.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/lib/js/top.js'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/lib/js/bottom.js'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/lib/js/phpboost/calendar.js'));
		$file->delete();
		
		$folder = new Folder(Url::to_rel('/kernel/framework/phpboost/deprecated'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/kernel/framework/phpboost/menu/mini'));
		if ($folder->exists())
			$folder->delete();
	}
	
	private function delete_old_files_lang()
	{
		$folder = new Folder(Url::to_rel('/lang/english/classes'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/lang/french/classes'));
		if ($folder->exists())
			$folder->delete();
		$file = new File(Url::to_rel('/lang/english/admin-menus-Common.php'));
		$file->delete();
		$file = new File(Url::to_rel('/lang/english/stats.php'));
		$file->delete();
		$file = new File(Url::to_rel('/lang/french/admin-menus-Common.php'));
		$file->delete();
		$file = new File(Url::to_rel('/lang/french/stats.php'));
		$file->delete();
	}
	
	private function delete_old_files_templates()
	{
		$file = new File(Url::to_rel('/templates/base/theme/images/body.png'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/admin/AdminLoginController.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/admin/admin_files_config.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/admin/admin_maintain.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/admin/admin_phpinfo.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/admin/admin_smileys_add.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/admin/admin_smileys_management.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/admin/admin_smileys_management2.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/admin/admin_system_report.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/admin/member/AdminViewAllMembersController.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/framework/builder/form/FormFieldColorPicker.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/framework/content/categories.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/framework/content/categories_select_form.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/framework/content/category.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/framework/content/syndication/images/add2netvibes.png'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/framework/content/syndication/images/addatom.png'));
		$file->delete();
		$file = new File(Url::to_rel('/templates/default/framework/content/syndication/images/addrss.png'));
		$file->delete();
		
		$folder = new Folder(Url::to_rel('/templates/default/framework/menus/content'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/templates/default/framework/menus/feed'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/templates/default/framework/menus/links'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/templates/default/framework/menus/modules_mini'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/templates/default/admin/errors'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/templates/default/images'));
		if ($folder->exists())
			$folder->delete();
	}
	
	private function delete_old_files_user()
	{
		$file = new File(Url::to_rel('/user/controllers/UserErrorCSRFController.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/user/controllers/UserMaintainController.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/user/phpboost/EventsCommentsTopic.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/user/phpboost/EventsExtensionPointProvider.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/user/services/UserLostPasswordService.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/user/templates/ErrorViewBuilder.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/user/templates/UserMaintainController.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/user/util/UserDisplayResponse.class.php'));
		$file->delete();
		
		$folder = new Folder(Url::to_rel('/user/controllers/error'));
		if ($folder->exists())
			$folder->delete();
	}
}
?>
