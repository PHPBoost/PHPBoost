<?php

class applicationTest extends PHPBoostUnitTestCase {

	function test_constructor()
	{
		$id 		= 'news';
		$language 	= 'french';
		
		$app = new Application($id, $language);
		
		self::assertEquals($id, $app->get_id());
		self::assertEquals($id, $app->get_name());
		self::assertEquals($language, $app->get_language());
		self::assertEquals(APPLICATION_TYPE__MODULE, $app->get_type());
		self::assertEquals('', $app->get_repository());
		self::assertEquals(0, $app->get_version());
		$d = new Date();
		self::assertEquals($d->format(DATE_FORMAT_SHORT, TIMEZONE_USER), $app->get_pubdate());
		
		$vers 	= 1;
		$repo	= 'repository';
		unset($app);
		$app = new Application($id, $language, APPLICATION_TYPE__MODULE, $vers, $repo);
		
		self::assertEquals(APPLICATION_TYPE__MODULE, $app->get_type());
		self::assertEquals($repo, $app->get_repository());
		self::assertEquals($vers, $app->get_version());

		unset($app);
		$app = new Application($id, $language, APPLICATION_TYPE__TEMPLATE, $vers, $repo);
		
		self::assertEquals(APPLICATION_TYPE__TEMPLATE, $app->get_type());
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
		
		self::assertEquals($md5, $app->get_identifier());
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
		self::assertEquals($vers,'0');
		
		TODO(__FILE__, __METHOD__);
	}
	
}
