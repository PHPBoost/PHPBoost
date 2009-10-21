<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/begin.php';

import('core/Cache');

unset($Errorh);

class Errors_mock extends Errors
{
	function handler($errstr, $errno, $errline = '', $errfile = '', $tpl_cond = '', $archive = false, $stop = true)
	{
		echo '<br />'.$errno.'-'.$errstr.'-'.$errline.'-'.$errfile.'<br />';
	}
}

$Errorh = new Errors_mock();

function clean_string($str)
{
	$tmp = str_replace("<?php\n", "", $str);
	$tmp = str_replace("\n?>", "", $tmp);
	return $tmp;
}

class UTcache extends PHPBoostUnitTestCase {

	function test()
	{
		$cache = new Cache();	
		$this->check_methods($cache);
	}

	function test_constructor()
	{
		$cache = new Cache();
		$this->assertIsA($cache, 'Cache');
	}

	/**
	* Fonctions locales
	*/
	function test__get_modules()
	{
		$cache = new Cache();
		$ret = $cache->_get_modules();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));

	}
	
	function test__get_menus()
	{
		$cache = new Cache();
		/*
		$ret = $cache->_get_menus();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
		*/
		TODO(__FILE__, __METHOD__);
	}
	
	function test__get_config()
	{
		$cache = new Cache();
		$ret = $cache->_get_config();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
	}
	
	function test__get_debug()
	{
		$cache = new Cache();
		$ret = $cache->_get_debug();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
	}
	
	function test__get_htaccess()
	{
		$cache = new Cache();
		$ret = $cache->_get_htaccess();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(empty($ret) OR is_string($ret));
	}
	
	function test__get_css()
	{
		$cache = new Cache();
		$ret = $cache->_get_css();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
	}
	
	function test__get_themes()
	{
		$cache = new Cache();
		$ret = $cache->_get_themes();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
	}
	
	function test__get_langs()
	{
		$cache = new Cache();
		$ret = $cache->_get_langs();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
	}
	
	function test__get_day()
	{
		$cache = new Cache();
		$ret = $cache->_get_day();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
	}
	
	function test_get_groups()
	{
		$cache = new Cache();
		$ret = $cache->_get_groups();
		$file = PATH_TO_ROOT . '/cache/groups.php';
		if (file_exists($file)) {
			$tmp = file_get_contents($file);
			$tmp = clean_string($tmp);
			$this->assertEqual($tmp, $ret);
		} else {
			echo "<br>";
			var_dump($ret);
			echo "<br>";
			$this->assertTrue(is_string($ret));
		}
	}
	
	function test__get_member()
	{
		$cache = new Cache();
		$ret = $cache->_get_member();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
	}
	
	function test__get_ranks()
	{
		$cache = new Cache();
		$ret = $cache->_get_ranks();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
	}
	
	function test__get_uploads()
	{
		$cache = new Cache();
		$ret = $cache->_get_uploads();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
	}
	
	function test__get_com()
	{
		$cache = new Cache();
		$ret = $cache->_get_com();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
	}
	
	function test__get_writingpad()
	{
		$cache = new Cache();
		$ret = $cache->_get_writingpad();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
	}
	
	function test__get_smileys()
	{
		$cache = new Cache();
		$ret = $cache->_get_smileys();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
	}
	
	function test__get_stats()
	{
		$cache = new Cache();
		$ret = $cache->_get_stats();
		echo "<br>";
		var_dump($ret);
		echo "<br>";
		$this->assertTrue(is_string($ret));
	}
	
	/**
	* fonctions publiques
	*/
	function test_load()
	{
	}
	
	function test_generate_file()
	{
	}
	
	function test_generate_module_file()
	{
	}
	
	function test_generate_all_files()
	{
	}
	
	function test_generate_all_modules()
	{
	}
	
	function test_delete_file()
	{
	}
	
	function test_write()
	{
	}
	
}
