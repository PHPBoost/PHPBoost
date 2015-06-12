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
	
	/**
	 * @var File
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
		if (!empty($locale))
		{
			LangLoader::set_locale($locale);
		}
		$this->messages = LangLoader::get('update', 'update');
	}
	
	public function is_already_installed($tables_prefix)
	{
		$tables_list = PersistenceContext::get_dbms_utils()->list_tables();
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
			PersistenceContext::get_dbms_utils()->create_database($database);
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
		
		$this->put_site_under_maintenance();
		
		$this->delete_old_files();
		
		// TODO : gérer les anciens menus
		
		//On désinstalle les thèmes
		foreach (ThemesManager::get_installed_themes_map() as $id => $theme)
		{
			ThemesManager::uninstall($id);
		}
		
		ThemesManager::install('base');
		
		$user_accounts_config = UserAccountsConfig::load();
		$user_accounts_config->set_default_theme('base');
		UserAccountsConfig::save();
		
		// Suppression du captcha PHPBoostCaptcha
		ModulesManager::uninstall_module('PHPBoostCaptcha', true)
		
		$content_management_config = ContentManagementConfig::load();
		if ($content_management_config->get_used_captcha_module() == 'PHPBoostCaptcha')
		{
			$content_management_config->set_used_captcha_module('ReCaptcha');
			ContentManagementConfig::save();
		}

		$tables = PersistenceContext::get_dbms_utils()->list_tables(true);
		
		if (!in_array(PREFIX . 'keywords', $tables))
		{
			$fields = array(
				'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
				'name' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"),
				'rewrited_name' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			);
			$options = array(
				'primary' => array('id'),
				'indexes' => array(
					'name' => array('type' => 'unique', 'fields' => 'name',
					'rewrited_name' => array('type' => 'unique', 'fields' => 'rewrited_name')
			)));
			PersistenceContext::get_dbms_utils()->create_table(PREFIX . 'keywords', $fields, $options);
		}
		
		if (!in_array(PREFIX . 'keywords_relations', $tables))
		{
			$fields = array(
				'id_in_module' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
				'module_id' => array('type' => 'string', 'length' => 25, 'default' => "''"),
				'id_keyword' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			);
			PersistenceContext::get_dbms_utils()->create_table(PREFIX . 'keywords_relations', $fields);
		}
		
		PersistenceContext::get_querier()->inject('ALTER TABLE '. DB_TABLE_UPLOAD .' CHANGE size size DOUBLE');
		
		$columns = PersistenceContext::get_dbms_utils()->desc_table(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST);
		if (!isset($columns['default_value']))
			PersistenceContext::get_querier()->inject('ALTER TABLE '. DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST .' CHANGE default_values default_value TEXT');
				
		if (!in_array(PREFIX . 'forum_ranks', $tables))
			PersistenceContext::get_querier()->inject('RENAME TABLE '. PREFIX .'ranks' .' TO '. PREFIX .'forum_ranks');
		
		PersistenceContext::get_dbms_utils()->truncate(PREFIX .'smileys');
		
		$modules_config = ModulesConfig::load();
		foreach (ModulesManager::get_installed_modules_map() as $id => $module)
		{
			if (ModulesManager::module_is_upgradable($id))
				$module->set_installed_version('4.2');
			elseif (version_compare($module->get_configuration()->get_version(), '4.2') == -1)
				$module->set_activated(false);
				
			$modules_config->update($module);
		}
		ModulesConfig::save();

		$this->update_modules();
		
		$this->update_content();
		
		$general_config = GeneralConfig::load();
		$general_config->set_phpboost_major_version('4.2');
		GeneralConfig::save();
		
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
	
	public function update_modules()
	{
		$update_module_class = $this->get_class(PATH_TO_ROOT . self::$directory . '/modules/', self::$module_pattern);
		foreach ($update_module_class as $class_name)
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
			$this->add_error_to_file('module ' . $object->get_module_id(), $success, $message);
		}
	}
	
	public function update_content()
	{
		include PATH_TO_ROOT . '/kernel/db/config.php';
		
		$result = PersistenceContext::get_querier()->select('SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.COLUMNS WHERE
		TABLE_SCHEMA = :table_name
		AND TABLE_NAME LIKE \'%'. PREFIX .'%\'
		AND COLUMN_NAME	IN :column_name
		AND DATA_TYPE IN :data_type', array(
			'table_name' => $db_connection_data['database'],
			'column_name' => array('description', 'content', 'contents', 'message', 'answer'),
			'data_type' => array('mediumtext','text')
		));
		
		foreach ($result as $row)
		{
			PersistenceContext::get_querier()->select('
			UPDATE '. $row['TABLE_NAME'] .' SET 
			'. $row['COLUMN_NAME'] .' = 
			
			replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(
			replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(
			replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(
			
			replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(
			replace(replace(replace(replace(replace(replace(replace(
			
			'. $row['COLUMN_NAME'] .', 
		
			\'../images/smileys/\', \'/images/smileys/\'),
			\'/images/smileys/waw.gif\', \'/images/smileys/straight.png\'), 
			\'/images/smileys/siffle.gif\', \'/images/smileys/whistle.png\'), 
			\'/images/smileys/sourire.gif\', \'/images/smileys/smile.png\'),
			\'/images/smileys/lol.gif\', \'/images/smileys/laugh.png\'),
			\'/images/smileys/rire.gif\', \'/images/smileys/laugh.png\'),
			\'/images/smileys/tirelangue.gif\', \'/images/smileys/tongue.png\'),
			\'/images/smileys/malheureux.gif\', \'/images/smileys/sad.png\'),
			\'/images/smileys/clindoeil.gif\', \'/images/smileys/wink.png\'),
			\'/images/smileys/heink.gif\', \'/images/smileys/what.png\'),
			\'/images/smileys/heureux.gif\', \'/images/smileys/grin.png\'),
			\'/images/smileys/incertain.gif\', \'/images/smileys/straight.png\'),
			\'/images/smileys/content.gif\', \'/images/smileys/happy.png\'),
			\'/images/smileys/pinch.gif\', \'/images/smileys/gne.png\'),
			\'/images/smileys/top.gif\', \'/images/smileys/top.png\'),
			\'/images/smileys/clap.gif\', \'/images/smileys/top.png\'),
			\'/images/smileys/hehe.gif\', \'/images/smileys/devil.png\'),
			\'/images/smileys/angry.gif\', \'/images/smileys/angry.png\'),
			\'/images/smileys/snif.gif\', \'/images/smileys/cry.png\'),
			\'/images/smileys/nex.gif\', \'/images/smileys/crazy.png\'),
			\'/images/smileys/star.gif\', \'/images/smileys/cool.png\'),
			\'/images/smileys/nuit.gif\', \'/images/smileys/night.png\'),
			\'/images/smileys/berk.gif\', \'/images/smileys/vomit.png\'),
			\'/images/smileys/colere.gif\', \'/images/smileys/unhappy.png\'),
			\'/images/smileys/love.gif\', \'/images/smileys/love.png\'),
			\'/images/smileys/doute.gif\', \'/images/smileys/confused.png\'),
			\'/images/smileys/mat.gif\', \'/images/smileys/drooling.png\'),
			\'/images/smileys/miam.gif\', \'/images/smileys/cake.png\'),
			\'/images/smileys/plus1.gif\', \'/images/smileys/top.png\'),
			\'/images/smileys/lu.gif\', \'/images/smileys/hello.png\'),
			
			\'class="bb_table"\', \'class="formatter-table"\'),
			\'class="bb_table_row"\', \'class="formatter-table-row"\'),
			\'class="bb_table_head"\', \'class="formatter-table-head"\'),
			\'class="bb_table_col"\', \'class="formatter-table-col"\'),
			\'class="bb_li"\', \'class="formatter-li"\'),
			\'class="bb_ul"\', \'class="formatter-ul"\'),
			\'class="bb_ol"\', \'class="formatter-ol"\'),
			
			\'class="text_blockquote"\', \'class="formatter-blockquote"\'),
			\'class="text_hide"\', \'class="formatter-hide"\'),
			\'class="bb_block"\', \'class="formatter-block"\'),
			\'class="bb_fieldset"\', \'class="formatter-fieldset"\'),
			\'class="wikipedia_link"\', \'class="wikipedia-link"\'),
			\'class="float_\', \'class="float-\'),
			
			\'class="title1\', \'class="formatter-title\'),
			\'class="title2\', \'class="formatter-title\'),
			\'class="stitle1\', \'class="formatter-title\'),
			\'class="stitle2\', \'class="formatter-title\')
			');
		}
	}
	
	private function get_class($directory, $pattern)
	{
		$class = array();
		$folder = new Folder($directory);
		foreach ($folder->get_files($pattern) as $file)
		{
			$class[] = $file->get_name_without_extension();
		}
		return $class;
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
		// TODO
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
		// TODO
	}
	
	private function delete_old_files_user()
	{
		// TODO
	}
}
?>