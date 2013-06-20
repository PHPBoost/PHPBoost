<?php
/*##################################################
 *                          CalendarSetup.class.php
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

class CalendarSetup extends DefaultModuleSetup
{
	public static $calendar_table;
	public static $calendar_cats_table;
	public static $calendar_users_relation_table;

	public static function __static()
	{
		self::$calendar_table = PREFIX . 'calendar';
		self::$calendar_cats_table = PREFIX . 'calendar_cats';
		self::$calendar_users_relation_table = PREFIX . 'calendar_users_relation';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}
	
	public function upgrade($installed_version)
	{
		PersistenceContext::get_querier()->inject('ALTER TABLE '. self::$calendar_table .' CHANGE timestamp start_date INT(11) NOT NULL DEFAULT \'0\'');
		PersistenceContext::get_querier()->inject('ALTER TABLE '. self::$calendar_table .' CHANGE user_id author_id INT(11) NOT NULL DEFAULT \'0\'');
		PersistenceContext::get_dbms_utils()->add_column(self::$calendar_table, 'location', array('type' => 'string', 'length' => 255, 'notnull' => 0));
		PersistenceContext::get_dbms_utils()->add_column(self::$calendar_table, 'end_date', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		PersistenceContext::get_dbms_utils()->add_column(self::$calendar_table, 'category_id', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		PersistenceContext::get_dbms_utils()->add_column(self::$calendar_table, 'registration_authorized', array('type' => 'boolean', 'notnull' => 1, 'notnull' => 1, 'default' => 0));
		PersistenceContext::get_dbms_utils()->add_column(self::$calendar_table, 'repeat', array('type' => 'string', 'length' => 25, 'notnull' => 1, 'default' => "'none'"));
		PersistenceContext::get_dbms_utils()->add_column(self::$calendar_table, 'repeat_number', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		$this->create_calendar_cats_table();
		$this->create_calendar_users_relation_table();
		return '4.1.0';
	}
	
	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('calendar', 'config');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$calendar_table, self::$calendar_cats_table, self::$calendar_users_relation_table));
	}
	
	private function create_tables()
	{
		$this->create_calendar_table();
		$this->create_calendar_cats_table();
		$this->create_calendar_users_relation_table();
	}
	
	private function create_calendar_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 150, 'notnull' => 1),
			'contents' => array('type' => 'text', 'length' => 65000),
			'location' => array('type' => 'string', 'length' => 255, 'notnull' => 0),
			'start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'registration_authorized' => array('type' => 'boolean', 'notnull' => 1, 'notnull' => 1, 'default' => 0),
			'max_registred_members' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => -1),
			'repeat_number' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'repeat_type' => array('type' => 'string', 'length' => 25, 'notnull' => 1, 'default' => "'never'"),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$calendar_table, $fields, $options);
	}
	
	private function create_calendar_cats_table()
	{
		CalendarRichCategory::create_categories_table(self::$calendar_cats_table);
	}
	
	private function create_calendar_users_relation_table()
	{
		$fields = array(
			'event_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$calendar_users_relation_table, $fields);
	}
}
?>
