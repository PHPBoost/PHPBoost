<?php

class ActionAuthorizationTest extends PHPBoostUnitTestCase
{
	public function test_build_auth_array()
	{
		$action = new ActionAuthorization('toto', 2);
		$action->set_roles_auths(new RolesAuthorizations(array('r1' => 1)));
		self::assertEquals(array('r1' => 2), $action->build_auth_array());

		$action->set_bit(4);
		self::assertEquals(array('r1' => 4), $action->build_auth_array());
	}

	public function test_set_roles_auths()
	{
		$action = new ActionAuthorization('toto', 2);
		$action->set_roles_auths(new RolesAuthorizations(array('r1' => 1)));
		self::assertEquals(array('r1' => 2), $action->build_auth_array());
	}

	public function test_build_from_auth_array()
	{
		$action = new ActionAuthorization('toto', 4);
		$action->build_from_auth_array(array('r1' => 4, 'r0' => 4, 'm1' => 4));
		self::assertEquals(new RolesAuthorizations(array('r1' => 1, 'r0' => 1, 'm1' => 1)), $action->get_roles_auths());
	}
}

?>