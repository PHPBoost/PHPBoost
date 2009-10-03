<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php'; //Fonctions de base.

import('io/filesystem/file');

unset($Errorh);

class UnitTest_file extends MY_UnitTestCase {

	function test()
	{
		$path = PATH_TO_ROOT . '/kernel';		
		$file = new File($path);
		$this->MY_check_methods($file);
	}
	
	function test_constructor()
	{
		// DO NOTHING
	}

	function test_file()
	{	
		$path = PATH_TO_ROOT . '/kernel';
		
		$file = new File($path);
		$this->assertEqual($file->path, $path);
		$this->assertEqual($file->mode, READ_WRITE);
		$this->assertFalse($file->is_open());
		unset($file);
		
		$file = new File($path, READ);
		$this->assertEqual($file->path, $path);
		$this->assertEqual($file->mode, READ);
		unset($file);
		
		$file = new File($path, WRITE);
		$this->assertEqual($file->path, $path);
		$this->assertEqual($file->mode, WRITE);
		unset($file);
		
		$path = PATH_TO_ROOT . '/kernel/begin.php';
		
		$file = new File($path, READ_WRITE, OPEN_NOW);
		$this->assertEqual($file->path, $path);
		$this->assertTrue($file->is_open());
		unset($file);
		
		$path = 'toto.tmp';
		
		$file = new File($path, READ_WRITE, OPEN_NOW);
		$this->assertEqual($file->path, $path);
		$this->assertTrue(file_exists($path));
		$file->delete();
	}

	function test_open()
	{
		$path = PATH_TO_ROOT . '/kernel/begin.php';
		
		$file = new File($path);
		$file->open();
		$this->assertTrue(!empty($file->lines));
		$this->assertTrue(!empty($file->contents));
	}
	
	function test_get_contents()
	{
		$path = PATH_TO_ROOT . '/kernel/begin.php';
		
		$file = new File($path);
		$file->open();
		$ret = $file->get_contents();
		$this->assertTrue(!empty($ret) AND is_string($ret));
		
		$ret = $file->get_contents(10);
		$this->assertTrue(!empty($ret) AND is_string($ret));
		
		$ret = $file->get_contents(10,filesize($path));
		$this->assertTrue(!empty($ret) AND is_string($ret));
		unset($file);

		echo '<br />get_contents - Test exception sur lecture si PAS MODE READ<br />';	
		
		$file = new File($path, WRITE);
		$file->open();
		$ret = $file->get_contents();
		$this->assertError();
		$this->assertFalse($ret);
	}
	
	function test_get_lines()
	{
		$path = PATH_TO_ROOT . '/kernel/begin.php';
		
		$file = new File($path);
		$file->open();
		$ret = $file->get_lines();
		$this->assertTrue(!empty($ret) AND is_array($ret) AND (count($ret) == count($file->lines)));
		
		$ret = $file->get_lines(0, 10);
		$this->assertTrue(!empty($ret) AND is_array($ret) AND (count($ret) == 10));
		
		$ret = $file->get_lines(10, count($file->lines));
		$this->assertTrue(!empty($ret) AND is_array($ret) AND (count($ret) == count($file->lines)-10));
		unset($file);

		echo '<br />get_lines - Test exception sur lecture si PAS MODE READ<br />';
		
		$file = new File($path, WRITE);
		$file->open();
		$ret = $file->get_lines();
		$this->assertError();
		$this->assertFalse($ret);	
	}
	
	function test_write()
	{
		TODO(__FILE__, __METHOD__);
	}

	function test_close()
	{
		TODO(__FILE__, __METHOD__);
	}

	function test_delete()
	{
		$path = 'toto.tmp';
		
		$file = new File($path, READ_WRITE, OPEN_NOW);
		$this->assertEqual($file->path, $path);
		$this->assertTrue(file_exists($path));
		
		$file->delete();
		$this->assertFalse(file_exists($path));
	}

	function test_lock_unlock()
	{
		$path = 'toto.tmp';
		
		$file = new File($path, READ_WRITE, OPEN_NOW);
		$this->assertEqual($file->path, $path);
		$this->assertTrue(file_exists($path));
		
		$file->lock();
		$this->assertTrue(true);
		
		$file->unlock();
		$file->delete();
	}
	
}
