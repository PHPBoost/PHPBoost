<?php

class UTModuleInterface extends PHPBoostUnitTestCase
{
	function test_constructor()
	{
		$ret = new ModuleInterface('news', 1);
		self::assertTrue(is_object($ret));
		self::assertEquals($ret->get_id(), 'news');
		self::assertTrue(is_array($ret->functionnalities) AND count($ret->functionnalities)==0);
		self::assertTrue($ret->get_errors() != 0);
		
		$ret = new ModuleInterface('news');
		self::assertTrue(is_object($ret));
		self::assertEquals($ret->get_id(), 'news');
		self::assertTrue(is_array($ret->functionnalities) AND count($ret->functionnalities) > 0);
	}

	function test_get_id()
	{
		$ret = new ModuleInterface('news', 1);
		self::assertTrue(is_object($ret));
		self::assertEquals($ret->get_id(), 'news');
	}
	
	function test_get_name()
	{
		$ret = new ModuleInterface('news', 1);
		self::assertTrue(is_object($ret));
		self::assertEquals($ret->get_name(), 'News');
	}
	
	function test_get_infos()
	{
		$ret = new ModuleInterface('news', 1);
		self::assertTrue(is_object($ret));
		
		$tmp = $ret->get_infos();
		self::assertEquals($tmp['name'], $ret->get_name());
		self::assertEquals($tmp['functionnalities'], $ret->functionnalities);
		self::assertTrue(is_array($tmp['infos']));
		print_r($tmp['infos']);
	}
	
	function test_get_attribute()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_set_attribute()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test_unset_attribute()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test_got_error()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test_get_errors()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test_functionnality()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test_has_functionnality()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test_has_functionnalities()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test__set_error()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test__clear_error()
	{
		TODO(__FILE__, __METHOD__);
	}
}
?>