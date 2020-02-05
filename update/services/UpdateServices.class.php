<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 05
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
	const NEW_KERNEL_VERSION = '5.2';

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
		$this->update_log_file = new File(PATH_TO_ROOT . '/update/update_log.txt');
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

		// Suppression des fichiers qui ne sont plus présent dans la nouvelle version pour éviter les conflits
		$this->delete_old_files();

		// Désinstallation du module UrlUpdater pour éviter les problèmes
		if (ModulesManager::is_module_installed('UrlUpdater'))
			ModulesManager::uninstall_module('UrlUpdater');

		if (GeneralConfig::load()->get_phpboost_major_version() != self::NEW_KERNEL_VERSION)
		{
			// Mise à jour des configurations
			$this->update_configurations();
		}

		// Mise à jour des tables du noyau
		$this->update_kernel_tables();

		// Mise en maintenance du site s'il ne l'est pas déjà
		$this->put_site_under_maintenance();

		// Mise à jour de la version du noyau
		$this->update_kernel_version();

		// Mise à jour des modules
		$this->update_modules();

		// Mise à jour des thèmes
		$this->update_themes();

		// Mise à jour des langues
		$this->update_langs();

		// Mise à jour du contenu
		$this->update_content();

		// Installation du module UrlUpdater pour la réécriture des Url des modules mis à jour
		$folder = new Folder(PATH_TO_ROOT . '/UrlUpdater');
		if ($folder->exists())
			ModulesManager::install_module('UrlUpdater');
		else
			$this->add_information_to_file('module UrlUpdater', 'has not been installed because it was not on the FTP');

		// Installation du module SocialNetworks
		$folder = new Folder(PATH_TO_ROOT . '/SocialNetworks');
		if ($folder->exists() && !ModulesManager::is_module_installed('SocialNetworks'))
			ModulesManager::install_module('SocialNetworks');

		// Vérification de la date d'installation du site et correction si besoin
		$this->check_installation_date();

		// Fin de la mise à jour : régénération du cache
		$this->delete_update_token();
		$this->generate_cache();
	}

	public function put_site_under_maintenance()
	{
		$maintenance_config = MaintenanceConfig::load();

		if (!$maintenance_config->is_under_maintenance())
		{
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
				$this->add_error_to_file('enabling maintenance', $success, $message);
			}
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
			$lang = LangLoader::get('user-common');

			$extended_field = new ExtendedField();
			$extended_field->set_name($lang['extended-field.field.biography']);
			$extended_field->set_field_name('user_biography');
			$extended_field->set_description($lang['extended-field.field.biography-explain']);
			$extended_field->set_field_type('MemberLongTextExtendedField');
			$extended_field->set_is_required(false);
			$extended_field->set_display(false);
			$extended_field->set_is_freeze(true);
			ExtendedFieldsService::add($extended_field);
		}

		$columns = self::$db_utils->desc_table(PREFIX . 'upload');

		if (!isset($columns['public']))
			self::$db_utils->add_column(PREFIX . 'upload', 'public', array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0));

		self::$db_querier->inject('UPDATE ' . PREFIX . 'authentication_method SET method = replace(method, \'fb\', \'facebook\')');
		self::$db_querier->inject('ALTER TABLE ' . PREFIX . 'sessions CHANGE location_script location_script VARCHAR(200) NOT NULL DEFAULT ""');
	}

	private function update_kernel_version()
	{
		$general_config = GeneralConfig::load();
		$general_config->set_phpboost_major_version(self::NEW_KERNEL_VERSION);
		GeneralConfig::save();
	}

	private function update_configurations()
	{
		$configs_module_class = $this->get_class(PATH_TO_ROOT . self::$directory . '/modules/config/', self::$configuration_pattern, 'config');

		foreach ($configs_module_class as $class)
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

	private function update_modules()
	{
		$update_modules_class = array();

		foreach ($this->get_class(PATH_TO_ROOT . self::$directory . '/modules/', self::$module_pattern, 'module') as $class)
		{
			$object = new $class['name']();
			$update_modules_class[$object->get_module_id()] = $class['name'];
		}

		$modules_config = ModulesConfig::load();
		$home_page = GeneralConfig::load()->get_module_home_page();

		$default_module_changed = false;

		foreach (ModulesManager::get_installed_modules_map() as $id => $module)
		{
			if ($module->get_configuration()->get_compatibility() == self::NEW_KERNEL_VERSION)
			{
				if (in_array($id, array_keys($update_modules_class)))
				{
					$object = new $update_modules_class[$id]();
					try {
						$object->execute();
						$success = true;
						$message = '';
					} catch (Exception $e) {
						$success = false;
						$message = $e->getMessage();
					}
					$this->add_error_to_file($class['type'] . ' ' . $object->get_module_id(), $success, $message);
				}
				else
				{
					ModulesManager::upgrade_module($id, false);
				}
				$module->set_installed_version($module->get_configuration()->get_version());
			}
			else
			{
				ModulesManager::update_module($id, false, false);
				$this->add_information_to_file('module ' . $id, 'has been disabled because : incompatible with new version');

				if ($home_page == $id)
					$default_module_changed = true;
			}

			$modules_config->update($module);
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

	public static function update_table_content($table, $contents = 'contents', $id = 'id')
	{
		$unparser = new OldBBCodeUnparser();
		$parser = new BBCodeParser();

		$result = self::$db_querier->select('SELECT ' . $id . ', ' . $contents . '
			FROM ' . $table . '
			WHERE (' . $contents . ' LIKE "%class=\"success\"%") OR (' . $contents . ' LIKE "%class=\"question\"%") OR (' . $contents . ' LIKE "%class=\"notice\"%") OR (' . $contents . ' LIKE "%class=\"warning\"%") OR (' . $contents . ' LIKE "%class=\"error\"%")'
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

		$object = new self('', false);
		$object->add_information_to_file('table ' . $table, ': ' . $updated_content . ' contents updated');
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

			if (defined('PREFIX'))
			{
				return true;
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

		$folder = new Folder(PATH_TO_ROOT . '/kernel/lib/js/lightcase');
		if ($folder->exists())
			$folder->delete();

		$folder = new Folder(PATH_TO_ROOT . '/kernel/lib/js/phpboost');
		if ($folder->exists())
			$folder->delete();

		$folder = new Folder(PATH_TO_ROOT . '/kernel/lib/css/font-awesome');
		if ($folder->exists())
			$folder->delete();

		$folder = new Folder(PATH_TO_ROOT . '/kernel/lib/css/font-awesome-animation');
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

		$file = new File(PATH_TO_ROOT . '/kernel/framework/builder/form/field/FormFieldMultipleFilePicker.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/category/controllers/AbstractCategoriesManageController.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/category/controllers/AbstractRichCategoriesFormController.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/kernel/framework/content/category/CategoriesCache.class.php');
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

	}

	private function delete_old_files_templates()
	{
		$file = new File(PATH_TO_ROOT . '/templates/default/framework/builder/form/FormFieldMultipleFilePicker.tpl');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/default/theme/admin_colors.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/default/theme/admin_content.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/default/theme/admin_cssmenu.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/default/theme/admin_design.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/default/theme/admin_form.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/default/theme/admin_global.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/default/theme/admin_menus.css');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/templates/default/theme/admin_table.css');
		$file->delete();

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
	}

	private function delete_old_files_user()
	{

	}
}
?>
