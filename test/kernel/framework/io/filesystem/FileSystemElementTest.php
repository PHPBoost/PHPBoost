<?php
class FileSystemElementTest extends PHPBoostUnitTestCase
{
	// TODO recode these tests with files in the /test/data directory and not with kernel files
	
//	function test_constructor()
//	{
//		$path = PATH_TO_ROOT . '/kernel/begin.php';
//		
//		$fse = new File($path);
//		
//		self::assertEquals($fse->path, $path);
//		self::assertFalse($fse->is_open);
//	}
//	
//	function test_open()
//	{
//		$path = PATH_TO_ROOT . '/kernel/begin.php';
//		
//		$fse = new File($path);
//		$fse->open();
//		self::assertTrue($fse->is_open);
//	}
//	
//	function test_get()
//	{
//		$path = PATH_TO_ROOT . '/kernel/begin.php';
//		
//		$fse = new File($path);
//		$fse->is_open = FALSE;
//		self::assertFalse($fse->is_open);
//		$fse->get();
//		self::assertTrue($fse->is_open);
//	}
//	
//	function test_write()
//	{
//		$path = PATH_TO_ROOT . '/kernel/begin.php';
//		
//		$fse = new File($path);
//		$fse->write();
//		self::assertTrue($fse->is_open);
//	}
//	
//	function test_get_name()
//	{
//		$path = PATH_TO_ROOT . '/kernel/begin.php';
//		
//		$fse = new File($path);
//		$name = $fse->get_name();
//		self::assertEquals($name, basename($path));
//	}
//
//	function test_change_chmod()
//	{
//		$path = PATH_TO_ROOT . '/kernel/begin.php';
//		
//		$fse = new File($path);
//		$mode = $fse->change_chmod(0755);
//		self::assertTrue(TRUE);
//		
//		$tmp = stat($path);
//		printf("%o",$tmp['mode']); echo '<br />';
//	}
//
//	function test_delete()
//	{
//		$path = PATH_TO_ROOT . '/kernel/begin.php';
//		
//		$fse = new File($path);
//		$fse->delete();
//		self::assertTrue(TRUE);
//	}
}
