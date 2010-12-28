<?php

$Sql = Appcontext::get_sql();

class SqlTest extends PHPBoostUnitTestCase
{

	function test()
	{
		global $Sql;
		
		$this->MY_check_methods($Sql);
	}
	
	function test_Sql()
	{
		// DO NOTHING
	}

	function test_query()
	{
		global $Sql;
		
		$ret = $Sql->query('SELECT TOTO', __LINE__, __FILE__);
		$this->assertEquals($ret, null);

		$ret = $Sql->query('SELECT * FROM '.PREFIX.'member', __LINE__, __FILE__);
		$this->assertTrue($ret);
	}

	function test_query_array()
	{
		global $Sql;
		
		$ret = $Sql->query_array('members', '*', __LINE__, __FILE__);
		$this->assertEquals($ret, null);

		$ret = $Sql->query_array(PREFIX.'member', '*', __LINE__, __FILE__);
		$this->assertTrue(is_array($ret));
	}
	
	function test_query_inject()
	{
		global $Sql;
		
		$ret = $Sql->query_inject('INSERT INTO members', __LINE__, __FILE__);
		$this->assertEquals($ret, null);

		$ret = $Sql->query_inject("INSERT INTO ".PREFIX."member SET login = 'toto'", __LINE__, __FILE__);
		$this->assertEquals($ret, true);
		
		$ret = $Sql->query_inject("UPDATE INTO Erreur de syntaxe SET login = 'titi' WHERE login='toto'", __LINE__, __FILE__);
		$this->assertEquals($ret, null);

		$ret = $Sql->query_inject("UPDATE ".PREFIX."member SET login = 'titi' WHERE login='toto'", __LINE__, __FILE__);
		$this->assertEquals($ret, true);
		$rows = $Sql->affected_rows('resource', 'Bidon');
		$this->assertTrue(is_int($rows) AND ($rows>0));
		
		$ret = $Sql->query_inject("DELETE INTO Erreur de syntaxe SET login = 'titi' WHERE login='toto'", __LINE__, __FILE__);
		$this->assertEquals($ret, null);

		$ret = $Sql->query_inject("DELETE FROM ".PREFIX."member WHERE login='titi'", __LINE__, __FILE__);
		$this->assertEquals($ret, true);
		
		$ret = $Sql->query_inject("ALTER TABLE ".PREFIX."member AUTO_INCREMENT=3", __LINE__, __FILE__);
		$this->assertEquals($ret, true);
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
		$this->assertEquals($ret, true);
		
		$id = $Sql->insert_id($ret);
		$this->assertTrue(is_int($id));
	}
	
	function test_affected_rows()
	{
		global $Sql;

		$ret = $Sql->query_inject("INSERT INTO ".PREFIX."member SET login = 'toto'", __LINE__, __FILE__);
		$this->assertEquals($ret, true);
		
		$nb = $Sql->affected_rows($ret);
		$this->assertEquals($nb, 1);
	}
	
	function test_query_while()
	{
		global $Sql;
		
		$ret = $Sql->query_while('SELECT * FROM table_inexsistante', __LINE__, __FILE__);
		$this->assertEquals($ret, null);

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
		$this->assertEquals($num, $num2);
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
		$this->assertEquals(count($ret), 33);
	}
	
	function test_get_data_base_name()
	{
		global $Sql;
		
		$ret = $Sql->get_data_base_name();
		$this->assertEquals($ret, 'phpboost');
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
?>