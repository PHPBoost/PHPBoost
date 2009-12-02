<?php

class UTbreadcrumb extends PHPBoostUnitTestCase
{
	function test_constructor()
	{
		// DO NOTHING
	}

	function test_add_1arg()
	{
		$bread_crumb = new BreadCrumb();
		
		$ret = $bread_crumb->add('texte');
		$this->assertTrue($ret);
		
		$tmp = $bread_crumb->array_links;
		$this->assertEquals(count($tmp), 1);
		list($t1, $t2) = $tmp[0];
		$this->assertEquals($t1, 'texte');
		$this->assertTrue(empty($t2));	
	}

	function test_add_2arg()
	{
		$bread_crumb = new BreadCrumb();
		
		$ret = $bread_crumb->add('texte','lien');
		$this->assertTrue($ret);
		
		$tmp = $bread_crumb->array_links;
		$this->assertEquals(count($tmp), 2);
		list($t1, $t2) = $tmp[1];
		$this->assertEquals($t1, 'texte');
		$this->assertEquals($t2, 'lien');
	}
	
	function test_reverse()
	{
		$bread_crumb = new BreadCrumb();
		
		$ret = $bread_crumb->reverse();
		
		$tmp = $bread_crumb->array_links;
		$this->assertEquals(count($tmp), 2);
		list($t1, $t2) = $tmp[0];
		$this->assertEquals($t1, 'texte');
		$this->assertEquals($t2, 'lien');
	}
	
	function test_remove_last()
	{
		$bread_crumb = new BreadCrumb();
		
		$ret = $bread_crumb->remove_last();
		
		$tmp = $bread_crumb->array_links;
		$this->assertEquals(count($tmp), 1);
		list($t1, $t2) = $tmp[0];
		$this->assertEquals($t1, 'texte');
		$this->assertEquals($t2, 'lien');
	}

	function test_display()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_clean()
	{
		$bread_crumb = new BreadCrumb();
		
		$ret = $bread_crumb->clean();
		
		$tmp = $bread_crumb->array_links;
		$this->assertEquals(count($tmp), 0);
	}
	
}
