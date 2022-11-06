<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 11 04
 * @since       PHPBoost 3.0 - 2012 02 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UpdateServices
{
	const CONNECTION_SUCCESSFUL = 0;
	const CONNECTION_ERROR = 1;
	const UNEXISTING_DATABASE = 2;
	const UNKNOWN_ERROR = 3;

	// New version number
	const NEW_KERNEL_VERSION = '6.0';

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
	private $update_log_file;

	/**
	 * @var string[string]
	 */
	private $messages;

	public function __construct($locale = '', $delete_update_log_file = true)
	{
		$this->token = new File(PATH_TO_ROOT . '/cache/.update_token');
		$this->update_log_file = new File(PATH_TO_ROOT . '/cache/update.log');
		if ($delete_update_log_file)
			$this->update_log_file->delete();

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

		Environment::try_to_increase_max_execution_time();

		// Delete files witch are no longer present in the new version to avoid conflicts
		$this->delete_old_files();

		// Uninstalling the UrlUpdater module to avoid problems
		if (ModulesManager::is_module_installed('UrlUpdater'))
			ModulesManager::uninstall_module('UrlUpdater');

		// Maintenance of the site if it is not already
		$this->put_site_under_maintenance();

		// Updating kernel configurations
		$this->update_kernel_configurations();

		// Updating kernel tables
		$this->update_kernel_tables();

		// Updating kernel version number
		$this->update_kernel_version();

		// Updating modules
		$this->update_modules();

		// Updating modules configurations
		$this->update_modules_configurations();

		// Updating themes
		$this->update_themes();

		// Updating languages
		$this->update_langs();

		// Updating content
		$this->update_content();

		// Updating content of content menus
		// $this->update_content_menus();

		// Clear autoload
		$this->clear_autoload();

		// Installation of the UrlUpdater module for rewriting the url of updated modules
		$folder = new Folder(PATH_TO_ROOT . '/UrlUpdater');
		if ($folder->exists())
			ModulesManager::install_module('UrlUpdater');
		else
			$this->add_information_to_file('module UrlUpdater', 'has not been installed because it was not on the FTP');

		// Installing the SocialNetworks module
		$folder = new Folder(PATH_TO_ROOT . '/SocialNetworks');
		if ($folder->exists() && !ModulesManager::is_module_installed('SocialNetworks'))
			ModulesManager::install_module('SocialNetworks');

		// Verification of the site installation date and correction if necessary
		$this->check_installation_date();

		// End of update: cache refresh
		$this->delete_update_token();
		$this->generate_cache();
	}

	public function put_site_under_maintenance()
	{
		$maintenance_config = MaintenanceConfig::load();

		if (!$maintenance_config->is_under_maintenance())
		{
			try {
				$object = new MaintenanceConfigUpdateVersion();
				$object->execute();
				$success = true;
				$message = '';
			} catch (Exception $e) {
				$success = false;
				$message = $e->getMessage();
			}
			$this->add_error_to_file('enabling maintenance', $success, $message);
		}
	}

	private function update_kernel_tables()
	{
		$columns = self::$db_utils->desc_table(PREFIX . 'sessions');

		if (!isset($columns['location_id']))
			self::$db_utils->add_column(PREFIX . 'sessions', 'location_id', array('type' => 'string', 'length' => 64, 'default' => "''"));

		$columns = self::$db_utils->desc_table(PREFIX . 'member_extended_fields');

		if (!isset($columns['user_biography']))
		{
			$lang = LangLoader::get('user-lang');

			$extended_field = new ExtendedField();
			$extended_field->set_name($lang['user.extended.field.biography']);
			$extended_field->set_field_name('user_biography');
			$extended_field->set_description($lang['user.extended.field.biography.clue']);
			$extended_field->set_field_type('MemberLongTextExtendedField');
			$extended_field->set_is_required(false);
			$extended_field->set_display(false);
			$extended_field->set_is_freeze(true);
			ExtendedFieldsService::add($extended_field);
		}

		if (!isset($columns['user_website']))
		{
			$lang = LangLoader::get('user-lang');
			
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

		$columns = self::$db_utils->desc_table(PREFIX . 'upload');

		if (!isset($columns['shared']))
			self::$db_utils->add_column(PREFIX . 'upload', 'shared', array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0));

		$columns = self::$db_utils->desc_table(PREFIX . 'comments');

		if (!isset($columns['visitor_email']))
			self::$db_utils->add_column(PREFIX . 'comments', 'visitor_email', array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"));

		$columns = self::$db_utils->desc_table(PREFIX . 'comments_topic');

		if (!isset($columns['comments_number']))
			self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'comments_topic CHANGE number_comments comments_number INT(11) NOT NULL DEFAULT 0');

		$columns = self::$db_utils->desc_table(PREFIX . 'average_notes');

		if (!isset($columns['notes_number']))
			self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'average_notes CHANGE number_notes notes_number VARCHAR(255) NOT NULL DEFAULT 0');

		self::$db_querier->inject('UPDATE ' . PREFIX . 'authentication_method SET method = replace(method, \'fb\', \'facebook\')');
		self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'sessions CHANGE location_script location_script VARCHAR(200) NOT NULL DEFAULT ""');
		self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'sessions CHANGE location_title location_title VARCHAR(255) NOT NULL DEFAULT ""');

		$columns = self::$db_utils->desc_table(PREFIX . 'member');

		if (isset($columns['groups']))
			self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'member CHANGE `groups` user_groups TEXT');
	}

	private function update_kernel_version()
	{
		$general_config = GeneralConfig::load();
		$general_config->set_phpboost_major_version(self::NEW_KERNEL_VERSION);
		GeneralConfig::save();
	}

	private function update_kernel_configurations()
	{
		// Update kernel configs
		foreach ($this->get_class(PATH_TO_ROOT . self::$directory . '/kernel/config/', self::$configuration_pattern, 'config') as $class)
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
			$this->add_error_to_file($class['type'] . ' ' . $object->get_config_name(), $success, $message);
		}
	}

	private function update_modules_configurations()
	{
		// Update modules configs
		$update_modules_configs_class_list = array();

		foreach ($this->get_class(PATH_TO_ROOT . self::$directory . '/modules/config/', self::$configuration_pattern, 'config') as $class)
		{
			$object = new $class['name']();
			$update_modules_configs_class_list[$object->get_module_id()] = $class['name'];
		}

		$modules_folder = new Folder(PATH_TO_ROOT);
		foreach ($modules_folder->get_folders() as $folder)
		{
			if ($folder->get_files('/config\.ini/'))
			{
				$has_config_update_class = false;
				$module_id = $folder->get_name();
				$module = ModulesManager::get_module($module_id);
				
				$module_folder = new Folder(PATH_TO_ROOT . '/' . $module_id);
				if ($folder->get_folders('/update/'))
				{
					foreach ($this->get_class(PATH_TO_ROOT . '/' . $module_id . '/update/', self::$configuration_pattern, 'config') as $class)
					{
						$module_config_update_class = new $class['name']();
						if (ClassLoader::is_class_registered_and_valid($class['name']) && is_subclass_of($module_config_update_class, 'ConfigUpdateVersion'))
							$has_config_update_class = true;
					}
				}
				else if (in_array($module_id, array_keys($update_modules_configs_class_list)))
				{
					$module_config_update_class = new $update_modules_configs_class_list[$module_id]();
					if (ClassLoader::is_class_registered_and_valid($update_modules_configs_class_list[$module_id]) && is_subclass_of($module_config_update_class, 'ConfigUpdateVersion'))
						$has_config_update_class = true;
				}

				if (ModulesManager::is_module_installed($module_id) && $module->get_configuration()->get_compatibility() == self::NEW_KERNEL_VERSION && $has_config_update_class && $module_config_update_class)
				{
					try {
						$module_config_update_class->execute();
						$success = true;
						$message = '';
					} catch (Exception $e) {
						$success = false;
						$message = $e->getMessage();
					}
					$this->add_error_to_file($class['type'] . ' ' . $module_config_update_class->get_module_id(), $success, $message);
				}
			}
		}
	}

	private function update_modules()
	{
		$update_modules_class_list = array();

		foreach ($this->get_class(PATH_TO_ROOT . self::$directory . '/modules/', self::$module_pattern, 'module') as $class)
		{
			$object = new $class['name']();
			$update_modules_class_list[$object->get_module_id()] = $class['name'];
		}

		$modules_config = ModulesConfig::load();
		$home_page = GeneralConfig::load()->get_module_home_page();

		$default_module_changed = false;

		$modules_folder = new Folder(PATH_TO_ROOT);
		foreach ($modules_folder->get_folders() as $folder)
		{
			if ($folder->get_files('/config\.ini/'))
			{
				$has_update_class = false;
				$module_id = $folder->get_name();
				$module = ModulesManager::get_module($module_id);
				
				$module_folder = new Folder(PATH_TO_ROOT . '/' . $module_id);
				if ($folder->get_folders('/update/'))
				{
					foreach ($this->get_class(PATH_TO_ROOT . '/' . $module_id . '/update/', self::$module_pattern, 'module') as $class)
					{
						$module_update_class = new $class['name']();
						if (ClassLoader::is_class_registered_and_valid($class['name']) && is_subclass_of($module_update_class, 'ModuleUpdateVersion'))
							$has_update_class = true;
					}
				}
				else if (in_array($module_id, array_keys($update_modules_class_list)))
				{
					$module_update_class = new $update_modules_class_list[$module_id]();
					if (ClassLoader::is_class_registered_and_valid($update_modules_class_list[$module_id]) && is_subclass_of($module_update_class, 'ModuleUpdateVersion'))
						$has_update_class = true;
				}

				if (!ModulesManager::is_module_installed($module_id))
				{
					if ($has_update_class && $module_update_class)
					{
						$module_update_class::delete_old_files();
						$module_update_class::delete_old_folders();
					}
				}
				else
				{
					if ($module->get_configuration()->get_compatibility() == self::NEW_KERNEL_VERSION)
					{
						if ($has_update_class && $module_update_class)
						{
							try {
								$module_update_class->execute();
								$success = true;
								$message = '';
							} catch (Exception $e) {
								$success = false;
								$message = $e->getMessage();
							}
							$this->add_error_to_file($class['type'] . ' ' . $module_update_class->get_module_id(), $success, $message);
						}
						else
						{
							ModulesManager::upgrade_module($module_id, false);
						}
						$module->set_installed_version($module->get_configuration()->get_version());
					}
					else
					{
						if ($has_update_class && $module_update_class)
						{
							$module_update_class::delete_old_files();
							$module_update_class::delete_old_folders();
						}

						ModulesManager::update_module($module_id, false, false);
						$this->add_information_to_file('module ' . $module_id, 'has been disabled because : incompatible with new version');

						if ($home_page == $module_id)
							$default_module_changed = true;
					}

					$modules_config->update($module);
				}
			}
		}
		ModulesConfig::save();

		if ($default_module_changed)
		{
			if (ModulesManager::is_module_installed('news'))
				$new_default = 'news';
			else if (ModulesManager::is_module_installed('articles'))
				$new_default = 'articles';
			else
				$new_default = 'forum';

			$general_config = GeneralConfig::load();
			$general_config->set_module_home_page($new_default);
			$general_config->set_other_home_page('');
			GeneralConfig::save();

			$this->add_information_to_file('module ' . $new_default, 'has been set to default home page because the old one (' . $home_page . ') was incompatible');
		}
	}

	private function update_themes()
	{
		$user_accounts_config = UserAccountsConfig::load();
		$active_themes_number = 0;
		$default_theme_changed = false;
		$themes_to_delete = array();
		foreach (ThemesManager::get_installed_themes_map() as $theme)
		{
			if ($theme->get_configuration()->get_compatibility() == self::NEW_KERNEL_VERSION)
			{
				$active_themes_number++;
			}
			else
			{
				if ($user_accounts_config->get_default_theme() == $theme->get_id())
				{
					$default_theme_changed = true;
				}

				$themes_to_delete[] = $theme->get_id();
			}
		}

		if (empty($active_themes_number) || $default_theme_changed)
		{
			$folder = new Folder(PATH_TO_ROOT . '/templates/base');
			if ($folder->exists())
			{
				ThemesManager::install('base');
				ThemesManager::change_visibility('base', true);
			}
			else
				$this->add_information_to_file('theme base', 'has not been installed because it was not on the FTP');

			$user_accounts_config->set_default_theme('base');
			UserAccountsConfig::save();

			$this->add_information_to_file('theme base', 'has been installed and set to default because no other theme was compatible');
		}

		foreach ($themes_to_delete as $id)
		{
			ThemesManager::uninstall($id);
			$this->add_information_to_file('theme ' . $id, 'has been uninstalled because : incompatible with new version');
		}
	}

	private function update_langs()
	{
		$user_accounts_config = UserAccountsConfig::load();
		$active_langs_number = 0;
		$default_lang_changed = false;
		$langs_to_delete = array();
		foreach (LangsManager::get_installed_langs_map() as $lang)
		{
			if ($lang->get_configuration()->get_compatibility() == self::NEW_KERNEL_VERSION)
			{
				$active_langs_number++;
			}
			else
			{
				if ($user_accounts_config->get_default_lang() == $lang->get_id())
				{
					$default_lang_changed = true;
				}

				$langs_to_delete[] = $lang->get_id();
			}
		}

		if (empty($active_langs_number) || $default_lang_changed)
		{
			$folder = new Folder(PATH_TO_ROOT . '/lang/' . LangLoader::get_locale());
			if ($folder->exists())
			{
				LangsManager::install(LangLoader::get_locale());
				LangsManager::change_visibility(LangLoader::get_locale(), true);
			}
			else
				$this->add_information_to_file('lang ' . LangLoader::get_locale(), 'has not been installed because it was not on the FTP');

			$user_accounts_config->set_default_lang(LangLoader::get_locale());
			UserAccountsConfig::save();

			$this->add_information_to_file('lang ' . LangLoader::get_locale(), 'has been installed and set to default because no other lang was compatible');
		}

		foreach ($langs_to_delete as $id)
		{
			LangsManager::uninstall($id);
			$this->add_information_to_file('lang ' . $id, 'has been uninstalled because : incompatible with new version');
		}
	}

	public function update_content()
	{
		// Update comments messages if needed
		self::update_table_content(PREFIX . 'comments', 'message');

		// Update pm contents if needed
		self::update_table_content(PREFIX . 'pm_msg');

		$unparser = new OldBBCodeUnparser();
		$parser = new BBCodeParser();

		$user_accounts_config = UserAccountsConfig::load();

		$unparser->set_content($user_accounts_config->get_welcome_message());
		$unparser->parse();
		$parser->set_content($unparser->get_content());
		$parser->parse();

		if ($parser->get_content() != $user_accounts_config->get_welcome_message())
		{
			$user_accounts_config->set_welcome_message($parser->get_content());
			UserAccountsConfig::save();
		}

		$unparser->set_content($user_accounts_config->get_registration_agreement());
		$unparser->parse();
		$parser->set_content($unparser->get_content());
		$parser->parse();

		if ($parser->get_content() != $user_accounts_config->get_registration_agreement())
		{
			$user_accounts_config->set_registration_agreement($parser->get_content());
			UserAccountsConfig::save();
		}
	}

	public static function update_table_content($table, $contents = 'content', $id = 'id')
	{
		$columns = self::$db_utils->desc_table($table);

		if (isset($columns[$contents]))
		{
			$unparser = new OldBBCodeUnparser();
			$parser = new BBCodeParser();

			$result = self::$db_querier->select('SELECT ' . $id . ', ' . $contents . '
				FROM ' . $table . '
				WHERE (' . $contents . ' LIKE "%<a%") OR (' . $contents . ' LIKE "%class=\"message-helper\"%") OR (' . $contents . ' LIKE "%class=\"success\"%") OR (' . $contents . ' LIKE "%class=\"question\"%") OR (' . $contents . ' LIKE "%class=\"notice\"%") OR (' . $contents . ' LIKE "%class=\"warning\"%") OR (' . $contents . ' LIKE "%class=\"error\"%") OR (' . $contents . ' LIKE "%title=\"%")'
			);

			$selected_rows = $result->get_rows_count();
			$updated_content = 0;

			while($row = $result->fetch())
			{
				$unparser->set_content($row[$contents]);
				$unparser->parse();
				$parser->set_content($unparser->get_content());
				$parser->parse();

				if ($parser->get_content() != $row[$contents])
				{
					self::$db_querier->update($table, array($contents => $parser->get_content()), 'WHERE ' . $id . '=:id', array('id' => $row[$id]));
					$updated_content++;
				}
			}
			$result->dispose();

			if ($updated_content)
			{
				$object = new self('', false);
				$object->add_information_to_file('table ' . $table, ': ' . $updated_content . ' content' . ($updated_content > 1 ? 's' : '') . ' updated');
			}
		}
	}

	public static function update_content_menus()
	{
		$table = PREFIX . 'menus';
		$contents = 'object';
		$class = 'ContentMenu';
		$id = 'id';
		$columns = self::$db_utils->desc_table($table);

		if (isset($columns[$contents]))
		{
			$unparser = new OldBBCodeUnparser();
			$parser = new BBCodeParser();

			$result = self::$db_querier->select('SELECT ' . $id . ', ' . $contents . ', class
				FROM ' . $table . '
				WHERE class LIKE "%' . $class . '%"
				AND (' . $contents . ' LIKE "%<a%") OR (' . $contents . ' LIKE "%class=\"message-helper\"%") OR (' . $contents . ' LIKE "%class=\"success\"%") OR (' . $contents . ' LIKE "%class=\"question\"%") OR (' . $contents . ' LIKE "%class=\"notice\"%") OR (' . $contents . ' LIKE "%class=\"warning\"%") OR (' . $contents . ' LIKE "%class=\"error\"%") OR (' . $contents . ' LIKE "%title=\"%")'
			);

			$selected_rows = $result->get_rows_count();
			$updated_content = 0;

			while($row = $result->fetch())
			{
				$unparser->set_content($row[$contents]);
				$unparser->parse();
				$parser->set_content($unparser->get_content());
				$parser->parse();

				if ($parser->get_content() != $row[$contents])
				{
					self::$db_querier->update($table, array($contents => $parser->get_content()), 'WHERE ' . $id . '=:id', array('id' => $row[$id]));
					$updated_content++;
				}
			
				$menu = MenuService::load($row[$id]);
				MenuService::save($menu);
				$menus_list = MenuService::get_menus_map();
				// Debug::stop($menus_list);
				// MenuService::initialise(array($menus_list));
				MenuService::generate_cache();
			}
			$result->dispose();

			if ($updated_content)
			{
				$object = new self('', false);
				$object->add_information_to_file('table ' . $table, ': ' . $updated_content . ' content' . ($updated_content > 1 ? 's' : '') . ' updated');
			}
			MenuService::save($menu);
			$menus_list = MenuService::get_menus_map();
			
			MenuService::initialise($menus_list);
			MenuService::generate_cache();
		}
	}

	private function check_installation_date()
	{
		$general_config = GeneralConfig::load();
		$first_member_registration_date = '';
		try {
			$first_member_registration_date = PersistenceContext::get_querier()->get_column_value(PREFIX . 'member m', 'registration_date', 'JOIN (SELECT min(user_id) AS minid FROM ' . PREFIX . 'member) b ON m.user_id IN (b.minid)');
		} catch (RowNotFoundException $e) {}

		if ($first_member_registration_date && $first_member_registration_date < $general_config->get_site_install_date()->get_timestamp())
		{
			$general_config->set_site_install_date(new Date($first_member_registration_date, Timezone::SERVER_TIMEZONE));
			GeneralConfig::save();
		}
	}

	private function get_class($directory, $pattern, $type)
	{
		$classes = array();
		$folder = new Folder($directory);
		if ($folder->exists())
		{
			foreach ($folder->get_files($pattern) as $file)
			{
				$classes[] = array(
					'name' => $file->get_name_without_extension(),
					'type' => $type
				);
			}
		}

		return $classes;
	}

	private function generate_cache()
	{
		AppContext::get_cache_service()->clear_cache();
		AppContext::init_extension_provider_service();

		if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled())
		{
			HtaccessFileCache::regenerate();
			NginxFileCache::regenerate();
		}
	}

	private function add_error_to_file($step_name, $success, $message)
	{
		$success_message = $success ? 'Ok !' : 'Error :';
		$this->update_log_file->append($step_name . ' ' . $success_message . ' ' . $message. "\r\n");
	}

	public function add_information_to_file($step_name, $message)
	{
		$this->update_log_file->append($step_name . ' ' . $message . "\r\n");
	}

	private function clear_autoload()
	{
		ClassLoader::clear_cache();
		ClassLoader::init_autoload();
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
		catch (IOException $ioe) {}

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

			return defined('PREFIX');
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

		// Example :
		// Delete a file :
		// $file = new File(PATH_TO_ROOT . '/admin/AbstractAdminFormPageController.class.php');
		// $file->delete();
		//
		// Delete a folder :
		// $folder = new Folder(PATH_TO_ROOT . '/kernel/framework/phpboost/deprecated');
		// if ($folder->exists())
		// $folder->delete();
	}

	private function delete_old_files_admin()
	{
		$file = new File(PATH_TO_ROOT . '/database/lang/english/database_english.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/database/lang/french/database_french.php');
		$file->delete();

		$folder = new Folder(PATH_TO_ROOT . '/ReCaptcha/lib');
		if ($folder->exists())
			$folder->delete();

		$folder = new Folder(PATH_TO_ROOT . '/TinyMCE/lib');
		if ($folder->exists())
			$folder->delete();

                $folder = new Folder(PATH_TO_ROOT . '/admin/controllers');
		if ($folder->exists())
			$folder->delete();
	}

	private function delete_old_files_kernel()
	{
		$folder = new Folder(PATH_TO_ROOT . '/kernel/framework/content/newcontent');
		if ($folder->exists())
			$folder->delete();

		$folder = new Folder(PATH_TO_ROOT . '/kernel/lib/php/facebook');
		if ($folder->exists())
			$folder->delete();

		$folder = new Folder(PATH_TO_ROOT . '/kernel/lib/php/google');
		if ($folder->exists())
			$folder->delete();

		$folder = new Folder(PATH_TO_ROOT . '/kernel/lib/js/html5shiv');
		if ($folder->exists())
			$folder->delete();

		$folder = new Folder(PATH_TO_ROOT . '/kernel/lib/js/lightcase');
		if ($folder->exists())
			$folder->delete();

		$folder = new Folder(PATH_TO_ROOT . '/kernel/lib/js/phpboost');
		if ($folder->exists())
			$folder->delete();

		$folder = new Folder(PATH_TO_ROOT . '/kernel/lib/css');
		if ($folder->exists())
			$folder->delete();

		$folder = new Folder(PATH_TO_ROOT . '/kernel/lib/flash');
		if ($folder->exists())
			$folder->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/lib/js/global.js');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/lib/php/phpmailer/class.phpmailer.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/lib/php/phpmailer/class.pop3.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/lib/php/phpmailer/class.smtp.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/lib/php/phpmailer/PHPMailerAutoload.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/builder/form/fieldset/FormFieldsetHTMLHeading.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/builder/form/field/FormFieldMultipleFilePicker.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/category/CategoriesCache.class.php');
		$file->delete();
		
		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/category/controllers/AbstractCategoriesManageController.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/category/controllers/AbstractRichCategoriesFormController.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/comments/extension-point/AbstractCommentsExtensionPoint.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/notation/extension-point/AbstractNotationExtensionPoint.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/notation/extension-point/NotationExtensionPoint.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/share/AbstractShare.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/share/FacebookLikeShare.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/share/GooglePlusOneShare.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/share/ShareInterface.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/share/TwitterTweeterShare.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/phpboost/user/authentication/FacebookAuthenticationMethod.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/phpboost/user/authentication/GoogleAuthenticationMethod.class.php');
		$file->delete();
	}

	private function delete_old_files_lang()
	{
		$file = new File(PATH_TO_ROOT . '/lang/english/admin-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/admin-cache-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin-cache-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/admin-config-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin-config-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/admin-contents-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin-contents-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/admin-errors-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin-errors-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/admin-langs-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin-langs-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/admin-links-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin-links-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/admin-modules-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin-modules-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/admin-maintain-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin-maintain-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/admin-menus-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin-menus-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/admin-themes-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin-themes-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/admin-server-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin-server-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/admin-user-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin-user-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/admin.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/admin.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/categories-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/categories-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/comments-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/comments-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/date-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/date-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/editor-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/editor-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/items-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/items-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/main.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/main.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/status-messages-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/status-messages-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/upload-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/upload-common.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/lang/english/user-common.php');
		$file->delete();
		$file = new File(PATH_TO_ROOT . '/lang/french/user-common.php');
		$file->delete();
	}

	private function delete_old_files_templates()
	{
        $folder = new Folder(PATH_TO_ROOT . '/templates/default');
		if ($folder->exists())
		$folder->delete();

        $folder = new Folder(PATH_TO_ROOT . '/templates/base/images');
		if ($folder->exists())
		$folder->delete();

        $file = new File(PATH_TO_ROOT . '/templates/base/theme/colors.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/theme/content.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/theme/cssmenu.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/theme/design.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/theme/form.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/theme/global.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/theme/login.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/theme/menus.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/theme/shape.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/theme/table.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/theme/images/logo.png');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/theme/images/admin.jpg');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/theme/images/theme.jpg');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/body.tpl');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/base/frame.tpl');
		$file->delete();
	}

	private function delete_old_files_user()
	{
		$file = new File(PATH_TO_ROOT . '/user/controllers/UserMessagesController.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/user/templates/UserMessagesController.tpl');
		$file->delete();
	}
}
?>
