<?php


class FunctionsIncTest extends PHPBoostUnitTestCase {

	function test_retrieve()
	{
		TODO(__METHOD__);
	}
	
	function test_strprotect()
	{
		TODO(__METHOD__);
	}
	
	function test_numeric()
	{
		$ret = numeric(10);
		self::assertTrue(is_int($ret) AND $ret == 10);
		$ret = numeric(10.55, 'float');
		self::assertTrue(is_float($ret) AND $ret == 10.55);
		$ret = numeric('bidon');
		self::assertTrue(is_int($ret) AND $ret == 0);
	}
	
	function test_get_utheme()
	{
		TODO(__METHOD__);
	}
	
	function test_get_ulang()
	{
		TODO(__METHOD__);
	}
	
	function test_wordwrap_html()
	{
		TODO(__METHOD__);
	}
	
	function test_substr_html()
	{
		TODO(__METHOD__);
	}
	
	function test_display_editor()
	{
        TODO(__METHOD__);
//		$ret = display_editor();
//		self::assertTrue(is_string($ret));
	}
	
	function test_display_comments()
	{
		TODO(__METHOD__);
	}
	
	function test_load_module_lang()
	{
		TODO(__METHOD__);
	}
	
	function test_load_ini_file()
	{
		TODO(__METHOD__);
	}
	
	function test_parse_ini_array()
	{
		TODO(__METHOD__);
	}
	
	function test_get_ini_config()
	{
		TODO(__METHOD__);
	}
	function test_find_require_dir()
	{
		TODO(__METHOD__);
	}
	
	function test_get_module_name()
	{
		TODO(__METHOD__);
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
		TODO(__METHOD__);
	}
	
	function test_check_nbr_links()
	{
		TODO(__METHOD__);
	}
	
	function test_com_display_link()
	{
		TODO(__METHOD__);
	}
	
	function test_check_mail()
	{
		$ret = check_mail('toto@test.fr');
		self::assertTrue($ret);
		$ret = check_mail('toto_bidon');
		self::assertFalse($ret);
	}
	
	function test_strparse()
	{
		TODO(__METHOD__);
	}
	
	function test_unparse()
	{
		TODO(__METHOD__);
	}
	
	function test_second_parse()
	{
		TODO(__METHOD__);
	}
	
	function test_url()
	{
		TODO(__METHOD__);
	}
	function test_url_encode_rewrite()
	{
		TODO(__METHOD__);
	}
	
	function test_gmdate_format()
	{
		TODO(__METHOD__);
	}
	
	function test_strtotimestamp()
	{
		TODO(__METHOD__);
	}
	
	function test_strtodate()
	{
		TODO(__METHOD__);
	}
	
	function test_delete_file()
	{
		TODO(__METHOD__);
	}
	
	function test_delete_directory()
	{
		TODO(__METHOD__);
	}
	
	function test_pages_displayed()
	{
		TODO(__METHOD__);
	}
	
	function test_number_round()
	{
		TODO(__METHOD__);
	}
	
	function test_file_get_contents_emulate()
	{
		TODO(__METHOD__);
	}
	
	function test_html_entity_decode()
	{
		TODO(__METHOD__);
	}
	
	function test_htmlspecialchars_decode()
	{
		TODO(__METHOD__);
	}
	
	function test_array_combine()
	{
		TODO(__METHOD__);
	}
	
	function test_get_feed_menu()
	{
		TODO(__METHOD__);
	}
	
	function test_strhash()
	{
		TODO(__METHOD__);
	}
	
	function test_get_uid()
	{
		$ret = get_uid();
		self::assertEquals($ret, 1764);
		$ret = get_uid();
		self::assertEquals($ret, 1765);		
	}
	
	function test_import()
	{
		
		self::assertTrue(class_exists('Date'));
	}
	
	function test_req()
	{
        TODO(__METHOD__);
	}
	
	function test_inc()
	{
        TODO(__METHOD__);
	}
	
	function test_of_class()
	{
		Global $User;
		
		$ret = of_class($User, 'User');
		self::assertTrue($ret);
		
		$ret = of_class($User, 'Sql');
		self::assertFalse($ret);	
	}

}
?>