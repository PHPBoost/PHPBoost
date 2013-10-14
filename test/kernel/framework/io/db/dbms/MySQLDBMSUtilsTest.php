<?php

class MySQLDBMSUtilsTest extends PHPBoostUnitTestCase
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
		self::$querier->disable_query_translator();

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
		self::$querier->inject("INSERT INTO `" . self::$test_table1 .
			"` (`ip`, `time`, `total`) VALUES (:ip, NOW(), :total)", array(
			'ip' => '127.0.0.1',
			'total' => 42,
		));
		self::$querier->inject("INSERT INTO `" . self::$test_table1 .
			"` (`ip`, `time`, `total`) VALUES (:ip, DATE(:time), :total)", array(
			'ip' => '127.0.0.2',
			'time' => '2003-12-31 01:02:03',
			'total' => 37,
		));
		self::$querier->inject("INSERT INTO `" . self::$test_table1 .
			"` (`ip`, `time`, `total`) VALUES (:ip, CURDATE(), :total)", array(
			'ip' => '127.0.0.3',
			'total' => 1764,
		));

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
		self::assertRegExp('`^MySQL [a-z0-9_.~-]+$`i', self::$dbms_utils->get_dbms_version());
	}

	public function test_get_database_name()
	{
		include PATH_TO_ROOT . '/kernel/db/config.php';
		self::assertEquals($db_connection_data['database'], self::$dbms_utils->get_database_name());
	}

	public function test_list_databases()
	{
		$db_name = self::$dbms_utils->get_database_name();
		$db_list = self::$dbms_utils->list_databases();
		self::assertContains($db_name, $db_list);
	}

	public function test_list_tables()
	{
		$tables = self::$dbms_utils->list_tables();
		self::assertContains(self::$test_table1, $tables);
	}

	public function test_list_and_desc_tables()
	{
		$tables = self::$dbms_utils->list_and_desc_tables();
		self::assertContains(self::$test_table1, array_keys($tables));
	}

	public function test_desc_table()
	{
		$desc = self::$dbms_utils->desc_table(self::$test_table1);
		self::assertContains(array (
			    'name' => 'id',
			    'type' => 'int(11)',
			    'null' => 'NO',
			    'key' => 'PRI',
			    'default' => NULL,
			    'extra' => 'auto_increment',
		), $desc);
		self::assertContains(array (
			    'name' => 'ip',
			    'type' => 'varchar(50)',
			    'null' => 'NO',
			    'key' => 'MUL',
			    'default' => '',
			    'extra' => '',
		), $desc);
		self::assertContains(array (
			    'name' => 'time',
			    'type' => 'date',
			    'null' => 'NO',
			    'key' => '',
			    'default' => '0000-00-00',
			    'extra' => '',
		), $desc);
		self::assertContains(array (
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
		self::assertContains(self::$test_table1, self::$dbms_utils->list_tables());
		self::$dbms_utils->drop(self::$test_table1);
		self::assertNotContains(self::$test_table1, self::$dbms_utils->list_tables());
	}

	public function test_drop_tables()
	{
		$tables_list = self::$dbms_utils->list_tables();
		self::assertContains(self::$test_table1, $tables_list);
		self::assertContains(self::$test_table2, $tables_list);

		self::$dbms_utils->drop(array(self::$test_table1, self::$test_table2));

		$tables_list = self::$dbms_utils->list_tables();
		self::assertNotContains(self::$test_table1, $tables_list);
		self::assertNotContains(self::$test_table2, $tables_list);
	}

	public function test_dump_table()
	{
		$file = new File(PATH_TO_ROOT . '/cache/test.php');
		self::$dbms_utils->dump_table(new BufferedFileWriter($file), self::$test_table1, DBMSUtils::DUMP_STRUCTURE_AND_DATA);
		$file->close();

		$content = $file->read();
		
		$file->delete();

		self::assertEquals(
"DROP TABLE IF EXISTS `phpboost_test_table_1`;
CREATE TABLE `phpboost_test_table_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL DEFAULT '',
  `time` date NOT NULL DEFAULT '0000-00-00',
  `total` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
INSERT INTO `phpboost_test_table_1` (`id`, `ip`, `time`, `total`) VALUES (1,'','',42);
INSERT INTO `phpboost_test_table_1` (`id`, `ip`, `time`, `total`) VALUES (2,'','',37);
INSERT INTO `phpboost_test_table_1` (`id`, `ip`, `time`, `total`) VALUES (3,'','',1764);
",
			$content);
	}

	public function test_create_table()
	{
		$options_sample = array(
            'primary' => array('id'),
            'character_set' => 'utf8',
            'collate' => 'utf8_unicode_ci',
            'foreignKeys' => array(
                'fk_name' => array(
                    'local'   => 'ext_id_fk',
                    'foreign' => 'id',
                    'foreignTable' => 'events',
                ),
                'onDelete' => 'CASCADE',
            )
        );
        $fields_sample = array(
            'id' => array(
                'type' => 'integer',
                'primary' => true,
                'autoincrement' => true
            ),
            'name' => array(
                'type' => 'string',
                'length' => 255,
                'full_text' => true
            ),
            'datetime' => array(
                'version' => false,
                'type' => 'datetime'
            ),
            'ext_id_fk' => array(
                'type' => 'integer',
            ),
        );
		self::$dbms_utils->create_table('test_table42', $fields_sample, $options_sample);
	}
}
