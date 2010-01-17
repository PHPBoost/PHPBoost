<?php
/*##################################################
 *                          CalendarSetup.class.php
 *                            -------------------
 *   begin                : January 17, 2009
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
	private static $calendar_table;

	public function __construct()
	{
		self::$calendar_table = PREFIX . 'calendar';
	}

	public function install()
	{
		$this->drop_table();
		$this->create_table();
	}

	public function uninstall()
	{
		$this->drop_table();
	}

	private function drop_table()
	{
		AppContext::get_dbms_utils()->drop(self::$calendar_table);
	}

	private function create_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'title' => array('type' => 'string', 'length' => 150, 'notnull' => 1),
			'contents' => array('type' => 'text', 'length' => 65000),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'nbr_com' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0, 'unsigned' => 1),
			'lock_com' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array('primary' => array('id'));
		AppContext::get_dbms_utils()->create_table(self::$calendar_table, $fields, $options);
	}
}

?>
