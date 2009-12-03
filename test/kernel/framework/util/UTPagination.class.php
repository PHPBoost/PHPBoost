<?php

class UTPagination extends PHPBoostUnitTestCase
{
	function test_constructor()
	{
		// On ne fait rien
	}

	function test_display()
	{
		if (!empty($_GET['p'])) unset($_GET['p']);
		
		$page = new Pagination();
		$ret = $page->display('toto', 1, 'p', 11, 5);
		self::assertEquals($ret, '');		
		
		$_GET['p'] = 10;
		$ret = $page->display('toto', 100, 'p', 5, 3);
		//echo $ret.'<br />';
		
		self::assertRegExp('|<a(.*)font-size:11px(.*)href="toto"(.*)>&laquo;</a>|', $ret);
		self::assertRegExp('|<a(.*)font-size:11px(.*)>&raquo;</a>|', $ret);
		
		$_GET['p'] = 10;
		$ret = $page->display('toto', 100, 'p', 11, 5, 13);
		self::assertRegExp('|font-size:13px(.*)&laquo;</a>|', $ret);
		
		$_GET['p'] = 10;
		$ret = $page->display('toto', 100, 'p', 11, 5, 11, false);
		self::assertNotRegExp('|<a(.*)font-size:11px(.*)>&laquo;</a>|', $ret);
		self::assertNotRegExp('|<a(.*)font-size:11px(.*)>&raquo;</a>|', $ret);
		
		$_GET['p'] = 10;
		$ret = $page->display('toto', 100, 'p', 11, 5, 11, true, false);
		self::assertNotRegExp('|<a(.*)style="font-size:11px;text-decoration: underline;">|', $ret);
	}

	function test_get_first_msg()
	{
		if (!empty($_GET['p'])) unset($_GET['p']);

		$page = new Pagination();

		$ret = $page->get_first_msg(2, 'p');
		self::assertEquals($ret, 0);
		
		$_GET['p'] = 10;
		$ret = $page->get_first_msg(2, 'p');
		self::assertEquals($ret, 18);

		$_GET['p'] = 10;
		$ret = $page->get_first_msg(0, 'p');
		self::assertEquals($ret, 0);		
		
		$_GET['p'] = 0;
		$ret = $page->get_first_msg(10, 'p');
		self::assertEquals($ret, 0);
	}
}
