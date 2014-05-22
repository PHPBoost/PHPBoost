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
		
		Environment::try_to_increase_max_execution_time();
		
		$this->delete_old_files();
		
		//Change timezone
		PersistenceContext::get_querier()->inject('ALTER TABLE '. DB_TABLE_MEMBER .' CHANGE user_timezone user_timezone VARCHAR(50)');
		
		PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('user_timezone' => 'Europe/Paris'), 'WHERE 1');
		
		$general_config = GeneralConfig::load();
		$general_config->set_site_timezone('Europe/Paris');
		GeneralConfig::save();
		
		$general_config = GeneralConfig::load();
		//$general_config->set_site_path('/trunk');
		$general_config->set_phpboost_major_version('4.1');
		GeneralConfig::save();
		
		//On désinstalle les thèmes non compatible
		$has_compatible_themes = false;
		foreach (ThemesManager::get_activated_themes_map() as $id => $theme)
		{
			$compatibility = $theme->get_configuration()->get_compatibility();
			if ($id !== 'base' && version_compare($compatibility, '4.1', '<'))
			{
				ThemesManager::uninstall($id);
			}
			else
			{
				$has_compatible_themes = true;
			}
		}
		
		if (!$has_compatible_themes)
		{
			ThemesManager::install('base');
			
			$user_accounts_config = UserAccountsConfig::load();
			$user_accounts_config->set_default_theme('base');
			UserAccountsConfig::save();
		}
		
		ModulesManager::install_module('QuestionCaptcha');
		ModulesManager::install_module('ReCaptcha');
		
		$content_management_config = ContentManagementConfig::load();
		$content_management_config->set_used_captcha_module('ReCaptcha');
		ContentManagementConfig::save();
		
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
		
		$columns = PersistenceContext::get_dbms_utils()->desc_table(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST);
		if (!isset($columns['default_value']))
			PersistenceContext::get_querier()->inject('ALTER TABLE '. DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST .' CHANGE default_values default_value TEXT');
				
		if (!in_array(PREFIX . 'forum_ranks', $tables))
			PersistenceContext::get_querier()->inject('RENAME TABLE '. PREFIX .'ranks' .' TO '. PREFIX .'forum_ranks');
		
		PersistenceContext::get_dbms_utils()->truncate(PREFIX .'smileys');
		
		$this->insert_smileys_data();
		
		$modules_config = ModulesConfig::load();
		foreach (ModulesManager::get_installed_modules_map() as $id => $module)
		{
			if (ModulesManager::module_is_upgradable($id))
				$module->set_installed_version('4.1');
			else
				$module->set_activated(false);
				
			$modules_config->update($module);
		}
		ModulesConfig::save();

		$this->update_modules();
		
		$this->update_content();
		
		$this->delete_update_token();
		$this->generate_cache();
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
			$this->add_error_to_file($object->get_module_id() . '_module', $success, $message);
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
			
			\'class="bb_table"\', \'class="bb-table"\'),
			\'class="bb_table_row"\', \'class="bb-table-row"\'),
			\'class="bb_table_head"\', \'class="bb-table-head"\'),
			\'class="bb_table_col"\', \'class="bb-table-col"\'),
			\'class="bb_li"\', \'class="bb-li"\'),
			\'class="bb_ul"\', \'class="bb-ul"\'),
			\'class="bb_ol"\', \'class="bb-ol"\'),
			
			\'class="text_blockquote"\', \'class="text-blockquote"\'),
			\'class="text_hide"\', \'class="text-hide"\'),
			\'class="bb_block"\', \'class="bb-block"\'),
			\'class="bb_fieldset"\', \'class="bb-fieldset"\'),
			\'class="wikipedia_link"\', \'class="wikipedia-link"\'),
			\'class="float_\', \'class="float-\'),
			
			\'class="title1\', \'class="bb-title\'),
			\'class="title2\', \'class="bb-title\'),
			\'class="stitle1\', \'class="bb-title\'),
			\'class="stitle2\', \'class="bb-title\')
			');
		}
	}
	
	private function insert_smileys_data()
	{
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 1,
			'code_smiley' => ':o',
			'url_smiley' => 'wow.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 2,
			'code_smiley' => ':whistle',
			'url_smiley' => 'whistle.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 3,
			'code_smiley' => ':)',
			'url_smiley' => 'smile.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 4,
			'code_smiley' => ':lol',
			'url_smiley' => 'laugh.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 5,
			'code_smiley' => ':p',
			'url_smiley' => 'tongue.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 6,
			'code_smiley' => ':(',
			'url_smiley' => 'sad.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 7,
			'code_smiley' => ';)',
			'url_smiley' => 'wink.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 8,
			'code_smiley' => ':?',
			'url_smiley' => 'what.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 9,
			'code_smiley' => ':D',
			'url_smiley' => 'grin.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 10,
			'code_smiley' => '^^',
			'url_smiley' => 'happy.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 11,
			'code_smiley' => ':|',
			'url_smiley' => 'straight.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 12,
			'code_smiley' => 'x|',
			'url_smiley' => 'gne.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 13,
			'code_smiley' => ':top',
			'url_smiley' => 'top.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 14,
			'code_smiley' => ':party',
			'url_smiley' => 'party.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 15,
			'code_smiley' => '3:)',
			'url_smiley' => 'devil.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 16,
			'code_smiley' => ':@',
			'url_smiley' => 'angry.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 17,
			'code_smiley' => ':\'(',
			'url_smiley' => 'cry.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 18,
			'code_smiley' => ':crazy',
			'url_smiley' => 'crazy.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 19,
			'code_smiley' => '8-)',
			'url_smiley' => 'cool.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 20,
			'code_smiley' => '|-)',
			'url_smiley' => 'night.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 21,
			'code_smiley' => ':vomit',
			'url_smiley' => 'vomit.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 22,
			'code_smiley' => '>:)',
			'url_smiley' => 'unhappy.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 23,
			'code_smiley' => ':love',
			'url_smiley' => 'love.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 24,
			'code_smiley' => ':hum',
			'url_smiley' => 'confused.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 25,
			'code_smiley' => ':drool',
			'url_smiley' => 'drooling.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 26,
			'code_smiley' => ':cold',
			'url_smiley' => 'cold.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 27,
			'code_smiley' => ':hot',
			'url_smiley' => 'hot.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 28,
			'code_smiley' => ':hi',
			'url_smiley' => 'hello.png'
		));

		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 29,
			'code_smiley' => ':bal',
			'url_smiley' => 'balloon.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 30,
			'code_smiley' => ':bomb',
			'url_smiley' => 'bomb.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 31,
			'code_smiley' => ':/L',
			'url_smiley' => 'brokenheart.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 32,
			'code_smiley' => ':cake',
			'url_smiley' => 'cake.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 33,
			'code_smiley' => ':dead',
			'url_smiley' => 'dead.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 34,
			'code_smiley' => ':drink',
			'url_smiley' => 'drink.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 35,
			'code_smiley' => ':flower',
			'url_smiley' => 'flower.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 36,
			'code_smiley' => ':ghost',
			'url_smiley' => 'ghost.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 37,
			'code_smiley' => ':gift',
			'url_smiley' => 'gift.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 38,
			'code_smiley' => ':girly',
			'url_smiley' => 'girly.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 39,
			'code_smiley' => ':L',
			'url_smiley' => 'heart.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 40,
			'code_smiley' => ':hug',
			'url_smiley' => 'hug.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 41,
			'code_smiley' => ':idea',
			'url_smiley' => 'idea.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 42,
			'code_smiley' => ':kiss',
			'url_smiley' => 'kiss.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 43,
			'code_smiley' => ':mail',
			'url_smiley' => 'mail.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 44,
			'code_smiley' => ':x',
			'url_smiley' => 'mute.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 46,
			'code_smiley' => ':nerd',
			'url_smiley' => 'nerd.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 47,
			'code_smiley' => ':sick',
			'url_smiley' => 'sick.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 48,
			'code_smiley' => ':boring',
			'url_smiley' => 'boring.png'
		));
		PersistenceContext::get_querier()->insert(PREFIX .'smileys', array(
			'idsmiley' => 49,
			'code_smiley' => ':zombie',
			'url_smiley' => 'zombie.png'
		));
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
		HtaccessFileCache::regenerate();
	}
	
	private function add_error_to_file($step_name, $success, $message)
	{
		$success_message = $success ? 'Ok !' : 'Error :';
		$this->update_followed_file->append($step_name.' '.$success_message.' '. $message. "\r\n");
	}
	
	private function generate_update_token()
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
		$this->delete_old_files_connect();
		$this->delete_old_files_images();
		$this->delete_old_files_kernel();
		$this->delete_old_files_lang();
		$this->delete_old_files_templates();
		$this->delete_old_files_user();
	}
	
	private function delete_old_files_admin()
	{
		$file = new File(Url::to_rel('/admin/admin_content_config.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/admin_errors.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/admin_members.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/admin_members_punishment.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/admin_ranks.php'));
		$file->delete();
		$file = new File(Url::to_rel('/admin/admin_ranks_add.php'));
		$file->delete();
	}
	
	private function delete_old_files_connect()
	{
		$file = new File(Url::to_rel('/connect/templates/index.php'));
		$file->delete();
		
		$folder = new Folder(Url::to_rel('/connect/templates/images'));
		if ($folder->exists())
			$folder->delete();
	}
	
	private function delete_old_files_images()
	{
		$file = new File(Url::to_rel('/images/avatars/index.php'));
		$file->delete();
		$file = new File(Url::to_rel('/images/avatars/customization.php'));
		$file->delete();
		$file = new File(Url::to_rel('/images/avatars/group.php'));
		$file->delete();
		$file = new File(Url::to_rel('/images/avatars/math.php'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/angry.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/berk.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/clap.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/clindoeil.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/colere.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/content.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/doute.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/hehe.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/heink.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/heureux.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/incertain.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/index.php'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/lol.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/love.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/lu.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/malheureux.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/mat.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/miam.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/nex.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/nuit.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/pinch.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/plus1.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/rire.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/siffle.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/snif.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/sourire.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/star.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/tirelangue.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/top.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/smileys/wow.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/images/stats/index.php'));
		$file->delete();
		$file = new File(Url::to_rel('/images/index.php'));
		$file->delete();
	}
	
	private function delete_old_files_kernel()
	{
		$file = new File(Url::to_rel('/kernel/framework/ajax/captcha.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/ajax/stats_xmlhttprequest.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/content/category/CategoryTree.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/content/category/CategoryTreeCache.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/content/category/CategoryTreeManager.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/content/search/Search.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/content/CategoriesManager.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/helper/BooleanHelper.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/phpboost/cache/RanksCache.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/phpboost/deprecated/DeprecatedPagination.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/phpboost/langs/LangManager.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/phpboost/module/css/CssFilesExtensionPointService.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/phpboost/theme/ThemeManager.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/util/ImagesStats.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/util/Pagination.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/util/PHPBoostCaptcha.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/framework/functions.inc.php'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/lib/phpboost/bbcode.js'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/lib/phpboost/global.js'));
		$file->delete();
		$file = new File(Url::to_rel('/kernel/css_cache.php'));
		$file->delete();
		
		$folder = new Folder(Url::to_rel('/kernel/framework/phpboost/module/homepage'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/kernel/lib/js/lightbox'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/kernel/lib/js/scriptaculous'));
		if ($folder->exists())
			$folder->delete();
	}
	
	private function delete_old_files_lang()
	{
		$file = new File(Url::to_rel('/lang/english/classes/AdminErrorsController404List.php'));
		$file->delete();
		$file = new File(Url::to_rel('/lang/english/admin-extended-fields-common.php'));
		$file->delete();
		$file = new File(Url::to_rel('/lang/english/admin-members-common.php'));
		$file->delete();
		$file = new File(Url::to_rel('/lang/english/errors-common.php'));
		$file->delete();
		$file = new File(Url::to_rel('/lang/english/index.php'));
		$file->delete();
		$file = new File(Url::to_rel('/lang/french/classes/AdminErrorsController404List.php'));
		$file->delete();
		$file = new File(Url::to_rel('/lang/french/admin-extended-fields-common.php'));
		$file->delete();
		$file = new File(Url::to_rel('/lang/french/admin-members-common.php'));
		$file->delete();
		$file = new File(Url::to_rel('/lang/french/errors-common.php'));
		$file->delete();
		$file = new File(Url::to_rel('/lang/french/index.php'));
		$file->delete();
		$file = new File(Url::to_rel('/lang/index.php'));
		$file->delete();
	}
	
	private function delete_old_files_templates()
	{
		/* A compléter */
	}
	
	private function delete_old_files_user()
	{
		$file = new File(Url::to_rel('/user/util/UserCommentsListPagination.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/user/util/UserUsersListPagination.class.php'));
		$file->delete();
	}
}
?>