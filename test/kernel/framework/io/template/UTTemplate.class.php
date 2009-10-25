<?php

require_once 'header.php';

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php'; //Fonctions de base.

import('io/template');

unset($Errorh);

class UTtemplate extends PHPBoostUnitTestCase {

	function test()
	{
		$tpl = new Template();
		$this->check_methods($tpl);
	}
	
	function test_constructor()
	{
		TODO(__FILE__, __METHOD__);
	}

	function test_set_filenames()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_get_module_data_path()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_assign_vars()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_assign_block_vars()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_unassign_block_vars()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_parse()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_pparse()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test_copy()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test__check_file()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test__check_cache_file()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test__load()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test__include()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test__parse()
	{
		TODO(__FILE__, __METHOD__);
	}	
	
	function test__protect_from_inject()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test__parse_blocks_vars()
	{
		TODO(__FILE__, __METHOD__);
	}
	
	function test__parse_blocks()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test__parse_conditionnal_blocks()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test__clean()
	{
		TODO(__FILE__, __METHOD__);
	}
		
	function test__save()
	{
		TODO(__FILE__, __METHOD__);
	}
		
}
?>