<?php

function clean_string($str)
{
	$tmp = str_replace("<?php\n", "", $str);
	$tmp = str_replace("\n?>", "", $tmp);
	return $tmp;
}

class cacheTest extends PHPBoostUnitTestCase
{
//	/**
//	* Fonctions locales
//	*/
//	function test__get_modules()
//	{
//		$cache = new Cache();
//		$ret = $cache->_get_modules();
//		echo "<br>";
//		var_dump($ret);
//		echo "<br>";
//		self::assertTrue(is_string($ret));
//
//	}
//	
//	function test__get_menus()
//	{
//		$cache = new Cache();
//		/*
//		$ret = $cache->_get_menus();
//		echo "<br>";
//		var_dump($ret);
//		echo "<br>";
//		self::assertTrue(is_string($ret));
//		*/
//		TODO(__FILE__, __METHOD__);
//	}
//	
//	function test__get_config()
//	{
//		$cache = new Cache();
//		$ret = $cache->_get_config();
//		echo "<br>";
//		var_dump($ret);
//		echo "<br>";
//		self::assertTrue(is_string($ret));
//	}
//	
//	function test__get_debug()
//	{
//		$cache = new Cache();
//		$ret = $cache->_get_debug();
//		echo "<br>";
//		var_dump($ret);
//		echo "<br>";
//		self::assertTrue(is_string($ret));
//	}
//	
//	function test__get_themes()
//	{
//		$cache = new Cache();
//		$ret = $cache->_get_themes();
//		echo "<br>";
//		var_dump($ret);
//		echo "<br>";
//		self::assertTrue(is_string($ret));
//	}
//	
//	function test__get_langs()
//	{
//		$cache = new Cache();
//		$ret = $cache->_get_langs();
//		echo "<br>";
//		var_dump($ret);
//		echo "<br>";
//		self::assertTrue(is_string($ret));
//	}
//	
//	function test__get_day()
//	{
//		$cache = new Cache();
//		$ret = $cache->_get_day();
//		echo "<br>";
//		var_dump($ret);
//		echo "<br>";
//		self::assertTrue(is_string($ret));
//	}
//	
//	function test__get_member()
//	{
//		$cache = new Cache();
//		$ret = $cache->_get_member();
//		echo "<br>";
//		var_dump($ret);
//		echo "<br>";
//		self::assertTrue(is_string($ret));
//	}
//	
//	function test__get_ranks()
//	{
//		$cache = new Cache();
//		$ret = $cache->_get_ranks();
//		echo "<br>";
//		var_dump($ret);
//		echo "<br>";
//		self::assertTrue(is_string($ret));
//	}
//	
//	function test__get_uploads()
//	{
//		$cache = new Cache();
//		$ret = $cache->_get_uploads();
//		echo "<br>";
//		var_dump($ret);
//		echo "<br>";
//		self::assertTrue(is_string($ret));
//	}
//	
//	function test__get_com()
//	{
//		$cache = new Cache();
//		$ret = $cache->_get_com();
//		echo "<br>";
//		var_dump($ret);
//		echo "<br>";
//		self::assertTrue(is_string($ret));
//	}
//	
//	function test__get_smileys()
//	{
//		$cache = new Cache();
//		$ret = $cache->_get_smileys();
//		echo "<br>";
//		var_dump($ret);
//		echo "<br>";
//		self::assertTrue(is_string($ret));
//	}
//	
//	function test__get_stats()
//	{
//		$cache = new Cache();
//		$ret = $cache->_get_stats();
//		echo "<br>";
//		var_dump($ret);
//		echo "<br>";
//		self::assertTrue(is_string($ret));
//	}
//	
//	/**
//	* fonctions publiques
//	*/
//	function test_load()
//	{
//	}
//	
//	function test_generate_file()
//	{
//	}
//	
//	function test_generate_module_file()
//	{
//	}
//	
//	function test_generate_all_files()
//	{
//	}
//	
//	function test_generate_all_modules()
//	{
//	}
//	
//	function test_delete_file()
//	{
//	}
//	
//	function test_write()
//	{
//	}
}
