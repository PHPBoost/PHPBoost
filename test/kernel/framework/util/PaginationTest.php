<?php

class PaginationTest extends PHPBoostUnitTestCase
{
	function test_constructor()
	{
		// On ne fait rien
	}

	function test_display()
	{
		global $SID;
		
		if (!empty($_GET['p'])) unset($_GET['p']);
		
		$pagination = new ModulePagination(1, 1, 11);
		$pagination->set_url(new Url('toto'));
		$ret = $pagination->display();
		self::assertEquals($ret, '');
		
		$pagination = new ModulePagination(10, 100, 5);
		$pagination->set_url(new Url('toto'));
		$ret = $pagination->display();
		self::assertRegExp('|<a(.*)font-size:11px(.*)href="toto"(.*)>&laquo;</a>|', $ret);
		self::assertRegExp('|<a(.*)font-size:11px(.*)>&raquo;</a>|', $ret);
	}
}
