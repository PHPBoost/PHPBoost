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
	
	function test__clean_error_string()
	{
		$errorh = new Errors();
		$str = "\r\t\ntest\r\t\n";
		$tmp = $errorh->_clean_error_string($str);
		$this->assertEqual($tmp, "<br />test<br />");
	}

	function test_get_errno_class()
	{
		$errorh = new Errors();
		$tmp = $errorh->get_errno_class(E_USER_REDIRECT);
		$this->assertEqual($tmp, "error_fatal");
		$tmp = $errorh->get_errno_class(E_USER_NOTICE);
		$this->assertEqual($tmp, "error_notice");
		$tmp = $errorh->get_errno_class(E_NOTICE);
		$this->assertEqual($tmp, "error_notice");
		$tmp = $errorh->get_errno_class(E_USER_WARNING);
		$this->assertEqual($tmp, "error_warning");
		$tmp = $errorh->get_errno_class(E_WARNING);
		$this->assertEqual($tmp, "error_warning");
		$tmp = $errorh->get_errno_class(E_USER_ERROR);
		$this->assertEqual($tmp, "error_fatal");
		$tmp = $errorh->get_errno_class(E_ERROR);
		$this->assertEqual($tmp, "error_fatal");
		$tmp = $errorh->get_errno_class(0);
		$this->assertEqual($tmp, "error_unknow");
	}
}
