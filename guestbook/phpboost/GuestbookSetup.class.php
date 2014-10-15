<?php
/*##################################################
 *                         GuestbookSetup.class.php
 *                            -------------------
 *   begin                : May 28, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class GuestbookSetup extends DefaultModuleSetup
{
	public static $guestbook_table;
	
	public static function __static()
	{
		self::$guestbook_table = PREFIX . 'guestbook';
	}
	
	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}
	
	public function uninstall()
	{
		$this->drop_tables();
		$this->delete_configuration();
	}
	
	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$guestbook_table));
	}
	
	private function delete_configuration()
	{
		ConfigManager::delete('guestbook', 'config');
	}
	
	private function create_tables()
	{
		$this->create_guestbook_table();
	}
	
	private function create_guestbook_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'contents' => array('type' => 'text', 'length' => 65000),
			'login' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'timestamp' => array('type' => 'key', 'fields' => 'timestamp')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$guestbook_table, $fields, $options);
	}
}
?>