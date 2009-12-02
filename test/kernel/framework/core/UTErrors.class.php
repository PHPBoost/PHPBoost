<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

unset($Errorh);

class UTerrors extends PHPBoostUnitTestCase {

	function test()
	{
		$errorh = new Errors();
		$this->check_methods($errorh);
	}

	function test_constructor()
	{
		// DO NOTHING
	}
	
	function test_get_errno_class()
	{
		$tmp = Errors::get_errno_class(E_USER_REDIRECT);
		$this->assertEquals($tmp, "error_fatal");
		$tmp = Errors::get_errno_class(E_USER_NOTICE);
		$this->assertEquals($tmp, "error_notice");
		$tmp = Errors::get_errno_class(E_NOTICE);
		$this->assertEquals($tmp, "error_notice");
		$tmp = Errors::get_errno_class(E_USER_WARNING);
		$this->assertEquals($tmp, "error_warning");
		$tmp = Errors::get_errno_class(E_WARNING);
		$this->assertEquals($tmp, "error_warning");
		$tmp = Errors::get_errno_class(E_USER_ERROR);
		$this->assertEquals($tmp, "error_fatal");
		$tmp = Errors::get_errno_class(E_ERROR);
		$this->assertEquals($tmp, "error_fatal");
		$tmp = Errors::get_errno_class(0);
		$this->assertEquals($tmp, "error_unknow");
	}
}
