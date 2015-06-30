<?php


class FunctionsIncTest extends PHPBoostUnitTestCase {

	function test_retrieve()
	{
		// TODO(__METHOD__);
	}

	function test_numeric()
	{
		$ret = NumberHelper::numeric(10);
		self::assertTrue(is_int($ret) AND $ret == 10);
		$ret = NumberHelper::numeric(10.55, 'float');
		self::assertTrue(is_float($ret) AND $ret == 10.55);
		$ret = NumberHelper::numeric('bidon');
		self::assertTrue(is_int($ret) AND $ret == 0);
	}
	
	function test_display_editor()
	{
        // TODO(__METHOD__);
//		$ret = display_editor();
//		self::assertTrue(is_string($ret));
	}
	
	function test_display_comments()
	{
		// TODO(__METHOD__);
	}
	
	function test_load_module_lang()
	{
		// TODO(__METHOD__);
	}
	
	function test_load_ini_file()
	{
		// TODO(__METHOD__);
	}
	
	function test_find_require_dir()
	{
		// TODO(__METHOD__);
	}
	
	function test_get_module_name()
	{
		// TODO(__METHOD__);
	}
	
	function test_redirect_confirm()
	{
		// FAIRE EN TEST WEB
	}
	
	function test_get_home_page()
	{
		// TODO(__METHOD__);
	}

	function test_com_display_link()
	{
		// TODO(__METHOD__);
	}
	
	function test_check_mail()
	{
		$ret = AppContext::get_mail_service()->is_mail_valid('toto@test.fr');
		self::assertTrue($ret);
		$ret = AppContext::get_mail_service()->is_mail_valid('toto_bidon');
		self::assertFalse($ret);
	}
	
	function test_url()
	{
		// TODO(__METHOD__);
	}
	
	function test_gmdate_format()
	{
		// TODO(__METHOD__);
	}
	
	function test_delete_file()
	{
		// TODO(__METHOD__);
	}
	
	function test_delete_directory()
	{
		// TODO(__METHOD__);
	}
	
	function test_pages_displayed()
	{
		// TODO(__METHOD__);
	}
	
	function test_file_get_contents_emulate()
	{
		// TODO(__METHOD__);
	}
	
	function test_html_entity_decode()
	{
		// TODO(__METHOD__);
	}
	
	function test_htmlspecialchars_decode()
	{
		// TODO(__METHOD__);
	}
	
	function test_array_combine()
	{
		// TODO(__METHOD__);
	}
	
	function test_get_feed_menu()
	{
		// TODO(__METHOD__);
	}
	
	function test_strhash()
	{
		// TODO(__METHOD__);
	}
	
	function test_get_uid()
	{
		$ret = AppContext::get_uid();
		self::assertEquals($ret, 1764);
		$ret = AppContext::get_uid();
		self::assertEquals($ret, 1765);		
	}
	
	function test_import()
	{
		
		self::assertTrue(class_exists('Date'));
	}
	
	function test_req()
	{
        // TODO(__METHOD__);
	}
	
	function test_inc()
	{
        // TODO(__METHOD__);
	}
}
?>