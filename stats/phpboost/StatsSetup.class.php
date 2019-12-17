<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2013 01 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

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
			),
			'charset' => 'latin1'
		);
		self::$db_utils->create_table(self::$stats_referer_table, $fields, $options);
	}
}
?>
