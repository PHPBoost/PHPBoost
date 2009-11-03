<?php
require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php'; //Fonctions de base.



unset($Errorh);

class UTmodule_interface extends PHPBoostUnitTestCase {

	function test()
	{
		$module = new ModuleInterface('news', 1);
		$this->check_methods($module);
	}

	function test_constructor()
	{
		$ret = new ModuleInterface('news', 1);
		$this->assertTrue(is_object($ret));
		$this->assertEqual($ret->get_id(), 'news');
		$this->assertTrue(is_array($ret->functionnalities) AND count($ret->functionnalities)==0);
		$this->assertTrue($ret->get_errors() != 0);
		
		$ret = new ModuleInterface('news');
		$this->assertTrue(is_object($ret));
		$this->assertEqual($ret->get_id(), 'news');
		$this->assertTrue(is_array($ret->functionnalities) AND count($ret->functionnalities) > 0);
	}

	function test_get_id()
	{
		$ret = new ModuleInterface('news', 1);
		$this->assertTrue(is_object($ret));
		$this->assertEqual($ret->get_id(), 'news');
	}
	
	function test_get_name()
	{
		$ret = new ModuleInterface('news', 1);
		$this->assertTrue(is_object($ret));
		$this->assertEqual($ret->get_name(), 'News');
	}
	
	function test_get_infos()
	{
		$ret = new ModuleInterface('news', 1);
		$this->assertTrue(is_object($ret));
		
		$tmp = $ret->get_infos();
		$this->assertEqual($tmp['name'], $ret->get_name());
		$this->assertEqual($tmp['functionnalities'], $ret->functionnalities);
		$this->assertTrue(is_array($tmp['infos']));
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