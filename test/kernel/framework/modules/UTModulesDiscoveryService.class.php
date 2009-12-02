<?php

class UTModulesDiscoveryService extends PHPBoostUnitTestCase
{
	function test_constructor()
	{
		Global $MODULES;
		
		$this->assertTrue(count($MODULES) > 0);
		$modulediscovery = new ModulesDiscoveryService();
		$this->assertTrue(is_array($modulediscovery->loaded_modules));
        $this->assertTrue(is_array($modulediscovery->availables_modules));
		$this->assertTrue(count($modulediscovery->availables_modules) > 0 AND count($modulediscovery->availables_modules) <= count($MODULES));
	}

	function test_functionnality()
	{
		Global $MODULES;
		
		$this->assertTrue(count($MODULES) > 0);
		$modulediscovery = new ModulesDiscoveryService();
		
		$params = array('news' => array());
		$ret = $modulediscovery->functionnality('get_cache', $params);
		var_dump($ret); exit;
	}
	
	function test_get_available_modules()
	{
		Global $MODULES;
		
		$this->assertTrue(count($MODULES) > 0);
		$modulediscovery = new ModulesDiscoveryService();
		$ret = $modulediscovery->get_available_modules();
		$this->assertTrue(is_array($ret));
		$ret = $modulediscovery->get_available_modules('get_cache');
		$this->assertTrue(is_array($ret));
	}
	
	function test_get_module()
	{
		Global $MODULES;
		
		$this->assertTrue(count($MODULES) > 0);
		$modulediscovery = new ModulesDiscoveryService();
		
		$ret = $modulediscovery->get_module('news');
		$this->assertIsA($ret, 'ModuleInterface');
		$this->assertIsA($modulediscovery->loaded_modules['news'], 'ModuleInterface');
	}

}
?>