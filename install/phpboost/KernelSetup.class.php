<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 04
 * @since       PHPBoost 3.0 - 2010 05 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class KernelSetup
{
	/**
	 * @var DBMSUtils
	 */
	private static $db_utils;
	/**
	 * @var DBQuerier
	 */
	private static $db_querier;
	private static $comments_table;
	private static $comments_topic_table;
	private static $note_table;
	private static $average_notes_table;
	private static $visit_counter_table;
	private static $configs_table;
	private static $events_table;
	private static $errors_404_table;
	private static $group_table;
	private static $keywords_table;
	private static $keywords_relations_table;
	private static $member_table;
	private static $member_extended_fields_table;
	private static $member_extended_fields_list;
	private static $menus_table;
	private static $pm_msg_table;
	private static $pm_topic_table;
	private static $sessions_table;
	private static $internal_authentication_table;
	private static $internal_authentication_failures_table;
	private static $authentication_method_table;
	private static $smileys_table;
	private static $upload_table;
	private static $upload_cat_table;

	public static function __static()
	{
		self::$db_utils = PersistenceContext::get_dbms_utils();
		self::$db_querier = PersistenceContext::get_querier();

		self::$comments_table = PREFIX . 'comments';
		self::$comments_topic_table = PREFIX . 'comments_topic';
		self::$note_table = PREFIX . 'note';
		self::$average_notes_table = PREFIX . 'average_notes';
		self::$visit_counter_table = PREFIX . 'visit_counter';
		self::$configs_table = PREFIX . 'configs';
		self::$events_table = PREFIX . 'events';
		self::$errors_404_table = PREFIX . 'errors_404';
		self::$group_table = PREFIX . 'group';
		self::$keywords_table = PREFIX . 'keywords';
		self::$keywords_relations_table = PREFIX . 'keywords_relations';
		self::$member_table = PREFIX . 'member';
		self::$member_extended_fields_table = PREFIX . 'member_extended_fields';
		self::$member_extended_fields_list = PREFIX . 'member_extended_fields_list';
		self::$menus_table = PREFIX . 'menus';
		self::$pm_msg_table = PREFIX . 'pm_msg';
		self::$pm_topic_table = PREFIX . 'pm_topic';
		self::$sessions_table = PREFIX . 'sessions';
		self::$internal_authentication_table = PREFIX . 'internal_authentication';
		self::$internal_authentication_failures_table = PREFIX . 'internal_authentication_failures';
		self::$authentication_method_table = PREFIX . 'authentication_method';
		self::$smileys_table = PREFIX . 'smileys';
		self::$upload_table = PREFIX . 'upload';
		self::$upload_cat_table = PREFIX . 'upload_cat';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
	}

	private function drop_tables()
	{
		self::$db_utils->drop(array(
			self::$comments_table,
			self::$comments_topic_table,
			self::$note_table,
			self::$average_notes_table,
			self::$visit_counter_table,
			self::$configs_table,
			self::$events_table,
			self::$errors_404_table,
			self::$group_table,
			self::$keywords_table,
			self::$keywords_relations_table,
			self::$member_table,
			self::$member_extended_fields_table,
			self::$member_extended_fields_list,
			self::$menus_table,
			self::$pm_msg_table,
			self::$pm_topic_table,
			self::$sessions_table,
			self::$internal_authentication_table,
			self::$internal_authentication_failures_table,
			self::$authentication_method_table,
			self::$smileys_table,
			self::$upload_table,
			self::$upload_cat_table
		));
	}

	private function create_tables()
	{
		$this->create_note_table();
		$this->create_comments_table();
		$this->create_comments_topic_table();
		$this->create_average_notes_table();
		$this->create_visit_counter_table();
		$this->create_configs_table();
		$this->create_events_table();
		$this->create_errors_404_table();
		$this->create_group_table();
		$this->create_keywords_table();
		$this->create_keywords_relations_table();
		$this->create_member_table();
		$this->create_member_extended_fields_table();
		$this->create_member_extended_fields_list_table();
		$this->create_menus_table();
		$this->create_pm_msg_table();
		$this->create_pm_topic_table();
		$this->create_sessions_table();
		$this->create_internal_authentication_table();
		$this->create_internal_authentication_failures_table();
		$this->create_authentication_method_table();
		$this->create_smileys_table();
		$this->create_upload_table();
		$this->create_upload_cat_table();
	}

	private function create_comments_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_topic' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'message' => array('type' => 'text', 'length' => 65000),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'pseudo' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'visitor_email' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'user_ip' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'note' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
		);
		self::$db_utils->create_table(self::$comments_table, $fields, $options);
	}

	private function create_comments_topic_table()
	{
		$fields = array(
			'id_topic' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'module_id' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'topic_identifier' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "'default'"),
			'id_in_module' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'is_locked' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'comments_number' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'path' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''")
		);
		$options = array(
			'primary' => array('id_topic'),
		);
		self::$db_utils->create_table(self::$comments_topic_table, $fields, $options);
	}

	private function create_note_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'module_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'id_in_module' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'note' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0),
		);
		$options = array(
			'primary' => array('id'),
		);
		self::$db_utils->create_table(self::$note_table, $fields, $options);
	}

	private function create_average_notes_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'module_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'id_in_module' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'average_notes' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0),
			'notes_number' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0),
		);
		$options = array(
			'primary' => array('id'),
		);
		self::$db_utils->create_table(self::$average_notes_table, $fields, $options);
	}

	private function create_visit_counter_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'ip' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'time' => array('type' => 'date', 'notnull' => 1),
			'total' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'ip' => array('type' => 'key', 'fields' => 'ip')
		));
		self::$db_utils->create_table(self::$visit_counter_table, $fields, $options);
	}

	private function create_configs_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "''"),
			'value' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'name' => array('type' => 'unique', 'fields' => 'name')
			)
		);
		self::$db_utils->create_table(self::$configs_table, $fields, $options);
	}

	private function create_events_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'entitled' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'description' => array('type' => 'text', 'length' => 65000),
			'fixing_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'module' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"),
			'current_status' => array('type' => 'boolean', 'length' => 3, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'fixing_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000),
			'poster_id' => array('type' => 'integer', 'length' => 11),
			'fixer_id' => array('type' => 'integer', 'length' => 11),
			'id_in_module' => array('type' => 'integer', 'length' => 11),
			'identifier' => array('type' => 'string', 'length' => 64),
			'contribution_type' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => "1"),
			'type' => array('type' => 'string', 'length' => 64),
			'priority' => array('type' => 'boolean', 'length' => 3, 'notnull' => 1, 'default' => 3)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'type_index' => array('type' => 'key', 'fields' => 'type'),
				'identifier_index' => array('type' => 'key', 'fields' => 'identifier'),
				'module_index' => array('type' => 'key', 'fields' => 'module'),
				'id_in_module_index' => array('type' => 'key', 'fields' => 'id_in_module')
		));
		self::$db_utils->create_table(self::$events_table, $fields, $options);
	}

	private function create_errors_404_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 10, 'autoincrement' => true, 'notnull' => 1),
			'requested_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'from_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'times' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);

		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'unique' => array('type' => 'unique', 'fields' => array('requested_url', 'from_url'))
			),
			'charset' => 'latin1'
		);
		self::$db_utils->create_table(self::$errors_404_table, $fields, $options);
	}

	private function create_group_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"),
			'img' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'color' => array('type' => 'string', 'length' => 6, 'notnull' => 1, 'default' => "''"),
			'auth' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'members' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'primary' => array('id'),
		);
		self::$db_utils->create_table(self::$group_table, $fields, $options);
	}

	private function create_keywords_table()
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
		self::$db_utils->create_table(self::$keywords_table, $fields, $options);
	}

	private function create_keywords_relations_table()
	{
		$fields = array(
			'id_in_module' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'module_id' => array('type' => 'string', 'length' => 25, 'default' => "''"),
			'id_keyword' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
		);
		self::$db_utils->create_table(self::$keywords_relations_table, $fields);
	}

	private function create_member_table()
	{
		$fields = array(
			'user_id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'display_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'level' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'email' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'show_email' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 1),
			'locale' => array('type' => 'string', 'length' => 25, 'default' => "''"),
			'theme' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'timezone' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'editor' => array('type' => 'string', 'length' => 15, 'default' => "''"),
			'unread_pm' => array('type' => 'integer', 'length' => 6, 'notnull' => 1, 'default' => 0),
			'posted_msg' => array('type' => 'integer', 'length' => 6, 'notnull' => 1, 'default' => 0),
			'registration_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_connection_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_groups' => array('type' => 'text', 'length' => 65000),
			'warning_percentage' => array('type' => 'integer', 'length' => 6, 'notnull' => 1, 'default' => 0),
			'delay_banned' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'delay_readonly' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'autoconnect_key' => array('type' => 'string', 'length' => 64, 'default' => "''")
		);

		$options = array(
		'primary' => array('user_id'),
		'indexes' => array(
			'display_name' => array('type' => 'unique', 'fields' => 'display_name'),
			'email' => array('type' => 'unique', 'fields' => 'email')
		));
		self::$db_utils->create_table(self::$member_table, $fields, $options);
	}

	private function create_member_extended_fields_table()
	{
		$fields = array(
			'user_id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
		);

		$options = array(
			'primary' => array('user_id'),
		);
		self::$db_utils->create_table(self::$member_extended_fields_table, $fields, $options);
	}

	private function create_member_extended_fields_list_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'position' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'field_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'description' => array('type' => 'text', 'length' => 65000),
			'field_type' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'possible_values' => array('type' => 'text', 'length' => 65000),
			'default_value' => array('type' => 'text', 'length' => 65000),
			'required' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'display' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'regex' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'freeze' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'auth' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'indexes' => array(
				'id' => array('type' => 'unique', 'fields' => 'id')
		));
		self::$db_utils->create_table(self::$member_extended_fields_list, $fields, $options);
	}

	private function create_menus_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 128, 'notnull' => 1, 'default' => "''"),
			'object' => array('type' => 'text', 'length' => 16777215),
			'class' => array('type' => 'string', 'length' => 67, 'notnull' => 1, 'default' => "''"),
			'enabled' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'block' => array('type' => 'boolean', 'length' => 2, 'notnull' => 1, 'default' => 0),
			'position' => array('type' => 'boolean', 'length' => 2, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'block' => array('type' => 'key', 'fields' => 'block'),
				'class' => array('type' => 'key', 'fields' => 'class'),
				'enabled' => array('type' => 'key', 'fields' => 'enabled')
		));
		self::$db_utils->create_table(self::$menus_table, $fields, $options);
	}

	private function create_pm_msg_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idconvers' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'contents' => array('type' => 'text', 'length' => 65000),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'view_status' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0)
		);

		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'idconvers' => array('type' => 'key', 'fields' => array('idconvers', 'user_id', 'timestamp'))
		));
		self::$db_utils->create_table(self::$pm_msg_table, $fields, $options);
	}

	private function create_pm_topic_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id_dest' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_convers_status' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'user_view_pm' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'nbr_msg' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_msg_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);

		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_user' => array('type' => 'key', 'fields' => array('user_id', 'user_id_dest', 'user_convers_status', 'last_timestamp'))
		));
		self::$db_utils->create_table(self::$pm_topic_table, $fields, $options);
	}

	private function create_sessions_table()
	{
		$fields = array(
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'session_id' => array('type' => 'string', 'length' => 64, 'default' => "''"),
			'token' => array('type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => "''"),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'ip' => array('type' => 'string', 'length' => 64, 'default' => "''"),
			'location_script' => array('type' => 'string', 'length' => 200, 'notnull' => 1, 'default' => "''"),
			'location_title' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'cached_data' => array('type' => 'text', 'length' => 65000),
			'data' => array('type' => 'text', 'length' => 65000),
			'location_id' => array('type' => 'string', 'length' => 64, 'default' => "''")
		);
		$options = array(
			'primary' => array('session_id'),
			'indexes' => array(
				'user_id' => array('type' => 'key', 'fields' => 'user_id'),
				'timestamp' => array('type' => 'key', 'fields' => 'timestamp')
			)
		);
		self::$db_utils->create_table(self::$sessions_table, $fields, $options);
	}

	private function create_internal_authentication_table()
	{
		$fields = array(
			'user_id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'login' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'password' => array('type' => 'string', 'length' => 64, 'default' => "''"),
			'registration_pass' => array('type' => 'string', 'length' => 30, 'notnull' => 1, 'default' => 0),
			'change_password_pass' => array('type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => "''"),
			'connection_attemps' => array('type' => 'boolean', 'length' => 4, 'notnull' => 1, 'default' => 0),
			'last_connection' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'approved' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0)
		);

		$options = array(
			'primary' => array('user_id'),
			'indexes' => array('login' => array('type' => 'unique', 'fields' => 'login'))
		);
		self::$db_utils->create_table(self::$internal_authentication_table, $fields, $options);
	}

	private function create_internal_authentication_failures_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'session_id' => array('type' => 'string', 'length' => 64, 'default' => "''"),
			'login' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'connection_attemps' => array('type' => 'boolean', 'length' => 4, 'notnull' => 1, 'default' => 0),
			'last_connection' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array('session_id' => array('type' => 'key', 'fields' => 'session_id'))
		);
		self::$db_utils->create_table(self::$internal_authentication_failures_table, $fields, $options);
	}

	private function create_authentication_method_table()
	{
		$fields = array(
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1),
			'method' => array('type' => 'string', 'length' => 32, 'default' => "''"),
			'identifier' => array('type' => 'string', 'length' => 128, 'default' => "''"),
			'data' => array('type' => 'text', 'length' => 65000)
		);

		$options = array(
			'indexes' => array(
				'method' => array('type' => 'unique', 'fields' => array('method', 'identifier'))
		));
		self::$db_utils->create_table(self::$authentication_method_table, $fields, $options);
	}


	private function create_smileys_table()
	{
		$fields = array(
			'idsmiley' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'code_smiley' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'url_smiley' => array('type' => 'string', 'length' => 50, 'default' => "''")
		);
		$options = array(
			'primary' => array('idsmiley')
		);
		self::$db_utils->create_table(self::$smileys_table, $fields, $options);
	}

	private function create_upload_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idcat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 150, 'default' => "''"),
			'path' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'size' => array('type' => 'float', 'length' => 1, 'notnull' => 1),
			'type' => array('type' => 'string', 'length' => 10, 'default' => "''"),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'shared' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		self::$db_utils->create_table(self::$upload_table, $fields, $options);
	}

	private function create_upload_cat_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 150, 'default' => "''")
		);
		$options = array(
			'primary' => array('id')
		);
		self::$db_utils->create_table(self::$upload_cat_table, $fields, $options);
	}

	private function insert_data()
	{
		$this->insert_visit_counter_data();
		$this->insert_smileys_data();
	}

	private function insert_visit_counter_data()
	{
		$now = new Date();

		self::$db_querier->insert(self::$visit_counter_table, array(
			'id' => 1,
			'ip' => 0,
			'time' => $now->format(Date::FORMAT_ISO_DAY_MONTH_YEAR),
			'total' => 0
		));
	}

	private function insert_smileys_data()
	{
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 1,
			'code_smiley' => ':o',
			'url_smiley' => 'wow.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 2,
			'code_smiley' => ':whistle',
			'url_smiley' => 'whistle.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 3,
			'code_smiley' => ':)',
			'url_smiley' => 'smile.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 4,
			'code_smiley' => ':lol',
			'url_smiley' => 'laugh.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 5,
			'code_smiley' => ':p',
			'url_smiley' => 'tongue.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 6,
			'code_smiley' => ':(',
			'url_smiley' => 'sad.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 7,
			'code_smiley' => ';)',
			'url_smiley' => 'wink.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 8,
			'code_smiley' => ':what',
			'url_smiley' => 'what.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 9,
			'code_smiley' => ':D',
			'url_smiley' => 'grin.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 10,
			'code_smiley' => '^^',
			'url_smiley' => 'happy.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 11,
			'code_smiley' => ':|',
			'url_smiley' => 'straight.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 12,
			'code_smiley' => ':gne',
			'url_smiley' => 'gne.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 13,
			'code_smiley' => ':top',
			'url_smiley' => 'top.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 14,
			'code_smiley' => ':party',
			'url_smiley' => 'party.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 15,
			'code_smiley' => ':devil',
			'url_smiley' => 'devil.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 16,
			'code_smiley' => ':@',
			'url_smiley' => 'angry.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 17,
			'code_smiley' => ':\'(',
			'url_smiley' => 'cry.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 18,
			'code_smiley' => ':crazy',
			'url_smiley' => 'crazy.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 19,
			'code_smiley' => ':cool',
			'url_smiley' => 'cool.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 20,
			'code_smiley' => ':night',
			'url_smiley' => 'night.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 21,
			'code_smiley' => ':vomit',
			'url_smiley' => 'vomit.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 22,
			'code_smiley' => ':unhappy',
			'url_smiley' => 'unhappy.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 23,
			'code_smiley' => ':love',
			'url_smiley' => 'love.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 24,
			'code_smiley' => ':hum',
			'url_smiley' => 'confused.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 25,
			'code_smiley' => ':drool',
			'url_smiley' => 'drooling.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 26,
			'code_smiley' => ':cold',
			'url_smiley' => 'cold.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 27,
			'code_smiley' => ':hot',
			'url_smiley' => 'hot.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 28,
			'code_smiley' => ':hi',
			'url_smiley' => 'hello.png'
		));

		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 29,
			'code_smiley' => ':bal',
			'url_smiley' => 'balloon.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 30,
			'code_smiley' => ':bomb',
			'url_smiley' => 'bomb.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 31,
			'code_smiley' => ':brokenheart',
			'url_smiley' => 'brokenheart.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 32,
			'code_smiley' => ':cake',
			'url_smiley' => 'cake.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 33,
			'code_smiley' => ':dead',
			'url_smiley' => 'dead.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 34,
			'code_smiley' => ':drink',
			'url_smiley' => 'drink.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 35,
			'code_smiley' => ':flower',
			'url_smiley' => 'flower.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 36,
			'code_smiley' => ':ghost',
			'url_smiley' => 'ghost.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 37,
			'code_smiley' => ':gift',
			'url_smiley' => 'gift.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 38,
			'code_smiley' => ':girly',
			'url_smiley' => 'girly.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 39,
			'code_smiley' => ':heart',
			'url_smiley' => 'heart.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 40,
			'code_smiley' => ':hug',
			'url_smiley' => 'hug.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 41,
			'code_smiley' => ':idea',
			'url_smiley' => 'idea.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 42,
			'code_smiley' => ':kiss',
			'url_smiley' => 'kiss.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 43,
			'code_smiley' => ':mail',
			'url_smiley' => 'mail.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 44,
			'code_smiley' => ':x',
			'url_smiley' => 'mute.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 46,
			'code_smiley' => ':nerd',
			'url_smiley' => 'nerd.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 47,
			'code_smiley' => ':sick',
			'url_smiley' => 'sick.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 48,
			'code_smiley' => ':boring',
			'url_smiley' => 'boring.png'
		));
		self::$db_querier->insert(self::$smileys_table, array(
			'idsmiley' => 49,
			'code_smiley' => ':zombie',
			'url_smiley' => 'zombie.png'
		));
	}
}
?>
