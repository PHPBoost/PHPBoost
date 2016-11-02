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
	const NEW_KERNEL_VERSION = '5.1';
	
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
		
		// Installation du module UrlUpdater pour la réécriture des Url des modules mis à jour
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
	
	private function update_kernel_tables()
	{
		// Mise à jour des tables du noyau - conversion en utf8
		foreach (self::$db_utils->list_tables() as $table_name)
		{
			if (!in_array($table_name, array(PREFIX . 'errors_404', PREFIX . 'stats_referer')))
			{
				self::$db_querier->inject('ALTER TABLE `' . $table_name . '` CONVERT TO CHARACTER SET utf8');
				self::$db_querier->inject('ALTER TABLE `' . $table_name . '` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci');
			}
		}
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
		// Exemple : 
		// Supprimer un fichier :
		// $file = new File(Url::to_rel('/admin/AbstractAdminFormPageController.class.php'));
		// $file->delete();
		// 
		// Supprimer un dossier :
		// $folder = new Folder(Url::to_rel('/kernel/framework/phpboost/deprecated'));
		// if ($folder->exists())
		// 	$folder->delete();
	}
	
	private function delete_old_files_kernel()
	{
		$file = new File(Url::to_rel('/kernel/lib/flash/flowplayer/flowplayer.js'));
		$file->delete();
	}
	
	private function delete_old_files_lang()
	{
		
	}
	
	private function delete_old_files_templates()
	{
		
	}
	
	private function delete_old_files_user()
	{
		
	}
}
?>
