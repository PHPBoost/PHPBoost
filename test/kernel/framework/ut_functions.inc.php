<?php

require_once 'header.php';

require_once( PATH_TO_ROOT . '/kernel/begin.php');

unset($Errorh);

class UTfunctions_inc extends UnitTestCase {

	function test_retrieve()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_strprotect()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_numeric()
	{
		$ret = numeric(10);
		$this->assertTrue(is_int($ret) AND $ret == 10);
		$ret = numeric(10.55, 'float');
		$this->assertTrue(is_float($ret) AND $ret == 10.55);
		$ret = numeric('bidon');
		$this->assertTrue(is_int($ret) AND $ret == 0);
	}
	
	function test_get_utheme()
	{
		Global $User;
		
		$ret = get_utheme();
		$this->assertEqual($ret, $User->get_attribute('user_theme'));
	}
	
	function test_get_ulang()
	{
		Global $User;
		
		$ret = get_ulang();
		$this->assertEqual($ret, $User->get_attribute('user_lang'));
	}
	
	function test_wordwrap_html()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_substr_html()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_display_editor()
	{
		$ret = display_editor();
		$this->assertTrue(is_string($ret));
	}
	function test_display_comments()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_load_module_lang()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_load_ini_file()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_parse_ini_array()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_get_ini_config()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_find_require_dir()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_get_module_name()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_redirect()
	{
		// FAIRE EN TEST WEB
	}
	function test_redirect_confirm()
	{
		// FAIRE EN TEST WEB
	}
	function test_get_start_page()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_check_nbr_links()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_com_display_link()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_check_mail()
	{
		$ret = check_mail('toto@test.fr');
		$this->assertTrue($ret);
		$ret = check_mail('toto_bidon');
		$this->assertFalse($ret);
	}
	function test_strparse()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_unparse()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_second_parse()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_url()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_url_encode_rewrite()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_gmdate_format()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_strtotimestamp()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_strtodate()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_delete_file()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_delete_directory()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_pages_displayed()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_number_round()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_file_get_contents_emulate()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_html_entity_decode()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_htmlspecialchars_decode()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_array_combine()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_get_feed_menu()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_strhash()
	{
		TODO(__FILE__, __METHOD__);
	}
	function test_get_uid()
	{
		$ret = get_uid();
		$this->assertEqual($ret, 1764);
		$ret = get_uid();
		$this->assertEqual($ret, 1765);		
	}
	
	function test_import()
	{
		import('util/Date');
		$this->assertTrue(class_exists('Date'));
	}
	
	function test_req()
	{
		// la fonction php REQUIRE implique l'arret du script si le fichier n'existe pas => donc pas de code retour
		req('/../phpboost-unit-tests/dummy_include.php');
		$this->assertTrue(defined('DUMMY_INCLUDE'));
	}
	
	function test_inc()
	{
		$ret = inc('/../phpboost-unit-tests/dummy_include.php');
		$this->assertTrue($ret);
		$this->assertTrue(defined('DUMMY_INCLUDE'));
		$ret = inc('/bidon.php');
		if (DEBUG)
		{
			$this->assertError();
			$this->assertError();
		}
		$this->assertFalse($ret);
	}
	
	function test_of_class()
	{
		Global $User;
		
		$ret = of_class($User, 'User');
		$this->assertTrue($ret);
		
		$ret = of_class($User, 'Sql');
		$this->assertFalse($ret);	
	}

}
?>