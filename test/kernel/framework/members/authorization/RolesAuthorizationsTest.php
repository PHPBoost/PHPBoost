<?php

class RolesAuthorizationsTest extends PHPBoostUnitTestCase
{
	function test_simple()
	{
		$array = array('r1' => 1, 'r0' => 1, 'r-1' => 1);
		$auths = new RolesAuthorizations($array);
		self::assertEquals($array, $auths->build_auth_array());
	}

	function test_incorrect_levels()
	{
		$array = array('r1' => 1, 'r0' => 0, 'r-1' => 1);
		$auths = new RolesAuthorizations($array);
		self::assertEquals(array('r1' => 1), $auths->build_auth_array());
	}

	function test_with_groups()
	{
		$array = array('1' => 1);
		$auths = new RolesAuthorizations($array);
		self::assertEquals($array, $auths->build_auth_array());
	}

	function test_with_incorrect_groups()
	{
		$array = array('1' => 1, '2' => 1, '3' => 0);
		$auths = new RolesAuthorizations($array);
		unset($array['3']);
		self::assertEquals($array, $auths->build_auth_array());
	}
	
	function test_with_legacy_groups()
	{
		$array = array('1' => 1);
		$auths = new RolesAuthorizations($array);
		self::assertEquals(array('1' => 1), $auths->build_auth_array());
	}
	
	function test_with_users()
	{
		$array = array('m1' => 1);
		$auths = new RolesAuthorizations($array);
		self::assertEquals($array, $auths->build_auth_array());
	}
	
	function test_with_incorrect_users()
	{
		$array = array('m1' => 1, 'm2' => 0);
		$auths = new RolesAuthorizations($array);
		unset($array['m2']);
		self::assertEquals($array, $auths->build_auth_array());
	}
	
	function test_with_complete_auth()
	{
		$array = array('r1' => 1, 'r0' => 1, 'r-1' => 1, 'm1' => 1, '4' => 1);
		$auths = new RolesAuthorizations($array);
		self::assertEquals($array, $auths->build_auth_array());
	}
}
?>