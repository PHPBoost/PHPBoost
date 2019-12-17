<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 04 04
 * @since       PHPBoost 3.0 - 2010 01 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarSetup extends DefaultModuleSetup
{
	public static $calendar_events_table;
	public static $calendar_events_content_table;
	public static $calendar_cats_table;
	public static $calendar_users_relation_table;

	public static function __static()
	{
		self::$calendar_events_table = PREFIX . 'calendar_events';
		self::$calendar_events_content_table = PREFIX . 'calendar_events_content';
		self::$calendar_cats_table = PREFIX . 'calendar_cats';
		self::$calendar_users_relation_table = PREFIX . 'calendar_users_relation';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}

	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('calendar', 'config');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$calendar_events_table, self::$calendar_events_content_table, self::$calendar_cats_table, self::$calendar_users_relation_table));
	}

	private function create_tables()
	{
		$this->create_calendar_events_table();
		$this->create_calendar_events_content_table();
		$this->create_calendar_cats_table();
		$this->create_calendar_users_relation_table();
	}

	private function create_calendar_events_table()
	{
		$fields = array(
			'id_event' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'content_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'parent_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id_event'),
			'indexes' => array(
				'content_id' => array('type' => 'key', 'fields' => 'content_id'),
				'parent_id' => array('type' => 'key', 'fields' => 'parent_id')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$calendar_events_table, $fields, $options);
	}

	private function create_calendar_events_content_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'picture_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'title' => array('type' => 'string', 'length' => 150, 'notnull' => 1, 'default' => "''"),
			'rewrited_title' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'location' => array('type' => 'text', 'length' => 65000),
			'map_displayed' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'cancelled' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'approved' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'registration_authorized' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'max_registered_members' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => -1),
			'last_registration_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'register_authorizations' => array('type' => 'text', 'length' => 65000),
			'repeat_number' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'repeat_type' => array('type' => 'string', 'length' => 25, 'notnull' => 1, 'default' => '\'' . CalendarEventContent::NEVER . '\'')
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$calendar_events_content_table, $fields, $options);
	}

	private function create_calendar_cats_table()
	{
		CalendarCategory::create_categories_table(self::$calendar_cats_table);
	}

	private function create_calendar_users_relation_table()
	{
		$fields = array(
			'event_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'indexes' => array(
				'event_id' => array('type' => 'key', 'fields' => 'event_id'),
				'user_id' => array('type' => 'key', 'fields' => 'user_id')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$calendar_users_relation_table, $fields, $options);
	}
}
?>
