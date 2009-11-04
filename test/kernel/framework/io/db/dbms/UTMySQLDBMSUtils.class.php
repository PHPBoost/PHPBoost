<?php

class UTMySQLDBMSUtils extends PHPBoostUnitTestCase
{
	/**
	 * @var DBMSUtils
	 */
	private static $dbms_utils;
	
	public function setUp()
	{
		$connection = DBFactory::get_db_connection();
		$querier = DBFactory::new_sql_querier($connection);
		self::$dbms_utils = new MySQLDBMSUtils($querier);
	}
	
	public function tearDown()
	{
		self::$dbms_utils = null;
	}
	
//	public function test_list_fields()
//	{
//		global $Sql;
//		
//		$ret = $Sql->list_fields('');
//		$this->assertTrue(is_array($ret) AND empty($ret));
//
//		$ret = $Sql->list_fields('member');
//		$this->assertTrue(is_array($ret) AND empty($ret));
//
//		$ret = $Sql->list_fields(PREFIX.'member');
//		$this->assertTrue(is_array($ret));
//		$this->assertEqual(count($ret), 33);
//	}
//	
	public function test_get_dbms_version()
	{
		$this->assertNotNull(self::$dbms_utils->get_dbms_version());
	}
	
	public function test_get_data_base_name()
	{	
		$this->assertNotNull(self::$dbms_utils->get_database_name());
	}
	
//	public function test_list_databases()
//	{
//		global $Sql;
//		
//		$ret = $Sql->list_databases();
//		$this->assertTrue(is_array($ret));
//	}
//	
//	public function test_optimize_tables()
//	{
//		global $Sql;
//
//		$ret = $Sql->optimize_tables(array(PREFIX.'news'));
//		$this->assertTrue($ret);
//		
//		$ret = $Sql->optimize_tables(array(PREFIX.'news','toto'));
//		$this->assertFalse($ret);
//	}
//	
//	public function test_repair_tables()
//	{
//		global $Sql;
//
//		$ret = $Sql->optimize_tables(array(PREFIX.'news'));
//		$this->assertTrue($ret);
//		
//		$ret = $Sql->optimize_tables(array(PREFIX.'news','toto'));
//		$this->assertFalse($ret);
//	}
//	
//	public function test_truncate_tables()
//	{
//		global $Sql;
//
//		$ret = $Sql->truncate_tables(array(PREFIX.'toto'));
//		$this->assertFalse($ret);
//	}
//	
//	public function test_drop_tables()
//	{
//		global $Sql;
//
//		$ret = $Sql->drop_tables(array('toto'));
//		$this->assertFalse($ret);
//	}
}
