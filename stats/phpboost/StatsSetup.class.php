<?php
/*##################################################
 *                             StatsSetup.class.php
 *                            -------------------
 *   begin                : January 06, 2013
 *   copyright            : (C) 2013 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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

class StatsSetup extends DefaultModuleSetup
{
	private static $db_utils;
	public static $stats_table;		
	public static $stats_referer_table;
	
	public static function __static()
	{
		self::$db_utils = PersistenceContext::get_dbms_utils();
		self::$stats_table = PREFIX . 'stats';
		self::$stats_referer_table = PREFIX . 'stats_referer';
	}
	
	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}

	public function uninstall()
	{
		$this->drop_tables();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$stats_table, self::$stats_referer_table,));
	}

	private function create_tables()
	{
		$this->create_stats_table();
		$this->create_stats_referer_table();
	}

	private function create_stats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'stats_year' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'stats_month' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'stats_day' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'nbr' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'pages' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'pages_detail' => array('type' => 'text', 'length' => 65000)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'stats_day' => array('type' => 'unique', 'fields' => array('stats_day', 'stats_month', 'stats_year'))
			)
		);
		self::$db_utils->create_table(self::$stats_table, $fields, $options);
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
		self::$db_utils->create_table(self::$stats_referer_table, $fields, $options);
	}
}
?>