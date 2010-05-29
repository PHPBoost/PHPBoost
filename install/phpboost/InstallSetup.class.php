<?php
/*##################################################
 *                             InstallSetup.class.php
 *                            -------------------
 *   begin                : May 29, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class InstallSetup extends DefaultModuleSetup
{
	private static $com_table;
	private static $visit_counter_table;
	private static $configs_table;
	private static $events_table;
	private static $errors_404_table;
	private static $group_table;
	private static $lang_table;
	private static $member_table;
	private static $member_extend_table;
	private static $member_extend_cat_table;
	private static $menus_table;
	private static $menu_configuration_table;
	private static $pm_msg_table;
	private static $pm_topic_table;
	private static $ranks_table;
	private static $search_index_table;
	private static $search_results_table;
	private static $sessions_table;
	private static $smileys_table;
	private static $stats_table;
	private static $stats_referer_table;
	private static $themes_table;
	private static $upload_table;
	private static $upload_cat_table;
	private static $verif_code_table;
	
	

	public function __construct()
	{
		self::$com_table = PREFIX . 'com';
		self::$visit_counter_table = PREFIX . 'visit_counter';
		self::$configs_table = PREFIX . 'configs';
		self::$events_table = PREFIX . 'events';
		self::$errors_404_table = PREFIX . 'errors_404';
		self::$group_table = PREFIX . 'group';
		self::$lang_table = PREFIX . 'lang';
		self::$member_table = PREFIX . 'member';
		self::$member_extend_table = PREFIX . 'member_extend';
		self::$member_extend_cat_table = PREFIX . 'member_extend_cat';
		self::$menus_table = PREFIX . 'menus';
		self::$menu_configuration_table = PREFIX . 'menu_configuration';
		self::$pm_msg_table = PREFIX . 'pm_msg';
		self::$pm_topic_table = PREFIX . 'pm_topic';
		self::$ranks_table = PREFIX . 'ranks';
		self::$search_index_table = PREFIX . 'search_index';
		self::$search_results_table = PREFIX . 'search_results';
		self::$sessions_table = PREFIX . 'sessions';
		self::$smileys_table = PREFIX . 'smileys';
		self::$stats_table = PREFIX . 'stats';
		self::$stats_referer_table = PREFIX . 'stats_referer';
		self::$themes_table = PREFIX . 'themes';
		self::$upload_table = PREFIX . 'upload';
		self::$upload_cat_table = PREFIX . 'upload_cat';
		self::$verif_code_table = PREFIX . 'verif_code';
		
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
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(
			self::$com_table, 
			self::$visit_counter_table, 
			self::$configs_table, 
			self::$events_table, 
			self::$errors_404_table, 
			self::$group_table, 
			self::$lang_table,
			self::$member_table,
			self::$member_extend_table,
			self::$member_extend_cat_table,
			self::$menus_table,
			self::$menu_configuration_table,
			self::$pm_msg_table,
			self::$pm_topic_table,
			self::$ranks_table,
			self::$search_index_table,
			self::$search_results_table,
			self::$sessions_table,
			self::$smileys_table,
			self::$stats_table,
			self::$stats_referer_table,
			self::$themes_table,
			self::$upload_table,
			self::$upload_cat_table,
			self::$verif_code_table
		));
	}

	private function create_tables()
	{
		$this->create_com_table();
		$this->create_visit_counter_table();
		$this->create_configs_table();
		$this->create_events_table();
		$this->errors_404_table();
		$this->create_group_table();
		$this->create_lang_table();
		$this->create_member_table();
		$this->create_member_extend_table();
		$this->create_member_extend_cat_table();
		$this->create_menus_table();
		$this->create_menu_configuration_table();
		$this->create_pm_msg_table();
		$this->create_pm_topic_table();
		$this->create_ranks_table();
		$this->create_search_index_table();
		$this->create_search_results_table();
		$this->create_sessions_table();
		$this->create_smileys_table();
		$this->create_stats_table();
		$this->create_stats_referer_table();
		$this->create_themes_table();
		$this->create_upload_table();
		$this->create_upload_cat_table();
		$this->create_verif_code_table();
	}

	private function create_com_table()
	{
		$fields = array(
			'idcom' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idprov' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'login' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'contents' => array('type' => 'text', 'length' => 65000),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'script' => array('type' => 'string', 'length' => 20, 'notnull' => 1, 'default' => "''"),
			'path' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'user_ip' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''")
		);
		$options = array(
			'primary' => array('idcom'),
			'indexes' => array(
				'idprov' => array('type' => 'key', 'fields' => array('idprov', 'script'))
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$com_table, $fields, $options);
	}

	
	private function create_visit_counter_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'ip' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'time' => array('type' => 'date', 'notnull' => 1, 'default' => '0000-00-00'),
			'times' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'ip' => array('type' => 'key', 'fields' => 'ip')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$visit_counter_table, $fields, $options);
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
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$configs_table, $fields, $options);
	}
	
	private function create_events_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'entitled' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'fixing_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'module' => array('type' => 'string', 'length' => 100, 'notnull' => 1),
			'current_status' => array('type' => 'boolean', 'length' => 3, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => "''"),
			'fixing_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => "''"),
			'auth' => array('type' => 'text', 'length' => 65000),
			'poster_id' => array('type' => 'integer', 'length' => 11),
			'fixer_id' => array('type' => 'integer', 'length' => 11),
			'id_in_module' => array('type' => 'integer', 'length' => 11),
			'identifier' => array('type' => 'string', 'length' => 64),
			'contribution_type' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => "1"),
			'type' => array('type' => 'string', 'length' => 64),
			'priority' => array('type' => 'boolean', 'length' => 3, 'notnull' => 1, 'default' => 3),
			'nbr_com' => array('type' => 'integer', 'length' => 10, 'default' => 0),
			'lock_com' => array('type' => 'boolean', 'length' => 1, 'default' => 0)
		  
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'type_index' => array('type' => 'key', 'fields' => 'type'),
				'identifier_index' => array('type' => 'key', 'fields' => 'identifier'),
				'module_index' => array('type' => 'key', 'fields' => 'module'),
				'id_in_module_index' => array('type' => 'key', 'fields' => 'id_in_module')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$events_table, $fields, $options);
	}
	
	private function create_errors_404_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'requested_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'from_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'times' => array('type' => 'integer', 'length' => 11, 'notnull' => 1)
		);

		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'unique' => array('type' => 'unique', 'fields' => array('requested_url', 'from_url'))
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$errors_404_table, $fields, $options);
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
		PersistenceContext::get_dbms_utils()->create_table(self::$group_table, $fields, $options);
	}
	
	private function create_lang_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'lang' => array('type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "''"),
			'activ' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'secure' => array('type' => 'boolean', 'length' => 2, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('user_id'),
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$lang_table, $fields, $options);
	}
	
	private function create_member_table()
	{
		$fields = array(
			'user_id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'login' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'password' => array('type' => 'string', 'length' => 64, 'default' => "''"),
			'level' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'user_groups' => array('type' => 'text', 'length' => 65000),
			'user_lang' => array('type' => 'string', 'length' => 25, 'default' => "''"),
			'user_theme' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'user_mail' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'user_show_mail' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 1),
			'user_editor' => array('type' => 'string', 'length' => 15, 'default' => "''"),
			'user_timezone' => array('type' => 'boolean', 'length' => 2, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_avatar' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'user_msg' => array('type' => 'integer', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'user_local' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'user_msn' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'user_yahoo' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'user_web' => array('type' => 'string', 'length' => 70, 'default' => "''"),
			'user_occupation' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'user_hobbies' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'user_desc' => array('type' => 'text', 'length' => 65000),
			'user_sex' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'user_born' => array('type' => 'date', 'notnull' => 1, 'default' => '0000-00-00'),
			'user_sign' => array('type' => 'text', 'length' => 65000),
			'user_pm' => array('type' => 'integer', 'length' => 6, 'notnull' => 1, 'default' => 0),
			'user_warning' => array('type' => 'integer', 'length' => 6, 'notnull' => 1, 'default' => 0),
			'user_readonly' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_connect' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'test_connect' => array('type' => 'boolean', 'length' => 4, 'notnull' => 1, 'default' => 0),
			'activ_pass' => array('type' => 'string', 'length' => 30, 'notnull' => 1, 'default' => 0),
			'new_pass' => array('type' => 'string', 'length' => 64, 'notnull' => 1, 'default' => "''"),	
			'user_ban' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_aprob' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0)

		);
		
		$options = array(
			'primary' => array('user_id'),
			'indexes' => array(
				'login' => array('type' => 'unique', 'fields' => 'login'),
				'user_id' => array('type' => 'key', 'fields' => array('login','password','level','user_id'))
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$member_table, $fields, $options);
	}
	
	private function create_member_extend_table()
	{
		$fields = array(
			'user_id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
		);
		
		$options = array(
			'primary' => array('user_id'),
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$member_extend_table, $fields, $options);
	}
	
	private function create_member_extend_cat_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'class' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'field_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'field' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'possible_values' => array('type' => 'text', 'length' => 65000),
			'default_values' => array('type' => 'text', 'length' => 65000),
			'required' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'display' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'regex' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
		);
		$options = array(
			'indexes' => array(
				'id' => array('type' => 'unique', 'fields' => 'id')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$member_extend_cat_table, $fields, $options);
	}
	
	private function create_menus_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 128, 'notnull' => 1),
			'object' => array('type' => 'text', 'length' => 65000),
			'class' => array('type' => 'string', 'length' => 67, 'notnull' => 1),
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
		PersistenceContext::get_dbms_utils()->create_table(self::$menus_table, $fields, $options);
	}
	
	private function create_menu_configuration_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 100, 'notnull' => 1),
			'match_regex' => array('type' => 'text', 'length' => 65000),
			'priority' => array('type' => 'integer', 'length' => 11, 'notnull' => 1)
		);
		
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'priority' => array('type' => 'key', 'fields' => 'priority')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$menu_configuration_table, $fields, $options);
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
		PersistenceContext::get_dbms_utils()->create_table(self::$pm_msg_table, $fields, $options);
	}
	
	private function create_pm_topic_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "''"),
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
		PersistenceContext::get_dbms_utils()->create_table(self::$pm_topic_table, $fields, $options);
	}
	
	private function create_ranks_table()
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
		PersistenceContext::get_dbms_utils()->create_table(self::$ranks_table, $fields, $options);
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
		PersistenceContext::get_dbms_utils()->create_table(self::$search_index_table, $fields, $options);
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
		PersistenceContext::get_dbms_utils()->create_table(self::$search_results_table, $fields, $options);
	}
	
	private function create_sessions_table()
	{
		$fields = array(
			'session_id' => array('type' => 'string', 'length' =>64, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'level' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'session_ip' => array('type' => 'string', 'length' =>64, 'default' => "''"),
			'session_time' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'session_script' => array('type' => 'string', 'length' => 100, 'notnull' => 1,'default' => 0),
			'session_script_get' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => 0),
			'session_script_title' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"),
			'session_flag' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'user_theme' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'user_lang' => array('type' => 'string', 'length' => 50, 'notnull' => 1, 'default' => "''"),
			'modules_parameters' => array('type' => 'text', 'length' => 65000),
			'token' => array('type' => 'string', 'length' => 64, 'notnull' => 1)
		);
		$options = array(
			'primary' => array('session_id'),
			'indexes' => array(
				'user_id' => array('type' => 'key', 'fields' => array('user_id', 'session_time'))
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$sessions_table, $fields, $options);
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
		PersistenceContext::get_dbms_utils()->create_table(self::$smileys_table, $fields, $options);
	}
	
	private function create_stats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'stats_year' => array('type' => 'boolean', 'length' => 6, 'notnull' => 1, 'default' => 0),
			'stats_month' => array('type' => 'boolean', 'length' => 4, 'notnull' => 1, 'default' => 0),
			'stats_day' => array('type' => 'boolean', 'length' => 4, 'notnull' => 1, 'default' => 0),
			'nbr' => array('type' => 'boolean', 'length' => 9, 'notnull' => 1, 'default' => 0),
			'pages' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'pages_detail' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'stats_day' => array('type' => 'unique', 'fields' => array('stats_day', 'stats_month', 'stats_year'))
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$stats_table, $fields, $options);
	}
	
	private function create_stats_referer_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'url' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'relative_url' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'total_visit' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'today_visit' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'yesterday_visit' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'nbr_day' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'last_update' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'type' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'url' => array('type' => 'key', 'fields' => array('url', 'relative_url'))
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$stats_referer_table, $fields, $options);
	}
	
	private function create_themes_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'theme' => array('type' => 'string', 'length' => 50, 'default' => "''"),
			'activ' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'secure' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'left_column' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'right_column' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$themes_table, $fields, $options);
	}
	
	private function create_upload_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'idcat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 150, 'default' => "''"),
			'path' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'size' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1),
			'type' => array('type' => 'string', 'length' => 10, 'default' => "''"),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$upload_table, $fields, $options);
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
		PersistenceContext::get_dbms_utils()->create_table(self::$upload_cat_table, $fields, $options);
	}
	
	private function create_verif_code_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_contents' => array('type' => 'integer', 'length' => 15, 'notnull' => 1, 'default' => "''"),
			'code' => array('type' => 'integer', 'length' => 20, 'notnull' => 1, 'default' => "''"),
			'difficulty' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id')
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$verif_code_table, $fields, $options);
	}
	
	private function insert_data()
	{
		$this->insert_menu_configuration_data();
	}

	private function insert_menu_configuration_data()
	{
		PersistenceContext::get_querier()->insert(self::$menu_configuration_table, array(
			'id' => 1,
			'name' => 'default',
			'match_regex' => '`.*`',
			'priority' => 1
		));

	}
}

?>
