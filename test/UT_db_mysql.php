<?php
require_once 'header.php';

//include_once(PATH_TO_ROOT . '/kernel/db/config.php');

define('CLASS_IMPORT', '.class.php');
define('INC_IMPORT', '.inc.php');
define('LIB_IMPORT', '.lib.php');

function import($path, $import_type = CLASS_IMPORT)
{
	require_once(PATH_TO_ROOT . '/kernel/framework/' . $path . $import_type);
}

import('db/mysql');

unset($Errorh);
unset($Sql);

class Sql_mock extends Sql
{
	function _error($query, $errstr, $errline = '', $errfile = '')
	{
		echo '<br />'.$query.'-'.$errstr.'-'.$errline.'-'.basename($errfile).'<br />';
	}
}

$Sql = new Sql_mock();
//$Sql->auto_connect();

class UnitTest_mysql extends MY_UnitTestCase {

	function test()
	{
		global $Sql;
		
		$this->MY_check_methods($Sql);
	}
	
	function test_Sql()
	{
		// DO NOTHING
	}

	function test_connect()
	{
		global $Sql;
		
		$ret = $Sql->connect('', '', '', '', false);
		$this->assertEqual($ret, 1);

		$ret = $Sql->connect('', '', '', '', true);
		$this->assertEqual($ret, 1);

		$ret = $Sql->connect('localhost', 'root', '', false);
		$this->assertEqual($ret, 2);

		$ret = $Sql->connect('localhost', 'root', '', 'phpboost', false);
		$this->assertEqual($ret, 3);
	}
	
	function test_auto_connect()
	{
		$sql = new Sql_mock();
		$ret = $sql->auto_connect();
		$this->assertEqual($ret, 3);
	}
	
	function test_query()
	{
		global $Sql;
		
		$ret = $Sql->query('SELECT TOTO', __LINE__, __FILE__);
		$this->assertEqual($ret, null);

		$ret = $Sql->query('SELECT * FROM '.PREFIX.'member', __LINE__, __FILE__);
		$this->assertTrue($ret);
	}

	function test_query_array()
	{
		global $Sql;
		
		$ret = $Sql->query_array('members', '*', __LINE__, __FILE__);
		$this->assertEqual($ret, null);

		$ret = $Sql->query_array(PREFIX.'member', '*', __LINE__, __FILE__);
		$this->assertTrue(is_array($ret));
	}
	
	function test_query_inject()
	{
		global $Sql;
		
		$ret = $Sql->query_inject('INSERT INTO members', __LINE__, __FILE__);
		$this->assertEqual($ret, null);

		$ret = $Sql->query_inject("INSERT INTO ".PREFIX."member SET login = 'toto'", __LINE__, __FILE__);
		$this->assertEqual($ret, true);
		
		$ret = $Sql->query_inject("UPDATE INTO Erreur de syntaxe SET login = 'titi' WHERE login='toto'", __LINE__, __FILE__);
		$this->assertEqual($ret, null);

		$ret = $Sql->query_inject("UPDATE ".PREFIX."member SET login = 'titi' WHERE login='toto'", __LINE__, __FILE__);
		$this->assertEqual($ret, true);
		$rows = $Sql->affected_rows('resource', 'Bidon');
		$this->assertTrue(is_int($rows) AND ($rows>0));
		
		$ret = $Sql->query_inject("DELETE INTO Erreur de syntaxe SET login = 'titi' WHERE login='toto'", __LINE__, __FILE__);
		$this->assertEqual($ret, null);

		$ret = $Sql->query_inject("DELETE FROM ".PREFIX."member WHERE login='titi'", __LINE__, __FILE__);
		$this->assertEqual($ret, true);
		
		$ret = $Sql->query_inject("ALTER TABLE ".PREFIX."member AUTO_INCREMENT=3", __LINE__, __FILE__);
		$this->assertEqual($ret, true);
	}
	
	function test_query_close()
	{
		global $Sql;

		$ret = $Sql->query_while("SELECT * FROM ".PREFIX."member", __LINE__, __FILE__);
		$this->assertTrue(is_resource($ret));

		$tmp = $Sql->query_close($ret);
		$this->assertTrue($tmp);
	}
	
	function test_insert_id()
	{
		global $Sql;

		$ret = $Sql->query_inject("INSERT INTO ".PREFIX."member SET login = 'toto'", __LINE__, __FILE__);
		$this->assertEqual($ret, true);
		
		$id = $Sql->insert_id($ret);
		$this->assertTrue(is_int($id));
	}
	
	function test_affected_rows()
	{
		global $Sql;

		$ret = $Sql->query_inject("INSERT INTO ".PREFIX."member SET login = 'toto'", __LINE__, __FILE__);
		$this->assertEqual($ret, true);
		
		$nb = $Sql->affected_rows($ret);
		$this->assertEqual($nb, 1);
	}
	
	function test_query_while()
	{
		global $Sql;
		
		$ret = $Sql->query_while('SELECT * FROM table_inexsistante', __LINE__, __FILE__);
		$this->assertEqual($ret, null);

		$ret = $Sql->query_while("SELECT * FROM ".PREFIX."member LIMIT 3", __LINE__, __FILE__);
		$this->assertTrue(is_resource($ret));

		$tmp = $Sql->query_close($ret);
		$this->assertTrue($tmp);		
	}
	
	function test_fetch_row()
	{
		global $Sql;

		$ret = $Sql->query_while("SELECT * FROM ".PREFIX."member LIMIT 3", __LINE__, __FILE__);
		$this->assertTrue(is_resource($ret));
		
		while( $row = $Sql->fetch_row($ret)) {
			$this->assertTrue(is_array($row));
			$last_key = array_pop(array_keys($row));
			$this->assertTrue(is_int($last_key)); // verification qu'une des clés est un integer
		}
		$tmp = $Sql->query_close($ret);
		$this->assertTrue($tmp);
	}
	
	function test_fetch_assoc()
	{
		global $Sql;

		$ret = $Sql->query_while("SELECT * FROM ".PREFIX."member LIMIT 3", __LINE__, __FILE__);
		$this->assertTrue(is_resource($ret));

		while( $row = $Sql->fetch_assoc($ret)) {
			$this->assertTrue(is_array($row));
			$last_key = array_pop(array_keys($row));
			$this->assertTrue(is_string($last_key)); // verification qu'une des clés est une string
		}
		$tmp = $Sql->query_close($ret);
		$this->assertTrue($tmp);
	}
	
	function test_num_rows()
	{
		global $Sql;

		$ret = $Sql->query_while("SELECT * FROM ".PREFIX."member", __LINE__, __FILE__);
		$this->assertTrue(is_resource($ret));
		
		$num 	= $Sql->num_rows($ret);
		$num2 	= mysql_num_rows($ret);
		$this->assertEqual($num, $num2);
	}
	
	function test_count_table()
	{
		global $Sql;
		
		$ret = $Sql->count_table("member", __LINE__, __FILE__);
		$this->assertTrue($ret AND ($ret>0));
	}
	
	function test_limit()
	{
		global $Sql;
		
		$ret = $Sql->limit(1);
		$this->assertTrue($ret == ' LIMIT 1, 0');

		$ret = $Sql->limit(1,2);
		$this->assertTrue($ret == ' LIMIT 1, 2');
	}
	
	function test_concat()
	{
		global $Sql;
		
		$ret = $Sql->Concat('SQL', "'string'");
		$this->assertTrue($ret == " CONCAT(SQL,'string') ");
	}
	
	function test_list_fields()
	{
		global $Sql;
		
		$ret = $Sql->list_fields('');
		$this->assertTrue(is_array($ret) AND empty($ret));

		$ret = $Sql->list_fields('member');
		$this->assertTrue(is_array($ret) AND empty($ret));

		$ret = $Sql->list_fields(PREFIX.'member');
		$this->assertTrue(is_array($ret));
		$this->assertEqual(count($ret), 33);
	}
	
	function test_get_dbms_version()
	{
		global $Sql;
		
		$ret = $Sql->get_dbms_version();
		$this->assertEqual($ret, 'MySQL '.mysql_get_server_info());
	}
	
	function test_get_data_base_name()
	{
		global $Sql;
		
		$ret = $Sql->get_data_base_name();
		$this->assertEqual($ret, 'phpboost');
	}
	
	function test_list_databases()
	{
		global $Sql;
		
		$ret = $Sql->list_databases();
		$this->assertTrue(is_array($ret));
	}
	
	function test_optimize_tables()
	{
		global $Sql;

		$ret = $Sql->optimize_tables(array(PREFIX.'news'));
		$this->assertTrue($ret);
		
		$ret = $Sql->optimize_tables(array(PREFIX.'news','toto'));
		$this->assertFalse($ret);
	}
	
	function test_repair_tables()
	{
		global $Sql;

		$ret = $Sql->optimize_tables(array(PREFIX.'news'));
		$this->assertTrue($ret);
		
		$ret = $Sql->optimize_tables(array(PREFIX.'news','toto'));
		$this->assertFalse($ret);
	}
	
	function test_truncate_tables()
	{
		global $Sql;

		$ret = $Sql->truncate_tables(array(PREFIX.'toto'));
		$this->assertFalse($ret);
	}
	
	function test_drop_tables()
	{
		global $Sql;

		$ret = $Sql->drop_tables(array('toto'));
		$this->assertFalse($ret);
	}
	
	function test_clean_database_name()
	{
		global $Sql;
		
		$name = 'toto';
		$ret = $Sql->clean_database_name($name);
		$this->assertEqual($name, $ret);

		$name2 = 'toto/\.\'" ';
		$ret = $Sql->clean_database_name($name2);
		$this->assertEqual('toto______', $ret);
	}

	// doit etre en derniere position
	function test_close()
	{
		global $Sql;

		$ret = $Sql->query_while("SELECT * FROM ".PREFIX."member LIMIT 3", __LINE__, __FILE__);
		$this->assertTrue(is_resource($ret));

		$tmp = $Sql->close($ret);
		$this->assertTrue($tmp);
	}	
}
