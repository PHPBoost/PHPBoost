<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 21
 * @since       PHPBoost 3.0 - 2010 05 28
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GuestbookSetup extends DefaultModuleSetup
{
	public static $guestbook_table;

	public static function __static()
	{
		self::$guestbook_table = PREFIX . 'guestbook';
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
		PersistenceContext::get_dbms_utils()->drop(array(self::$guestbook_table));
	}

	private function delete_configuration()
	{
		ConfigManager::delete('guestbook', 'config');
	}

	private function create_tables()
	{
		$this->create_guestbook_table();
	}

	private function create_guestbook_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'content' => array('type' => 'text', 'length' => 65000),
			'login' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'timestamp' => array('type' => 'key', 'fields' => 'timestamp')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$guestbook_table, $fields, $options);
	}
}
?>
