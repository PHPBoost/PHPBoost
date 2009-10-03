<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php'; //Fonctions de base.

import('util/captcha');

unset($Errorh);

class UTcaptcha extends PHPBoostUnitTestCase {

	function test()
	{
		$captcha = new Captcha();
		$this->check_methods($captcha);
	}

	function test_constructor()
	{
		TODO(__FILE__, __METHOD__);
	}

	function test_gd_loaded()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_update_instance()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_set_difficulty()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_set_instance()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_set_width()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_set_height()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_set_font()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_is_valid()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_js_require()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test_display_form()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test_display()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test___image_color_allocate_dark()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test__save_user()
	{
		TODO(__FILE__, __METHOD__);
	}
}
?>