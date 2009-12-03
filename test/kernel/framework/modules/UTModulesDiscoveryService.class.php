<?php

class UTModulesDiscoveryService extends PHPBoostUnitTestCase
{
	function test_constructor()
	{
		Global $MODULES;
		
		self::assertTrue(count($MODULES) > 0);
		$modulediscovery = new ModulesDiscoveryService();
		self::assertTrue(is_array($modulediscovery->loaded_modules));
        self::assertTrue(is_array($modulediscovery->availables_modules));
		self::assertTrue(count($modulediscovery->availables_modules) > 0 AND count($modulediscovery->availables_modules) <= count($MODULES));
	}

	function test_functionnality()
	{
		Global $MODULES;
		
		self::assertTrue(count($MODULES) > 0);
		$modulediscovery = new ModulesDiscoveryService();
		
		$params = array('news' => array());
		$ret = $modulediscovery->functionnality('get_cache', $params);
		var_dump($ret); exit;
	}
	
	function test_get_available_modules()
	{
		Global $MODULES;
		
		self::assertTrue(count($MODULES) > 0);
		$modulediscovery = new ModulesDiscoveryService();
		$ret = $modulediscovery->get_available_modules();
		self::assertTrue(is_array($ret));
		$ret = $modulediscovery->get_available_modules('get_cache');
		self::assertTrue(is_array($ret));
	}
	
	function test_get_module()
	{
		Global $MODULES;
		
		self::assertTrue(count($MODULES) > 0);
		$modulediscovery = new ModulesDiscoveryService();
		
		$ret = $modulediscovery->get_module('news');
		self::assertIsA($ret, 'ModuleInterface');
		self::assertIsA($modulediscovery->loaded_modules['news'], 'ModuleInterface');
	}

}
?>