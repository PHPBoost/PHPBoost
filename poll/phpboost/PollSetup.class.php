<?php
/*##################################################
 *                             PollSetup.class.php
 *                            -------------------
 *   begin                : January 17, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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