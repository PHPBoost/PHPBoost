<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php'; //Fonctions de base.

import('core/BreadCrumb');

$Bread_crumb = new Breadcrumb();

unset($Errorh);

class UTbreadcrumb extends PHPBoostUnitTestCase {

	function test()
	{
		global $Bread_crumb;
		
		$this->check_methods($Bread_crumb);
	}
	
	function test_constructor()
	{
		// DO NOTHING
	}

	function test_add_1arg()
	{
		global $Bread_crumb;
		
		$ret = $Bread_crumb->add('texte');
		$this->assertTrue($ret);
		
		$tmp = $Bread_crumb->array_links;
		$this->assertEqual(count($tmp), 1);
		list($t1, $t2) = $tmp[0];
		$this->assertEqual($t1, 'texte');
		$this->assertTrue(empty($t2));	
	}

	function test_add_2arg()
	{
		global $Bread_crumb;
		
		$ret = $Bread_crumb->add('texte','lien');
		$this->assertTrue($ret);
		
		$tmp = $Bread_crumb->array_links;
		$this->assertEqual(count($tmp), 2);
		list($t1, $t2) = $tmp[1];
		$this->assertEqual($t1, 'texte');
		$this->assertEqual($t2, 'lien');
	}
	
	function test_reverse()
	{
		global $Bread_crumb;
		
		$ret = $Bread_crumb->reverse();
		
		$tmp = $Bread_crumb->array_links;
		$this->assertEqual(count($tmp), 2);
		list($t1, $t2) = $tmp[0];
		$this->assertEqual($t1, 'texte');
		$this->assertEqual($t2, 'lien');
	}
	
	function test_remove_last()
	{
		global $Bread_crumb;
		
		$ret = $Bread_crumb->remove_last();
		
		$tmp = $Bread_crumb->array_links;
		$this->assertEqual(count($tmp), 1);
		list($t1, $t2) = $tmp[0];
		$this->assertEqual($t1, 'texte');
		$this->assertEqual($t2, 'lien');
	}

	function test_display()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_clean()
	{
		global $Bread_crumb;
		
		$ret = $Bread_crumb->clean();
		
		$tmp = $Bread_crumb->array_links;
		$this->assertEqual(count($tmp), 0);
	}
	
}
