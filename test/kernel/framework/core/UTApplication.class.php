<?php

class UTapplication extends PHPBoostUnitTestCase {

	function test_constructor()
	{
		$id 		= 'news';
		$language 	= 'french';
		
		$app = new Application($id, $language);
		
		$this->assertEquals($id, $app->get_id());
		$this->assertEquals($id, $app->get_name());
		$this->assertEquals($language, $app->get_language());
		$this->assertEquals(APPLICATION_TYPE__MODULE, $app->get_type());
		$this->assertEquals('', $app->get_repository());
		$this->assertEquals(0, $app->get_version());
		$d = new Date();
		$this->assertEquals($d->format(DATE_FORMAT_SHORT, TIMEZONE_USER), $app->get_pubdate());
		
		$vers 	= 1;
		$repo	= 'repository';
		unset($app);
		$app = new Application($id, $language, APPLICATION_TYPE__MODULE, $vers, $repo);
		
		$this->assertEquals(APPLICATION_TYPE__MODULE, $app->get_type());
		$this->assertEquals($repo, $app->get_repository());
		$this->assertEquals($vers, $app->get_version());

		unset($app);
		$app = new Application($id, $language, APPLICATION_TYPE__TEMPLATE, $vers, $repo);
		
		$this->assertEquals(APPLICATION_TYPE__TEMPLATE, $app->get_type());
	}
	
	function test_load()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_get_identifier()
	{
		$id 		= 'news';
		$language 	= 'french';
		
		$app = new Application($id, $language);
		$md5 = md5($app->get_type() . '_' . $app->get_id() . '_' . $app->get_version() . '_' . $app->get_language());
		
		$this->assertEquals($md5, $app->get_identifier());
	}

	function test__get_attribute()
	{
		$id 		= 'news';
		$language 	= 'french';
		
		$app = new Application($id, $language);
		TODO(__FILE__, __METHOD__);
	}
	
	function test__get_installed_version()
	{
		$id 		= 'news';
		$language 	= 'french';
		
		$app = new Application($id, $language);
		$vers = $app->_get_installed_version();
		$this->assertEquals($vers,'0');
		
		TODO(__FILE__, __METHOD__);
	}
	
}
