<?php
/*##################################################
 *                             ForumSetup.class.php
 *                            -------------------
 *   begin                : May 27, 2010
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

class ForumSetup extends DefaultModuleSetup
{
	private static $forum_alerts_table;
	private static $forum_cats_table;
	private static $forum_history_table;
	private static $forum_message_table;
	private static $forum_poll_table;
	private static $forum_topics_table;
	private static $forum_track_table;
	private static $forum_view_table;

	private static $member_extended_field_last_view_forum_column = 'last_view_forum';

	private $querier;

	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$forum_alerts_table = PREFIX . 'forum_alerts';
		self::$forum_cats_table = PREFIX . 'forum_cats';
		self::$forum_history_table = PREFIX . 'forum_history';
		self::$forum_message_table = PREFIX . 'forum_msg';
		self::$forum_poll_table = PREFIX . 'forum_poll';
		self::$forum_topics_table = PREFIX . 'forum_topics';
		self::$forum_track_table = PREFIX . 'forum_track';
		self::$forum_view_table = PREFIX . 'forum_view';
	}

	public function __construct()
	{
		$this->querier = PersistenceContext::get_querier();
	}

	public function install()
	{
		$this->uninstall();
		$this->create_tables();
		$this->insert_data();
	}

	public function uninstall()
	{
		$this->drop_tables();
		$this->delete_member_extended_last_view_forum();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(
		self::$forum_alerts_table,
		self::$forum_cats_table,
		self::$forum_history_table,
		self::$forum_message_table,
		self::$forum_poll_table,
		self::$forum_topics_table,
		self::$forum_track_table,
		self::$forum_view_table
		));
	}

	private function create_tables()
	{
		$this->create_forum_alerts_table();
		$this->create_forum_cats_table();
		$this->create_forum_history_table();
		$this->create_forum_message_table();
		$this->create_forum_poll_table();
		$this->create_forum_topics_table();
		$this->create_forum_track_table();
		$this->create_forum_view_table();
	}

	private function create_forum_alerts_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idcat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'idtopic' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'contents' => array('type' => 'text', 'length' => 65000),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'status' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'idmodo' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'idtopic' => array('type' => 'key', 'fields' => array('idtopic', 'user_id', 'idmodo'))
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_alerts_table, $fields, $options);
	}

	private function create_forum_cats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_left' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'id_right' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'level' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 100, 'notnull' => 1),
			'subname' => array('type' => 'string', 'length' => 150, 'notnull' => 1),
			'nbr_topic' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'nbr_msg' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'last_topic_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'status' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'aprob' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000),
			'url' => array('type' => 'text', 'length' => 2048)
		);
		$options = array(
			'primary' => array('id'),
			'last_topic_id' => array('type' => 'key', 'fields' => 'last_topic_id'),
			'id_left' => array('type' => 'key', 'fields' => 'id_left')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_cats_table, $fields, $options);
	}

	private function create_forum_history_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'action' => array('type' => 'string', 'length' => 50, 'notnull' => 1),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id_action' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'url' => array('type' => 'text', 'length' => 2048),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'user_id' => array('type' => 'key', 'fields' => 'user_id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_history_table, $fields, $options);
	}

	private function create_forum_message_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idtopic' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'contents' => array('type' => 'text', 'length' => 65000),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp_edit' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id_edit' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_ip' => array('type' => 'string', 'length' => 128, 'notnull' => 1)
		);
		$options = array(
			'primary' => array('id'),
			'idtopic' => array('type' => 'key', 'fields' => 'idtopic'),
			'contenu' => array('type' => 'fulltext', 'fields' => 'contents')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_message_table, $fields, $options);
	}

	private function create_forum_poll_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idtopic' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'question' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'answers' => array('type' => 'text', 'length' => 65000),
			'voter_id' => array('type' => 'text', 'length' => 65000),
			'votes' => array('type' => 'text', 'length' => 65000),
			'type' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'idtopic' => array('type' => 'unique', 'fields' => 'idtopic')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_poll_table, $fields, $options);
	}

	private function create_forum_topics_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idcat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 100, 'notnull' => 1),
			'subtitle' => array('type' => 'string', 'length' => 75, 'notnull' => 1),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'nbr_msg' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'nbr_views' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'last_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_msg_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'first_msg_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'type' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'status' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'aprob' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'display_msg' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'idcat' => array('type' => 'key', 'fields' => array('idcat', 'last_user_id', 'last_timestamp', 'type')),
			'title' => array('type' => 'key', 'fields' => 'title')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_topics_table, $fields, $options);
	}

	private function create_forum_track_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idtopic' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'track' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'pm' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'mail' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'idtopic' => array('type' => 'unique', 'fields' => array('idtopic', 'user_id'))
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_track_table, $fields, $options);
	}

	private function create_forum_view_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idtopic' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_view_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'idv' => array('type' => 'key', 'fields' => array('idtopic', 'user_id'))
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_view_table, $fields, $options);
	}

	private function delete_member_extended_last_view_forum()
	{
		$extended_field = new ExtendedField();
		$extended_field->set_field_name(self::$member_extended_field_last_view_forum_column);
		ExtendedFieldsService::delete($extended_field, ExtendedFieldsService::BY_FIELD_NAME);
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'forum');
		$this->create_member_extended_field();
		$this->insert_forum_cats_data();
		$this->insert_forum_topics_data();
		$this->insert_forum_msg_data();
	}

	private function create_member_extended_field()
	{
		$extended_field = new ExtendedField();
		$extended_field->set_name(self::$member_extended_field_last_view_forum_column);
		$extended_field->set_field_name(self::$member_extended_field_last_view_forum_column);
		$extended_field->set_field_type('0');
		$extended_field->set_is_freeze('1');
		ExtendedFieldsService::add($extended_field);
	}

	private function insert_forum_cats_data()
	{
		$this->querier->insert(self::$forum_cats_table, array(
			'id' => 1,
			'id_left' => 1,
			'id_right' => 4,
			'level' => 0,
			'name' => $this->messages['default.category.name'],
			'subname' => $this->messages['default.category.subname'],
			'nbr_topic' => 1,
			'nbr_msg' => 1,
			'last_topic_id' => 1,
			'status' => 1,
			'aprob' => 1,
			'auth' => 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:1;s:2:"r1";i:1;s:2:"r2";i:7;}',
			'url' => ''
		));

		$this->querier->insert(self::$forum_cats_table, array(
			'id' => 2,
			'id_left' => 2,
			'id_right' => 3,
			'level' => 1,
			'name' => $this->messages['default.board.name'],
			'subname' => $this->messages['default.board.subname'],
			'nbr_topic' => 1,
			'nbr_msg' => 1,
			'last_topic_id' => 1,
			'status' => 1,
			'aprob' => 1,
			'auth' => 'a:4:{s:3:"r-1";i:1;s:2:"r0";i:3;s:2:"r1";i:7;s:2:"r2";i:7;}',
			'url' => ''
		));
	}

	private function insert_forum_topics_data()
	{
		$this->querier->insert(self::$forum_topics_table, array(
			'id' => 1,
			'idcat' => 2,
			'title' => $this->messages['sample.thread.title'],
			'subtitle' => $this->messages['sample.thread.subtitle'],
			'user_id' => 1,
			'nbr_msg' => 1,
			'nbr_views' => 0,
			'last_user_id' => 1,
			'last_msg_id' => 1,
			'last_timestamp' => time(),
			'first_msg_id' => 1,
			'type' => 0,
			'status' => 1,
			'aprob' => 0,
			'display_msg' => 0
		));
	}

	private function insert_forum_msg_data()
	{
		$this->querier->insert(self::$forum_message_table, array(
			'id' => 1,
		 	'idtopic' => 1,
			'user_id' => 1,
			'contents' => $this->messages['sample.thread.message.content'],
			'timestamp' => time(),
			'timestamp_edit' => 0,
			'user_id_edit' => 0
		));
	}
}

?>
