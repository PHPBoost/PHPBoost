<?php

class UTMySQLDBMSUtils extends PHPBoostUnitTestCase
{
	private static $test_table1;
	private static $test_table2;

	/**
	 * @var DBMSUtils
	 */
	private static $dbms_utils;

	/**
	 * @var SQLQuerier
	 */
	private static $querier;

	public function setUp()
	{
		self::$test_table1 = PREFIX . 'test_table_1';
		self::$test_table2 = PREFIX . 'test_table_2';

		$connection = DBFactory::get_db_connection();
		self::$querier = DBFactory::new_sql_querier($connection);
		self::$dbms_utils = new MySQLDBMSUtils(self::$querier);

		self::$querier->inject("DROP TABLE IF EXISTS `" . self::$test_table1 . "`");
		self::$querier->inject("CREATE TABLE `" . self::$test_table1 . "` (
		  `id` int(11) NOT NULL auto_increment,
		  `ip` varchar(50) NOT NULL default '',
		  `time` date NOT NULL default '0000-00-00',
		  `total` int(11) NOT NULL default '0',
		  PRIMARY KEY  (`id`),
		  KEY `ip` (`ip`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;");

		self::$querier->inject("DROP TABLE IF EXISTS `" . self::$test_table2 . "`");
		self::$querier->inject("CREATE TABLE `" . self::$test_table2 . "` (
		  `pk` int(11) NOT NULL auto_increment,
		  `name` varchar(50) NOT NULL default '',
		  PRIMARY KEY  (`pk`)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
	}

	public function tearDown()
	{
		self::$dbms_utils = null;
		self::$querier->inject("DROP TABLE IF EXISTS `" . self::$test_table1 . "`");
		self::$querier->inject("DROP TABLE IF EXISTS `" . self::$test_table2 . "`");
	}

	public function test_get_dbms_version()
	{
		$this->assertRegExp('`^MySQL [a-z0-9_.~-]+$`i', self::$dbms_utils->get_dbms_version());
	}

	public function test_get_database_name()
	{
		include PATH_TO_ROOT . '/kernel/db/config.php';
		$this->assertEquals($db_connection_data['database'], self::$dbms_utils->get_database_name());
	}

	public function test_list_databases()
	{
		$db_name = self::$dbms_utils->get_database_name();
		$db_list = self::$dbms_utils->list_databases();
		$this->assertContains($db_name, $db_list);
	}

	public function test_list_tables()
	{
		$tables = self::$dbms_utils->list_tables();
		$this->assertContains(self::$test_table1, $tables);
	}

	public function test_desc_table()
	{
		$desc = self::$dbms_utils->desc_table(self::$test_table1);
		$this->assertContains(array (
		    'name' => 'id',
		    'type' => 'int(11)',
		    'null' => 'NO',
		    'key' => 'PRI',
		    'default' => NULL,
		    'extra' => 'auto_increment',
		), $desc);
		$this->assertContains(array (
		    'name' => 'ip',
		    'type' => 'varchar(50)',
		    'null' => 'NO',
		    'key' => 'MUL',
		    'default' => '',
		    'extra' => '',
		), $desc);
		$this->assertContains(array (
		    'name' => 'time',
		    'type' => 'date',
		    'null' => 'NO',
		    'key' => '',
		    'default' => '0000-00-00',
		    'extra' => '',
		), $desc);
		$this->assertContains(array (
		    'name' => 'total',
		    'type' => 'int(11)',
		    'null' => 'NO',
		    'key' => '',
		    'default' => '0',
		    'extra' => '',
		), $desc);
	}

	public function test_optimize_table()
	{
		self::$dbms_utils->optimize(self::$test_table1);
	}

	public function test_optimize_tables()
	{
		self::$dbms_utils->optimize(array(self::$test_table1, self::$test_table2));
	}

	public function test_repair_table()
	{
		self::$dbms_utils->repair(self::$test_table1);
	}

	public function test_repair_tables()
	{
		self::$dbms_utils->repair(array(self::$test_table1, self::$test_table2));
	}

	public function test_truncate_table()
	{
		self::$dbms_utils->truncate(self::$test_table1);
	}

	public function test_truncate_tables()
	{
		self::$dbms_utils->truncate(array(self::$test_table1, self::$test_table2));
	}

	public function test_drop_table()
	{
		$this->assertContains(self::$test_table1, self::$dbms_utils->list_tables());
		self::$dbms_utils->drop(self::$test_table1);
		$this->assertNotContains(self::$test_table1, self::$dbms_utils->list_tables());
	}

	public function test_drop_tables()
	{
		$tables_list = self::$dbms_utils->list_tables();
		$this->assertContains(self::$test_table1, $tables_list);
		$this->assertContains(self::$test_table2, $tables_list);

		self::$dbms_utils->drop(array(self::$test_table1, self::$test_table2));

		$tables_list = self::$dbms_utils->list_tables();
		$this->assertNotContains(self::$test_table1, $tables_list);
		$this->assertNotContains(self::$test_table2, $tables_list);
	}

}
