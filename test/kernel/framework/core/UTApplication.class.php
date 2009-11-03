<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';



unset($Errorh);

class UTapplication extends PHPBoostUnitTestCase {

	function test()
	{
		$id 		= 'news';
		$language 	= 'french';
		$app = new Application($id, $language);
		
		$this->check_methods($app);
	}
	
	function test_constructor()
	{
		$id 		= 'news';
		$language 	= 'french';
		
		$app = new Application($id, $language);
		
		$this->assertEqual($id, $app->get_id());
		$this->assertEqual($id, $app->get_name());
		$this->assertEqual($language, $app->get_language());
		$this->assertEqual(APPLICATION_TYPE__MODULE, $app->get_type());
		$this->assertEqual('', $app->get_repository());
		$this->assertEqual(0, $app->get_version());
		$d = new Date();
		$this->assertEqual($d->format(DATE_FORMAT_SHORT, TIMEZONE_USER), $app->get_pubdate());
		
		$vers 	= 1;
		$repo	= 'repository';
		unset($app);
		$app = new Application($id, $language, APPLICATION_TYPE__MODULE, $vers, $repo);
		
		$this->assertEqual(APPLICATION_TYPE__MODULE, $app->get_type());
		$this->assertEqual($repo, $app->get_repository());
		$this->assertEqual($vers, $app->get_version());

		unset($app);
		$app = new Application($id, $language, APPLICATION_TYPE__TEMPLATE, $vers, $repo);
		
		$this->assertEqual(APPLICATION_TYPE__TEMPLATE, $app->get_type());
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
		
		$this->assertEqual($md5, $app->get_identifier());
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
		$this->assertEqual($vers,'0');
		
		TODO(__FILE__, __METHOD__);

		/*
		unset($app);
		$app = new Application($id, $language, APPLICATION_TYPE__MODULE);
		$vers = $app->_get_installed_version();
		$this->assertTrue($vers);
		
		unset($app);
		$app = new Application($id, $language, APPLICATION_TYPE__MODULE);
		$vers = $app->_get_installed_version();
		$this->assertTrue($vers);
		*/
	}
	
}
