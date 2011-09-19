<?php

class ExtensionPointProviderServiceTest extends PHPBoostUnitTestCase
{
	function test_constructor()
	{
		Global $MODULES;
		
		self::assertTrue(count($MODULES) > 0);
		$modulediscovery = AppContext::get_extension_provider_service();
		self::assertTrue(is_array($modulediscovery->loaded_modules));
        self::assertTrue(is_array($modulediscovery->availables_modules));
		self::assertTrue(count($modulediscovery->availables_modules) > 0 AND count($modulediscovery->availables_modules) <= count($MODULES));
	}

	function test_functionnality()
	{
		Global $MODULES;
		
		self::assertTrue(count($MODULES) > 0);
		$modulediscovery = AppContext::get_extension_provider_service();
		
		$params = array('news' => array());
		$ret = $modulediscovery->functionnality('get_cache', $params);
		var_dump($ret); exit;
	}
	
	function test_get_module()
	{
		Global $MODULES;
		
		self::assertTrue(count($MODULES) > 0);
		$modulediscovery = AppContext::get_extension_provider_service();
		
		$ret = $modulediscovery->get_provider('news');
		self::assertIsA($ret, 'ExtensionPointProvider');
		self::assertIsA($modulediscovery->loaded_modules['news'], 'ExtensionPointProvider');
	}

}
?>