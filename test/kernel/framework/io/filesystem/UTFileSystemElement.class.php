<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php'; //Fonctions de base.



unset($Errorh);

class UTfse extends PHPBoostUnitTestCase {
	
	function test()
	{
		$path = PATH_TO_ROOT . '/kernel/begin.php';
		
		$fse = new FileSystemElement($path);
		$this->check_methods($fse);
	}
	
	function test_constructor()
	{
		$path = PATH_TO_ROOT . '/kernel/begin.php';
		
		$fse = new FileSystemElement($path);
		
		self::assertEquals($fse->path, $path);
		self::assertFalse($fse->is_open);
	}
	
	function test_open()
	{
		$path = PATH_TO_ROOT . '/kernel/begin.php';
		
		$fse = new FileSystemElement($path);
		$fse->open();
		self::assertTrue($fse->is_open);
	}
	
	function test_get()
	{
		$path = PATH_TO_ROOT . '/kernel/begin.php';
		
		$fse = new FileSystemElement($path);
		$fse->is_open = FALSE;
		self::assertFalse($fse->is_open);
		$fse->get();
		self::assertTrue($fse->is_open);
	}
	
	function test_write()
	{
		$path = PATH_TO_ROOT . '/kernel/begin.php';
		
		$fse = new FileSystemElement($path);
		$fse->write();
		self::assertTrue($fse->is_open);
	}
	
	function test_get_name()
	{
		$path = PATH_TO_ROOT . '/kernel/begin.php';
		
		$fse = new FileSystemElement($path);
		$name = $fse->get_name();
		self::assertEquals($name, basename($path));
	}

	function test_change_chmod()
	{
		$path = PATH_TO_ROOT . '/kernel/begin.php';
		
		$fse = new FileSystemElement($path);
		$mode = $fse->change_chmod(0755);
		self::assertTrue(TRUE);
		
		$tmp = stat($path);
		printf("%o",$tmp['mode']); echo '<br />';
	}

	function test_delete()
	{
		$path = PATH_TO_ROOT . '/kernel/begin.php';
		
		$fse = new FileSystemElement($path);
		$fse->delete();
		self::assertTrue(TRUE);
	}
}
