<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 24
 * @since       PHPBoost 3.0 - 2010 01 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ShoutboxSetup extends DefaultModuleSetup
{
	public static $shoutbox_table;
	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$shoutbox_table = PREFIX . 'shoutbox';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
	}

	public function uninstall()
	{
		$this->drop_tables();
		$this->delete_configuration();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$shoutbox_table));
	}

	private function delete_configuration()
	{
		ConfigManager::delete('shoutbox', 'config');
	}

	private function create_tables()
	{
		$this->create_shoutbox_table();
	}

	private function create_shoutbox_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'login' => array('type' => 'string', 'length' => 150, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'content' => array('type' => 'text', 'length' => 65000),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'timestamp' => array('type' => 'key', 'fields' => 'timestamp')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$shoutbox_table, $fields, $options);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'shoutbox');
		$this->insert_shoutbox_data();
	}

	private function insert_shoutbox_data()
	{
		PersistenceContext::get_querier()->insert(self::$shoutbox_table, array(
			'id' => 1,
			'login' => $this->messages['shoutbox_login'],
			'user_id' => -1,
			'content' => $this->messages['shoutbox_content'],
			'timestamp' => time()
		));
	}
}
?>
