<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 06 29
 * @since       PHPBoost 3.0 - 2010 01 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PollSetup extends DefaultModuleSetup
{
	public static $poll_table;
	public static $poll_ip_table;

	public static function __static()
	{
		self::$poll_table = PREFIX . 'poll';
		self::$poll_ip_table = PREFIX . 'poll_ip';
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
		PersistenceContext::get_dbms_utils()->drop(array(self::$poll_table, self::$poll_ip_table));
	}

	private function delete_configuration()
	{
		ConfigManager::delete('poll', 'config');
	}

	private function create_tables()
	{
		$this->create_poll_table();
		$this->create_poll_ip_table();
	}

	private function create_poll_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'question' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'answers' => array('type' => 'text', 'length' => 65000),
			'votes' => array('type' => 'text', 'length' => 65000),
			'type' => array('type' => 'boolean', 'notnull' => 1, 'notnull' => 1, 'default' => 0),
			'archive' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'visible' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'start' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'end' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'default' => 0)

		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$poll_table, $fields, $options);
	}

	private function create_poll_ip_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'ip' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'idpoll' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$poll_ip_table, $fields, $options);
	}

	private function insert_data()
	{
        $this->messages = LangLoader::get('install', 'poll');
		$this->insert_poll_data();
	}

	private function insert_poll_data()
	{
		PersistenceContext::get_querier()->insert(self::$poll_table, array(
			'id' => 1,
			'question' => $this->messages['poll_question'],
			'answers' => $this->messages['poll_answers'],
			'votes' => $this->messages['poll_votes'],
			'type' => 1,
			'archive' => 0,
			'timestamp' => time(),
			'visible' => 1,
			'start' => 0,
			'end' => 0,
			'user_id' => 1
		));
	}
}
?>
