<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2012 05 05
*/

class SearchSetup extends DefaultModuleSetup
{
	private static $db_utils;
	public static $search_index_table;
	public static $search_results_table;

	public static function __static()
	{
		self::$db_utils = PersistenceContext::get_dbms_utils();

		self::$search_index_table = PREFIX . 'search_index';
		self::$search_results_table = PREFIX . 'search_results';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}

	public function uninstall()
	{
		$this->drop_tables();
		$this->delete_configuration();
	}

	private function drop_tables()
	{
		self::$db_utils->drop(array(self::$search_index_table, self::$search_results_table));
	}

	private function create_tables()
	{
		$this->create_search_index_table();
		$this->create_search_results_table();
	}

	private function create_search_index_table()
	{
		$fields = array(
			'id_search' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_user' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'module' => array('type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => 0),
			'search' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'options' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'last_search_use' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'times_used' => array('type' => 'integer', 'length' => 3, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id_search'),
			'indexes' => array(
				'id_user' => array('type' => 'unique', 'fields' => array('id_user', 'module', 'search', 'options')),
				'last_search_use' => array('type' => 'key', 'fields' => 'last_search_use')
			)
		);
		self::$db_utils->create_table(self::$search_index_table, $fields, $options);
	}

	private function create_search_results_table()
	{
		$fields = array(
			'id_search' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_content' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'relevance' => array('type' => 'decimal', 'scale' => 3, 'notnull' => 1, 'default' => 0.00),
			'link' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''")

		);

		$options = array(
			'primary' => array('id_search', 'id_content'),
			'indexes' => array(
				'relevance' => array('type' => 'key', 'fields' => 'relevance')
			)
		);
		self::$db_utils->create_table(self::$search_results_table, $fields, $options);
	}

	private function delete_configuration()
	{
		ConfigManager::delete('search', 'config');
	}
}
?>
