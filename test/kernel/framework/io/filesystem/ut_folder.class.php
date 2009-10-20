<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php'; //Fonctions de base.

import('io/filesystem/Folder');

unset($Errorh);

class UTfolder extends PHPBoostUnitTestCase {

	function test()
	{
		$path = PATH_TO_ROOT . '/kernel';
		
		$folder = new Folder($path);
		$this->check_methods($folder);
	}

	function test_constructor()
	{
		$path = PATH_TO_ROOT . '/kernel';
		
		$folder = new Folder($path);
		$this->assertEqual($folder->path, rtrim($path, '/'));
		unset($folder);
		
		$path = dirname(__FILE__) . '/tmp_dir';
		$folder = new Folder($path);
		$this->assertTrue(is_dir($path));
		$folder->delete();
	}

	function test_open()
	{
		$path = PATH_TO_ROOT . '/kernel';
		
		$folder = new Folder($path);
		$folder->open();
		$this->assertTrue(is_array($folder->files));
		$this->assertTrue(is_a($folder->files[0], 'file'));
		$this->assertTrue(is_array($folder->folders));
		$this->assertTrue(is_a($folder->folders[0], 'folder'));
	}
	
	function test_get_files()
	{
		$path = PATH_TO_ROOT . '/kernel';
		
		$folder = new Folder($path);
		$folder->open();
		$ret = $folder->get_files();
		$this->assertTrue(is_array($ret));
		$this->assertTrue($folder->is_open);
		$this->assertEqual(count($ret), count($folder->files));
	}
	
	function test_get_folders()
	{
		$path = PATH_TO_ROOT . '/kernel';
		
		$folder = new Folder($path);
		$folder->open();
		$ret = $folder->get_folders();
		$this->assertTrue(is_array($ret));
		$this->assertEqual(count($ret), count($folder->folders));
	}
	
	function test_get_first_folder()
	{
		$path = PATH_TO_ROOT . '/kernel';
		
		$folder = new Folder($path);
		$this->assertFalse($folder->is_open);
		$ret = $folder->get_first_folder();
		$this->assertTrue($folder->is_open);
		$this->assertEqual($ret, $folder->folders[0]);
		unset($folder);
		
		$path = dirname(__FILE__) . '/tmp_dir';
		
		$folder = new Folder($path);
		$this->assertTrue(is_dir($path));
		$ret = $folder->get_first_folder();
		$this->assertFalse($ret);
	}

	function test_get_all_content()
	{
		$path = PATH_TO_ROOT . '/kernel';
		
		$folder = new Folder($path);
		$folder->open();
		$ret = $folder->get_all_content();
		$this->assertEqual(count($ret), count($folder->folders) + count($folder->files) );
	}

	function test_delete()
	{
		$path = dirname(__FILE__) . '\tmp_dir';
		
		$folder = new Folder($path);		
		$this->assertTrue(is_dir($path));
		$folder->delete();
		$this->assertFalse(file_exists($path));
	}
	
}
?>