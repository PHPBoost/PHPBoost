<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 11 24
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
		self::$db_utils->drop([
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
		]);
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
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'id_topic' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'message' => ['type' => 'text', 'length' => 65000],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'pseudo' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'visitor_email' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'user_ip' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'note' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'timestamp' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
        ];
		$options = [
			'primary' => ['id'],
        ];
		self::$db_utils->create_table(self::$comments_table, $fields, $options);
	}

	private function create_comments_topic_table()
	{
		$fields = [
			'id_topic' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'module_id' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'topic_identifier' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "'default'"],
			'id_in_module' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'is_locked' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'comments_number' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'path' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"]
        ];
		$options = [
			'primary' => ['id_topic'],
        ];
		self::$db_utils->create_table(self::$comments_topic_table, $fields, $options);
	}

	private function create_note_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'module_name' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'id_in_module' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'note' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0],
		];
		$options = [
			'primary' => ['id'],
		];
		self::$db_utils->create_table(self::$note_table, $fields, $options);
	}

	private function create_average_notes_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'module_name' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'id_in_module' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'average_notes' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0],
			'notes_number' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => 0],
		];
		$options = [
			'primary' => ['id'],
		];
		self::$db_utils->create_table(self::$average_notes_table, $fields, $options);
	}

	private function create_visit_counter_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'ip' => ['type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"],
			'time' => ['type' => 'date', 'notnull' => 1],
			'total' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'ip' => ['type' => 'key', 'fields' => 'ip']
            ]
        ];
		self::$db_utils->create_table(self::$visit_counter_table, $fields, $options);
	}

	private function create_configs_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'name' => ['type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "''"],
			'value' => ['type' => 'text', 'length' => 65000]
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'name' => ['type' => 'unique', 'fields' => 'name']
			]
		];
		self::$db_utils->create_table(self::$configs_table, $fields, $options);
	}

	private function create_events_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'entitled' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'description' => ['type' => 'text', 'length' => 65000],
			'fixing_url' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'module' => ['type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"],
			'current_status' => ['type' => 'boolean', 'length' => 3, 'notnull' => 1, 'default' => 0],
			'creation_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'fixing_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'auth' => ['type' => 'text', 'length' => 65000],
			'poster_id' => ['type' => 'integer', 'length' => 11],
			'fixer_id' => ['type' => 'integer', 'length' => 11],
			'id_in_module' => ['type' => 'integer', 'length' => 11],
			'identifier' => ['type' => 'string', 'length' => 64],
			'contribution_type' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => "1"],
			'type' => ['type' => 'string', 'length' => 64],
			'priority' => ['type' => 'boolean', 'length' => 3, 'notnull' => 1, 'default' => 3]
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'type_index' => ['type' => 'key', 'fields' => 'type'],
				'identifier_index' => ['type' => 'key', 'fields' => 'identifier'],
				'module_index' => ['type' => 'key', 'fields' => 'module'],
				'id_in_module_index' => ['type' => 'key', 'fields' => 'id_in_module']
            ]
        ];
		self::$db_utils->create_table(self::$events_table, $fields, $options);
	}

	private function create_errors_404_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 10, 'autoincrement' => true, 'notnull' => 1],
			'requested_url' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'from_url' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'times' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
		];

		$options = [
			'primary' => ['id'],
			'indexes' => [
				'unique' => ['type' => 'unique', 'fields' => ['requested_url', 'from_url']]
			],
			'charset' => 'latin1'
        ];
		self::$db_utils->create_table(self::$errors_404_table, $fields, $options);
	}

	private function create_group_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'name' => ['type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"],
			'img' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'color' => ['type' => 'string', 'length' => 6, 'notnull' => 1, 'default' => "''"],
			'auth' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'members' => ['type' => 'text', 'length' => 65000]
        ];
		$options = [
			'primary' => ['id'],
		];
		self::$db_utils->create_table(self::$group_table, $fields, $options);
	}

	private function create_keywords_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'name' => ['type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"],
			'rewrited_name' => ['type' => 'string', 'length' => 250, 'default' => "''"],
		];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'name' => ['type' => 'unique', 'fields' => 'name'],
				'rewrited_name' => ['type' => 'unique', 'fields' => 'rewrited_name']
            ]
        ];
		self::$db_utils->create_table(self::$keywords_table, $fields, $options);
	}

	private function create_keywords_relations_table()
	{
		$fields = [
			'id_in_module' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'module_id' => ['type' => 'string', 'length' => 25, 'default' => "''"],
			'id_keyword' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
		];
		self::$db_utils->create_table(self::$keywords_relations_table, $fields);
	}

	private function create_member_table()
	{
		$fields = [
			'user_id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'display_name' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'level' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'email' => ['type' => 'string', 'length' => 50, 'default' => "''"],
			'show_email' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 1],
			'locale' => ['type' => 'string', 'length' => 25, 'default' => "''"],
			'theme' => ['type' => 'string', 'length' => 50, 'default' => "''"],
			'timezone' => ['type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"],
			'editor' => ['type' => 'string', 'length' => 15, 'default' => "''"],
			'unread_pm' => ['type' => 'integer', 'length' => 6, 'notnull' => 1, 'default' => 0],
			'posted_msg' => ['type' => 'integer', 'length' => 6, 'notnull' => 1, 'default' => 0],
			'registration_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'last_connection_date' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'user_groups' => ['type' => 'text', 'length' => 65000],
			'warning_percentage' => ['type' => 'integer', 'length' => 6, 'notnull' => 1, 'default' => 0],
			'delay_banned' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'delay_readonly' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'autoconnect_key' => ['type' => 'string', 'length' => 64, 'default' => "''"]
        ];

		$options = [
            'primary' => ['user_id'],
            'indexes' => [
                'display_name' => ['type' => 'unique', 'fields' => 'display_name'],
                'email' => ['type' => 'unique', 'fields' => 'email']
            ]
        ];
		self::$db_utils->create_table(self::$member_table, $fields, $options);
	}

	private function create_member_extended_fields_table()
	{
		$fields = [
			'user_id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
        ];

		$options = [
			'primary' => ['user_id'],
        ];
		self::$db_utils->create_table(self::$member_extended_fields_table, $fields, $options);
	}

	private function create_member_extended_fields_list_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'position' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0, 'default' => 0],
			'name' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'field_name' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'description' => ['type' => 'text', 'length' => 65000],
			'field_type' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'possible_values' => ['type' => 'text', 'length' => 65000],
			'default_value' => ['type' => 'text', 'length' => 65000],
			'required' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'display' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'regex' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'freeze' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'auth' => ['type' => 'text', 'length' => 65000]
        ];
		$options = [
			'indexes' => [
				'id' => ['type' => 'unique', 'fields' => 'id']
            ]
        ];
		self::$db_utils->create_table(self::$member_extended_fields_list, $fields, $options);
	}

	private function create_menus_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'title' => ['type' => 'string', 'length' => 128, 'notnull' => 1, 'default' => "''"],
			'object' => ['type' => 'text', 'length' => 16777215],
			'class' => ['type' => 'string', 'length' => 67, 'notnull' => 1, 'default' => "''"],
			'enabled' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'block' => ['type' => 'boolean', 'length' => 2, 'notnull' => 1, 'default' => 0],
			'position' => ['type' => 'boolean', 'length' => 2, 'notnull' => 1, 'default' => 0]
        ];
		$options = [
			'primary' => ['id'],
			'indexes' => [
				'block' => ['type' => 'key', 'fields' => 'block'],
				'class' => ['type' => 'key', 'fields' => 'class'],
				'enabled' => ['type' => 'key', 'fields' => 'enabled']
            ]
        ];
		self::$db_utils->create_table(self::$menus_table, $fields, $options);
	}

	private function create_pm_msg_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'idconvers' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'contents' => ['type' => 'text', 'length' => 65000],
			'timestamp' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'view_status' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0]
        ];

		$options = [
			'primary' => ['id'],
			'indexes' => [
				'idconvers' => ['type' => 'key', 'fields' => ['idconvers', 'user_id', 'timestamp']]
            ]
        ];
		self::$db_utils->create_table(self::$pm_msg_table, $fields, $options);
	}

	private function create_pm_topic_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'title' => ['type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "''"],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'user_id_dest' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'user_convers_status' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0],
			'user_view_pm' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'nbr_msg' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'last_user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'last_msg_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'last_timestamp' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
        ];

		$options = [
			'primary' => ['id'],
			'indexes' => [
				'id_user' => ['type' => 'key', 'fields' => ['user_id', 'user_id_dest', 'user_convers_status', 'last_timestamp']]
            ]
        ];
		self::$db_utils->create_table(self::$pm_topic_table, $fields, $options);
	}

	private function create_sessions_table()
	{
		$fields = [
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'session_id' => ['type' => 'string', 'length' => 64, 'default' => "''"],
			'token' => ['type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => "''"],
			'timestamp' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'ip' => ['type' => 'string', 'length' => 64, 'default' => "''"],
			'location_script' => ['type' => 'string', 'length' => 200, 'notnull' => 1, 'default' => "''"],
			'location_title' => ['type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"],
			'cached_data' => ['type' => 'text', 'length' => 65000],
			'data' => ['type' => 'text', 'length' => 65000],
			'location_id' => ['type' => 'string', 'length' => 64, 'default' => "''"]
        ];
		$options = [
			'primary' => ['session_id'],
			'indexes' => [
				'user_id' => ['type' => 'key', 'fields' => 'user_id'],
				'timestamp' => ['type' => 'key', 'fields' => 'timestamp']
            ]
        ];
		self::$db_utils->create_table(self::$sessions_table, $fields, $options);
	}

	private function create_internal_authentication_table()
	{
		$fields = [
			'user_id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'login' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'password' => ['type' => 'string', 'length' => 64, 'default' => "''"],
			'registration_pass' => ['type' => 'string', 'length' => 30, 'notnull' => 1, 'default' => 0],
			'change_password_pass' => ['type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => "''"],
			'connection_attemps' => ['type' => 'boolean', 'length' => 4, 'notnull' => 1, 'default' => 0],
			'last_connection' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'approved' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0]
        ];

		$options = [
			'primary' => ['user_id'],
			'indexes' => ['login' => ['type' => 'unique', 'fields' => 'login']]
        ];
		self::$db_utils->create_table(self::$internal_authentication_table, $fields, $options);
	}

	private function create_internal_authentication_failures_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'session_id' => ['type' => 'string', 'length' => 64, 'default' => "''"],
			'login' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'connection_attemps' => ['type' => 'boolean', 'length' => 4, 'notnull' => 1, 'default' => 0],
			'last_connection' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0]
        ];
		$options = [
			'primary' => ['id'],
			'indexes' => ['session_id' => ['type' => 'key', 'fields' => 'session_id']]
        ];
		self::$db_utils->create_table(self::$internal_authentication_failures_table, $fields, $options);
	}

	private function create_authentication_method_table()
	{
		$fields = [
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1],
			'method' => ['type' => 'string', 'length' => 32, 'default' => "''"],
			'identifier' => ['type' => 'string', 'length' => 128, 'default' => "''"],
			'data' => ['type' => 'text', 'length' => 65000]
        ];

		$options = [
			'indexes' => [
				'method' => ['type' => 'unique', 'fields' => ['method', 'identifier']]
            ]
        ];
		self::$db_utils->create_table(self::$authentication_method_table, $fields, $options);
	}


	private function create_smileys_table()
	{
		$fields = [
			'idsmiley' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'code_smiley' => ['type' => 'string', 'length' => 50, 'default' => "''"],
			'url_smiley' => ['type' => 'string', 'length' => 50, 'default' => "''"]
        ];
		$options = [
			'primary' => ['idsmiley']
        ];
		self::$db_utils->create_table(self::$smileys_table, $fields, $options);
	}

	private function create_upload_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'idcat' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'name' => ['type' => 'string', 'length' => 150, 'default' => "''"],
			'path' => ['type' => 'string', 'length' => 255, 'default' => "''"],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'size' => ['type' => 'float', 'length' => 1, 'notnull' => 1],
			'type' => ['type' => 'string', 'length' => 10, 'default' => "''"],
			'timestamp' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'shared' => ['type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0]
        ];
		$options = [
			'primary' => ['id']
        ];
		self::$db_utils->create_table(self::$upload_table, $fields, $options);
	}

	private function create_upload_cat_table()
	{
		$fields = [
			'id' => ['type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1],
			'id_parent' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'user_id' => ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0],
			'name' => ['type' => 'string', 'length' => 150, 'default' => "''"]
        ];
		$options = [
			'primary' => ['id']
        ];
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

		self::$db_querier->insert(self::$visit_counter_table, [
			'id' => 1,
			'ip' => 0,
			'time' => $now->format(Date::FORMAT_ISO_DAY_MONTH_YEAR),
			'total' => 0
		]);
	}

	private function insert_smileys_data()
	{
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 1,
			'code_smiley' => ':o',
			'url_smiley' => 'wow.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 2,
			'code_smiley' => ':whistle',
			'url_smiley' => 'whistle.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 3,
			'code_smiley' => ':)',
			'url_smiley' => 'smile.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 4,
			'code_smiley' => ':lol',
			'url_smiley' => 'laugh.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 5,
			'code_smiley' => ':p',
			'url_smiley' => 'tongue.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 6,
			'code_smiley' => ':(',
			'url_smiley' => 'sad.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 7,
			'code_smiley' => ';)',
			'url_smiley' => 'wink.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 8,
			'code_smiley' => ':what',
			'url_smiley' => 'what.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 9,
			'code_smiley' => ':D',
			'url_smiley' => 'grin.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 10,
			'code_smiley' => '^^',
			'url_smiley' => 'happy.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 11,
			'code_smiley' => ':|',
			'url_smiley' => 'straight.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 12,
			'code_smiley' => ':gne',
			'url_smiley' => 'gne.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 13,
			'code_smiley' => ':top',
			'url_smiley' => 'top.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 14,
			'code_smiley' => ':party',
			'url_smiley' => 'party.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 15,
			'code_smiley' => ':devil',
			'url_smiley' => 'devil.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 16,
			'code_smiley' => ':@',
			'url_smiley' => 'angry.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 17,
			'code_smiley' => ':\'(',
			'url_smiley' => 'cry.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 18,
			'code_smiley' => ':crazy',
			'url_smiley' => 'crazy.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 19,
			'code_smiley' => ':cool',
			'url_smiley' => 'cool.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 20,
			'code_smiley' => ':night',
			'url_smiley' => 'night.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 21,
			'code_smiley' => ':vomit',
			'url_smiley' => 'vomit.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 22,
			'code_smiley' => ':unhappy',
			'url_smiley' => 'unhappy.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 23,
			'code_smiley' => ':love',
			'url_smiley' => 'love.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 24,
			'code_smiley' => ':hum',
			'url_smiley' => 'confused.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 25,
			'code_smiley' => ':drool',
			'url_smiley' => 'drooling.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 26,
			'code_smiley' => ':cold',
			'url_smiley' => 'cold.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 27,
			'code_smiley' => ':hot',
			'url_smiley' => 'hot.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 28,
			'code_smiley' => ':hi',
			'url_smiley' => 'hello.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 29,
			'code_smiley' => ':bal',
			'url_smiley' => 'balloon.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 30,
			'code_smiley' => ':bomb',
			'url_smiley' => 'bomb.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 31,
			'code_smiley' => ':brokenheart',
			'url_smiley' => 'brokenheart.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 32,
			'code_smiley' => ':cake',
			'url_smiley' => 'cake.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 33,
			'code_smiley' => ':dead',
			'url_smiley' => 'dead.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 34,
			'code_smiley' => ':drink',
			'url_smiley' => 'drink.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 35,
			'code_smiley' => ':flower',
			'url_smiley' => 'flower.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 36,
			'code_smiley' => ':ghost',
			'url_smiley' => 'ghost.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 37,
			'code_smiley' => ':gift',
			'url_smiley' => 'gift.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 38,
			'code_smiley' => ':girly',
			'url_smiley' => 'girly.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 39,
			'code_smiley' => ':heart',
			'url_smiley' => 'heart.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 40,
			'code_smiley' => ':hug',
			'url_smiley' => 'hug.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 41,
			'code_smiley' => ':idea',
			'url_smiley' => 'idea.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 42,
			'code_smiley' => ':kiss',
			'url_smiley' => 'kiss.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 43,
			'code_smiley' => ':mail',
			'url_smiley' => 'mail.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 44,
			'code_smiley' => ':x',
			'url_smiley' => 'mute.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 46,
			'code_smiley' => ':nerd',
			'url_smiley' => 'nerd.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 47,
			'code_smiley' => ':sick',
			'url_smiley' => 'sick.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 48,
			'code_smiley' => ':boring',
			'url_smiley' => 'boring.png'
		]);
		self::$db_querier->insert(self::$smileys_table, [
			'idsmiley' => 49,
			'code_smiley' => ':zombie',
			'url_smiley' => 'zombie.png'
		]);
	}
}
?>
