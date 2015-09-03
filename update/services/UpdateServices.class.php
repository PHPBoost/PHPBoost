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
	
	private static $token_file_content = '1';
	
	private static $directory = '/update/services';
	private static $configuration_pattern = '`ConfigUpdateVersion\.class\.php$`';
	private static $module_pattern = '`ModuleUpdateVersion\.class\.php$`';
	
	private static $new_kernel_version = '4.2';
	
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
		
		// TODO : gérer les anciens menus + loguer leur nom dans update followed
		
		// Suppression du captcha PHPBoostCaptcha
		ModulesManager::uninstall_module('PHPBoostCaptcha', true);
		
		$content_management_config = ContentManagementConfig::load();
		if ($content_management_config->get_used_captcha_module() == 'PHPBoostCaptcha')
		{
			$content_management_config->set_used_captcha_module('ReCaptcha');
			ContentManagementConfig::save();
		}
		
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
	
	private function update_kernel_tables()
	{
		// Création des nouvelles tables pour l'authentification
		$tables = self::$db_utils->list_tables(true);
		
		if (!in_array(PREFIX . 'authentication_method', $tables))
		{
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
		
		if (!in_array(PREFIX . 'internal_authentication', $tables))
		{
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
		
		// Insertions des mots de passe des membres actuels dans la nouvelle table
		$result = $this->querier->select_rows(PREFIX . 'member', array('user_id', 'login', 'password', 'approbation_pass', 'change_password_pass', 'last_connect', 'user_aprob'));
		while ($row = $result->fetch())
		{
			$this->querier->insert(PREFIX . 'authentication_method', array(
				'user_id' => $row['user_id'],
				'method' => PHPBoostAuthenticationMethod::AUTHENTICATION_METHOD,
				'identifier' => $row['user_id']
			));
			
			$this->querier->insert(PREFIX . 'internal_authentication', array(
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
		
		// Modification de la table member
		$columns = self::$db_utils->desc_table(PREFIX . 'member');
		
		$rows_change = array(
			'login' => 'display_name VARCHAR(255)',
			'timestamp' => 'registration_date INT(11)',
			'user_groups' => 'groups TEXT',
			'user_lang' => 'locale VARCHAR(25)',
			'user_theme' => 'theme VARCHAR(50)',
			'user_mail' => 'email VARCHAR(50)',
			'user_show_mail' => 'show_email INT(4)',
			'user_editor' => 'editor VARCHAR(15)',
			'user_timezone' => 'timezone VARCHAR(50)',
			'user_msg' => 'posted_msg INT(6)',
			'user_pm' => 'unread_pm INT(6)',
			'user_warning' => 'warning_percentage INT(6)',
			'user_readonly' => 'delay_readonly INT(11)',
			'user_ban' => 'delay_banned INT(11)',
			'last_connect' => 'last_connection_date INT(11)',
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
		
		self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'member DROP UNIQUE KEY `login`');
		self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'member DROP KEY `user_id`');
		self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'member ADD UNIQUE KEY `display_name` (`display_name`)');
		self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'member ADD UNIQUE KEY `email` (`email`)');
		
		// Modification de la table sessions
		$columns = self::$db_utils->desc_table(PREFIX . 'sessions');
		
		$rows_change = array(
			'session_ip' => 'ip VARCHAR(64)',
			'session_time' => 'timestamp INT(11)',
			'session_script' => 'location_script VARCHAR(100)',
			'session_script_title' => 'location_title VARCHAR(100)',
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
		self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'sessions ADD KEY `timestamp` (`timestamp`)');
	}
	
	public function update_kernel_version()
	{
		$general_config = GeneralConfig::load();
		$general_config->set_phpboost_major_version(self::$new_kernel_version);
		GeneralConfig::save();
	}
	
	// TODO : Update configurations (pour modules forum et media, faire comme pour la 4.0)
	
	
	public function update_configurations()
	{
		$configs_kernel_class = $this->get_class(PATH_TO_ROOT . self::$directory . '/kernel/config/', self::$configuration_pattern);
		
		$configs_class = array_merge($configs_module_class, $configs_kernel_class);
		foreach ($configs_class as $class_name)
		{
			try {
				$object = new $class_name();
				$object->execute();
				$success = true;
				$message = '';
			} catch (Exception $e) {
				$success = false;
				$message = $e->getMessage();
			}
			$this->add_error_to_file($object->get_config_name() . '_config', $success, $message);
		}
	}
	public function update_modules()
	{
		$modules_config = ModulesConfig::load();
		foreach (ModulesManager::get_installed_modules_map() as $id => $module)
		{
			if (ModulesManager::module_is_upgradable($id))
				$module->set_installed_version($module->get_configuration()->get_version());
			else
			{
				$module->set_activated(false);
				$this->add_information_to_file('module ' . $id, 'has been disabled because : incompatible with new version');
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
	}
	
	public function update_themes()
	{
		$themes_config = ThemesConfig::load();
		$active_themes_number = 0;
		foreach (ThemesManager::get_installed_themes_map() as $id => $theme)
		{
			if ($theme->get_configuration()->get_compatibility() == self::$new_kernel_version)
			{
				$theme->set_installed_version($theme->get_configuration()->get_version());
				$active_themes_number++;
			}
			else
			{
				ThemesManager::uninstall($id);
				$this->add_information_to_file('theme ' . $id, 'has been uninstalled because : incompatible with new version');
			}
			
			$themes_config->update($theme);
		}
		ThemesConfig::save();
		
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
		$langs_config = LangsConfig::load();
		$active_langs_number = 0;
		foreach (LangsManager::get_installed_langs_map() as $id => $lang)
		{
			if ($lang->get_configuration()->get_compatibility() == self::$new_kernel_version)
			{
				$lang->set_installed_version($lang->get_configuration()->get_version());
				$active_langs_number++;
			}
			else
			{
				LangsManager::uninstall($id);
				$this->add_information_to_file('lang ' . $id, 'has been uninstalled because : incompatible with new version');
			}
			
			$langs_config->update($lang);
		}
		LangsConfig::save();
		
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
			throw new TokenNotFoundException($this->token->get_path_from_root());
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
		$file = new File(Url::to_rel('/lang/english/stats.php'));
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
		$file = new File(Url::to_rel('/templates/default/images/color.png'));
		$file->delete();
		
		$folder = new Folder(Url::to_rel('/templates/default/admin/errors'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/templates/default/images/lightbox'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/templates/default/images/upload'));
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
		$file = new File(Url::to_rel('/user/templates/UserMaintainController.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/user/util/UserDisplayResponse.class.php'));
		$file->delete();
	}
}
?>