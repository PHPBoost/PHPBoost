<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 22
 * @since       PHPBoost 6.0 - 2021 10 22
*/

class HistorySetup extends DefaultModuleSetup
{
	public static $history_table;

	public static function __static()
	{
		self::$history_table = PREFIX . 'history';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}

	public function uninstall()
	{
		$this->drop_tables();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$history_table));
	}

	private function create_tables()
	{
		$this->create_history_table();
	}

	private function create_history_table()
	{
		$fields = array(
			'id'            => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'module_id'     => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'id_in_module'  => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'user_id'       => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'action'        => array('type' => 'string', 'length' => 128, 'notnull' => 1, 'default' => "''"),
			'title'         => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'url'           => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'description'   => array('type' => 'string', 'length' => 512, 'default' => "''")
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'module_id_index'    => array('type' => 'key', 'fields' => 'module_id'),
				'id_in_module_index' => array('type' => 'key', 'fields' => 'id_in_module')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$history_table, $fields, $options);
	}
}
?>
