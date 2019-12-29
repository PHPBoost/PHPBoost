<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 29
 * @since       PHPBoost 3.0 - 2010 05 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ForumSetup extends DefaultModuleSetup
{
	public static $forum_alerts_table;
	public static $forum_cats_table;
	public static $forum_history_table;
	public static $forum_message_table;
	public static $forum_poll_table;
	public static $forum_topics_table;
	public static $forum_track_table;
	public static $forum_view_table;
	public static $forum_ranks_table;

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
		self::$forum_ranks_table = PREFIX . 'forum_ranks';
	}

	public function __construct()
	{
		$this->querier = PersistenceContext::get_querier();
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
		ConfigManager::delete('forum', 'config');
		$this->delete_member_extended_field();
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
			self::$forum_view_table,
			self::$forum_ranks_table
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
		$this->create_forum_ranks_table();
	}

	private function create_forum_alerts_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'idtopic' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
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
		ForumCategory::create_categories_table(self::$forum_cats_table);
	}

	private function create_forum_history_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'action' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
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
			'user_ip' => array('type' => 'string', 'length' => 128, 'notnull' => 1, 'default' => "''")
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'idtopic' => array('type' => 'key', 'fields' => 'idtopic'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_message_table, $fields, $options);
	}

	private function create_forum_poll_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idtopic' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'question' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
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
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"),
			'subtitle' => array('type' => 'string', 'length' => 75, 'notnull' => 1, 'default' => "''"),
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
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => array('id_category', 'last_user_id', 'last_timestamp', 'type')),
				'title' => array('type' => 'fulltext', 'fields' => 'title')

		));
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

	private function create_forum_ranks_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "''"),
			'msg' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'icon' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'special' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$forum_ranks_table, $fields, $options);
	}

	private function delete_member_extended_field()
	{
		ExtendedFieldsService::delete_by_field_name(self::$member_extended_field_last_view_forum_column);
		ExtendedFieldsService::delete_by_field_name('user_website');
		ExtendedFieldsService::delete_by_field_name('user_skype');
		ExtendedFieldsService::delete_by_field_name('user_sign');
	}

	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'forum');
		$this->create_member_extended_field();
		$this->insert_forum_cats_data();
		$this->insert_forum_topics_data();
		$this->insert_forum_msg_data();
		$this->insert_forum_ranks_data();
	}

	private function create_member_extended_field()
	{
		$lang = LangLoader::get('common', 'forum');

		$extended_field = new ExtendedField();
		$extended_field->set_name(self::$member_extended_field_last_view_forum_column);
		$extended_field->set_field_name(self::$member_extended_field_last_view_forum_column);
		$extended_field->set_field_type('MemberHiddenExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(false);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);

		//Website
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['extended.field.website']);
		$extended_field->set_field_name('user_website');
		$extended_field->set_description($lang['extended.field.website.explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(false);
		$extended_field->set_is_freeze(true);
		$extended_field->set_regex(5);
		ExtendedFieldsService::add($extended_field);

		//Skype
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['extended.field.skype']);
		$extended_field->set_field_name('user_skype');
		$extended_field->set_description($lang['extended.field.skype.explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(false);
		$extended_field->set_is_freeze(true);
		$extended_field->set_regex(4);
		ExtendedFieldsService::add($extended_field);

		//Sign
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['extended.field.signing']);
		$extended_field->set_field_name('user_sign');
		$extended_field->set_description($lang['extended.field.signing.explain']);
		$extended_field->set_field_type('MemberLongTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(false);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
	}

	private function insert_forum_cats_data()
	{
		$this->querier->insert(self::$forum_cats_table, array(
			'id' => 1,
			'name' => $this->messages['default.category.name'],
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'description' => $this->messages['default.category.description'],
			'c_order' => 1,
			'auth' => '',
			'id_parent' => 0,
			'last_topic_id' => 1,
			'url' => ''
		));

		$this->querier->insert(self::$forum_cats_table, array(
			'id' => 2,
			'name' => $this->messages['default.board.name'],
			'rewrited_name' => Url::encode_rewrite($this->messages['default.board.name']),
			'description' => $this->messages['default.board.description'],
			'c_order' => 1,
			'auth' => '',
			'id_parent' => 1,
			'last_topic_id' => 1,
			'url' => ''
		));
	}

	private function insert_forum_topics_data()
	{
		$this->querier->insert(self::$forum_topics_table, array(
			'id' => 1,
			'id_category' => 2,
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
			'user_id_edit' => 0,
			'user_ip' => AppContext::get_request()->get_ip_address()
		));

		//Mise à jour du nombre de messages du membre.
		$this->querier->inject("UPDATE " . DB_TABLE_MEMBER . " SET posted_msg = posted_msg + 1 WHERE user_id = '1'");
	}

	private function insert_forum_ranks_data()
	{
		$this->querier->insert(self::$forum_ranks_table, array(
			'id' => 1,
			'name' => $this->messages['rank.admin'],
			'msg' => -2,
			'icon' => 'rank_admin.png',
			'special' => 1
		));
		$this->querier->insert(self::$forum_ranks_table, array(
			'id' => 2,
			'name' => $this->messages['rank.modo'],
			'msg' => -1,
			'icon' => 'rank_modo.png',
			'special' => 1
		));
		$this->querier->insert(self::$forum_ranks_table, array(
			'id' => 3,
			'name' => $this->messages['rank.inactiv'],
			'msg' => 0,
			'icon' => 'rank_0.png',
			'special' => 0
		));
		$this->querier->insert(self::$forum_ranks_table, array(
			'id' => 4,
			'name' => $this->messages['rank.fronde'],
			'msg' => 1,
			'icon' => 'rank_0.png',
			'special' => 0
		));
		$this->querier->insert(self::$forum_ranks_table, array(
			'id' => 5,
			'name' => $this->messages['rank.minigun'],
			'msg' => 25,
			'icon' => 'rank_1.png',
			'special' => 0
		));
		$this->querier->insert(self::$forum_ranks_table, array(
			'id' => 6,
			'name' => $this->messages['rank.fuzil'],
			'msg' => 50,
			'icon' => 'rank_2.png',
			'special' => 0
		));
		$this->querier->insert(self::$forum_ranks_table, array(
			'id' => 7,
			'name' => $this->messages['rank.bazooka'],
			'msg' => 100,
			'icon' => 'rank_3.png',
			'special' => 0
		));
		$this->querier->insert(self::$forum_ranks_table, array(
			'id' => 8,
			'name' => $this->messages['rank.roquette'],
			'msg' => 250,
			'icon' => 'rank_4.png',
			'special' => 0
		));
		$this->querier->insert(self::$forum_ranks_table, array(
			'id' => 9,
			'name' => $this->messages['rank.mortier'],
			'msg' => 500,
			'icon' => 'rank_5.png',
			'special' => 0
		));
		$this->querier->insert(self::$forum_ranks_table, array(
			'id' => 10,
			'name' => $this->messages['rank.missile'],
			'msg' => 1000,
			'icon' => 'rank_6.png',
			'special' => 0
		));
		$this->querier->insert(self::$forum_ranks_table, array(
			'id' => 11,
			'name' => $this->messages['rank.fusee'],
			'msg' => 1500,
			'icon' => 'rank_special.png',
			'special' => 0
		));

	}
}
?>
