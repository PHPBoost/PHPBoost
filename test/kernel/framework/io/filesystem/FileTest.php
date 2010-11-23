<?php

class FileTest extends PHPBoostUnitTestCase
{
    // TODO recode these tests with files in the /test/data directory and not with kernel files
//	function test_file()
//	{	
//		$path = PATH_TO_ROOT . '/kernel';
//		
//		$file = new File($path);
//		self::assertEquals($file->path, $path);
//		self::assertEquals($file->mode, READ_WRITE);
//		self::assertFalse($file->is_open());
//		unset($file);
//		
//		$file = new File($path, READ);
//		self::assertEquals($file->path, $path);
//		self::assertEquals($file->mode, READ);
//		unset($file);
//		
//		$file = new File($path, WRITE);
//		self::assertEquals($file->path, $path);
//		self::assertEquals($file->mode, WRITE);
//		unset($file);
//		
//		$path = PATH_TO_ROOT . '/kernel/begin.php';
//		
//		$file = new File($path, READ_WRITE, DIRECT_OPENING);
//		self::assertEquals($file->path, $path);
//		self::assertTrue($file->is_open());
//		unset($file);
//		
//		$path = 'toto.tmp';
//		
//		$file = new File($path, READ_WRITE, DIRECT_OPENING);
//		self::assertEquals($file->path, $path);
//		self::assertTrue(file_exists($path));
//		$file->delete();
//	}
//
//	function test_open()
//	{
//		$path = PATH_TO_ROOT . '/kernel/begin.php';
//		
//		$file = new File($path);
//		$file->open();
//		self::assertTrue(!empty($file->lines));
//		self::assertTrue(!empty($file->contents));
//	}
//	
//	function test_get_contents()
//	{
//		$path = PATH_TO_ROOT . '/kernel/begin.php';
//		
//		$file = new File($path);
//		$file->open();
//		$ret = $file->read();
//		self::assertTrue(!empty($ret) AND is_string($ret));
//		
//		$ret = $file->read(10);
//		self::assertTrue(!empty($ret) AND is_string($ret));
//		
//		$ret = $file->read(10,filesize($path));
//		self::assertTrue(!empty($ret) AND is_string($ret));
//		unset($file);
//
//		echo '<br />get_contents - Test exception sur lecture si PAS MODE READ<br />';	
//		
//		$file = new File($path, WRITE);
//		$file->open();
//		$ret = $file->read();
//		self::assertError();
//		self::assertFalse($ret);
//	}
//	
//	function test_get_lines()
//	{
//		$path = PATH_TO_ROOT . '/kernel/begin.php';
//		
//		$file = new File($path);
//		$file->open();
//		$ret = $file->get_lines();
//		self::assertTrue(!empty($ret) AND is_array($ret) AND (count($ret) == count($file->lines)));
//		
//		$ret = $file->get_lines(0, 10);
//		self::assertTrue(!empty($ret) AND is_array($ret) AND (count($ret) == 10));
//		
//		$ret = $file->get_lines(10, count($file->lines));
//		self::assertTrue(!empty($ret) AND is_array($ret) AND (count($ret) == count($file->lines)-10));
//		unset($file);
//
//		echo '<br />get_lines - Test exception sur lecture si PAS MODE READ<br />';
//		
//		$file = new File($path, WRITE);
//		$file->open();
//		$ret = $file->get_lines();
//		self::assertError();
//		self::assertFalse($ret);	
//	}
//	
//	function test_write()
//	{
//		TODO(__FILE__, __METHOD__);
//	}
//
//	function test_close()
//	{
//		TODO(__FILE__, __METHOD__);
//	}
//
//	function test_delete()
//	{
//		$path = 'toto.tmp';
//		
//		$file = new File($path, READ_WRITE, DIRECT_OPENING);
//		self::assertEquals($file->path, $path);
//		self::assertTrue(file_exists($path));
//		
//		$file->delete();
//		self::assertFalse(file_exists($path));
//	}
//
//	function test_lock_unlock()
//	{
//		$path = 'toto.tmp';
//		
//		$file = new File($path, READ_WRITE, DIRECT_OPENING);
//		self::assertEquals($file->path, $path);
//		self::assertTrue(file_exists($path));
//		
//		$file->lock();
//		self::assertTrue(true);
//		
//		$file->unlock();
//		$file->delete();
//	}	
}
?>