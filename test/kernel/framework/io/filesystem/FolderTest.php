<?php

class FolderTest extends PHPBoostUnitTestCase
{
    // TODO recode these tests with files in the /test/data directory and not with kernel files
//	function test_constructor()
//	{
//		$path = PATH_TO_ROOT . '/kernel';
//		
//		$folder = new Folder($path);
//		self::assertEquals($folder->path, rtrim($path, '/'));
//		unset($folder);
//		
//		$path = dirname(__FILE__) . '/tmp_dir';
//		$folder = new Folder($path);
//		self::assertTrue(is_dir($path));
//		$folder->delete();
//	}
//
//	function test_open()
//	{
//		$path = PATH_TO_ROOT . '/kernel';
//		
//		$folder = new Folder($path);
//		$folder->open();
//		self::assertTrue(is_array($folder->files));
//		self::assertTrue(is_a($folder->files[0], 'file'));
//		self::assertTrue(is_array($folder->folders));
//		self::assertTrue(is_a($folder->folders[0], 'folder'));
//	}
//	
//	function test_get_files()
//	{
//		$path = PATH_TO_ROOT . '/kernel';
//		
//		$folder = new Folder($path);
//		$folder->open();
//		$ret = $folder->get_files();
//		self::assertTrue(is_array($ret));
//		self::assertTrue($folder->is_open);
//		self::assertEquals(count($ret), count($folder->files));
//	}
//	
//	function test_get_folders()
//	{
//		$path = PATH_TO_ROOT . '/kernel';
//		
//		$folder = new Folder($path);
//		$folder->open();
//		$ret = $folder->get_folders();
//		self::assertTrue(is_array($ret));
//		self::assertEquals(count($ret), count($folder->folders));
//	}
//	
//	function test_get_first_folder()
//	{
//		$path = PATH_TO_ROOT . '/kernel';
//		
//		$folder = new Folder($path);
//		self::assertFalse($folder->is_open);
//		$ret = $folder->get_first_folder();
//		self::assertTrue($folder->is_open);
//		self::assertEquals($ret, $folder->folders[0]);
//		unset($folder);
//		
//		$path = dirname(__FILE__) . '/tmp_dir';
//		
//		$folder = new Folder($path);
//		self::assertTrue(is_dir($path));
//		$ret = $folder->get_first_folder();
//		self::assertFalse($ret);
//	}
//
//	function test_get_all_content()
//	{
//		$path = PATH_TO_ROOT . '/kernel';
//		
//		$folder = new Folder($path);
//		$folder->open();
//		$ret = $folder->get_all_content();
//		self::assertEquals(count($ret), count($folder->folders) + count($folder->files) );
//	}
//
//	function test_delete()
//	{
//		$path = dirname(__FILE__) . '\tmp_dir';
//		
//		$folder = new Folder($path);		
//		self::assertTrue(is_dir($path));
//		$folder->delete();
//		self::assertFalse(file_exists($path));
//	}
}
?>