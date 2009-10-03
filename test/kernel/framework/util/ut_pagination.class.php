<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

import('util/pagination');

unset($Errorh);

class Errors_mock extends Errors
{
	function handler($errstr, $errno)
	{
		echo '<br />'.$errstr.'|'.$errno.'<br />';
	}
}

$Errorh = new Errors_mock();

class UTpagination extends PHPBoostUnitTestCase {

	function test()
	{
		$page = new Pagination();
		$this->check_methods($page);
	}

	function test_constructor()
	{
		// On ne fait rien
	}

	function test_display()
	{
		if (!empty($_GET['p'])) unset($_GET['p']);
		
		$page = new Pagination();
		$ret = $page->display('toto', 1, 'p', 11, 5);
		$this->assertEqual($ret, '');		
		
		$_GET['p'] = 10;
		$ret = $page->display('toto', 100, 'p', 5, 3);
		//echo $ret.'<br />';
		$this->assertPattern('|<a(.*)font-size:11px(.*)href="toto"(.*)>&laquo;</a>|', $ret);
		$this->assertPattern('|<a(.*)font-size:11px(.*)>&raquo;</a>|', $ret);
		
		$_GET['p'] = 10;
		$ret = $page->display('toto', 100, 'p', 11, 5, 13);
		$this->assertPattern('|font-size:13px(.*)&laquo;</a>|', $ret);
		
		$_GET['p'] = 10;
		$ret = $page->display('toto', 100, 'p', 11, 5, 11, false);
		$this->assertNoPattern('|<a(.*)font-size:11px(.*)>&laquo;</a>|', $ret);
		$this->assertNoPattern('|<a(.*)font-size:11px(.*)>&raquo;</a>|', $ret);
		
		$_GET['p'] = 10;
		$ret = $page->display('toto', 100, 'p', 11, 5, 11, true, false);
		$this->assertNoPattern('|<a(.*)style="font-size:11px;text-decoration: underline;">|', $ret);
	}

	function test_get_first_msg()
	{
		if (!empty($_GET['p'])) unset($_GET['p']);

		$page = new Pagination();

		$ret = $page->get_first_msg(2, 'p');
		$this->assertEqual($ret, 0);
		
		$_GET['p'] = 10;
		$ret = $page->get_first_msg(2, 'p');
		$this->assertEqual($ret, 18);

		$_GET['p'] = 10;
		$ret = $page->get_first_msg(0, 'p');
		$this->assertEqual($ret, 0);		
		
		$_GET['p'] = 0;
		$ret = $page->get_first_msg(10, 'p');
		$this->assertEqual($ret, 0);
	}

	function test__get_var_page()
	{
		if (!empty($_GET['p'])) unset($_GET['p']);
		
		$page = new Pagination();
		$ret = $page->_get_var_page('p');
		$this->assertEqual($ret, 1);

		$_GET['p'] = 11;
		$ret = $page->_get_var_page('p');
		$this->assertEqual($ret, 11);
		
		$_GET['p'] = 'toto';
		$ret = $page->_get_var_page('p');
		$this->assertEqual($ret, 1);
	}
	
	function test_get_current_page()
	{
		$page = new Pagination();
		$page->var_page = 'p';
		$ret = $page->get_current_page();
		$this->assertEqual($ret, 1);
		
		$_GET['p'] = 11;
		$page->var_page = 'p';
		$ret = $page->get_current_page();
		$this->assertEqual($ret, 11);
	}
	
	function test__check_page()
	{
		$page = new Pagination();
		$ret = $page->_check_page(1);
		$this->assertEqual($ret, null);
		
		$page->page = -1;
		$ret = $page->_check_page(1);
		$this->assertEqual($ret, -1);
		
		$page->page = 2;
		$ret = $page->_check_page(1);
		$this->assertEqual($ret, 2);
	}
}
