<?php
/*##################################################
 *                       CalendarModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 11, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class CalendarModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('calendar');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (!isset($tables[PREFIX . 'calendar_events']))
			$this->create_calendar_events_table();
		if (!isset($tables[PREFIX . 'calendar_events_content']))
			$this->create_calendar_events_content_table();
		if (!isset($tables[PREFIX . 'calendar_cats']))
			$this->create_calendar_cats_table();
		if (!isset($tables[PREFIX . 'calendar_users_relation']))
			$this->create_calendar_users_relation_table();
		
		if (isset($tables[PREFIX . 'calendar']))
			$this->update_events();
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
		$this->db_utils->create_table(PREFIX . 'calendar_events', $fields, $options);
	}
	
	private function create_calendar_events_content_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 150, 'notnull' => 1),
			'rewrited_title' => array('type' => 'string', 'length' => 250, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'location' => array('type' => 'string', 'length' => 255, 'notnull' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'approved' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'registration_authorized' => array('type' => 'boolean', 'notnull' => 1, 'notnull' => 1, 'default' => 0),
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
		PersistenceContext::get_dbms_utils()->create_table(PREFIX . 'calendar_events_content', $fields, $options);
	}
	
	private function create_calendar_cats_table()
	{
		CalendarCategory::create_categories_table(PREFIX . 'calendar_cats');
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
		$this->db_utils->create_table(PREFIX . 'calendar_users_relation', $fields, $options);
	}
	
	private function update_events()
	{
		$result = $this->querier->select_rows(PREFIX . 'calendar', array('*'));
		while ($row = $result->fetch())
		{
			$insert_result = $this->querier->insert(PREFIX . 'calendar_events_content', array(
				'title' => $row['title'],
				'rewrited_title' => Url::encode_rewrite($row['title']),
				'contents' => $row['contents'],
				'creation_date' => $row['timestamp'] - (24 * 3600),
				'author_id' => $row['user_id'],
				'approved' => 1
			));
			
			$this->querier->insert(PREFIX . 'calendar_events', array(
				'content_id' => $insert_result->get_last_inserted_id(),
				'start_date' => $row['timestamp'],
				'end_date' => $row['timestamp'] + 7200,
				'parent_id' => 0
			));
		}
		
		$this->db_utils->drop(array(PREFIX . 'calendar'));
	}
}
?>