<?php
class AuthorizationsSettingsTest extends PHPBoostUnitTestCase
{
	public function test_build_auth_array()
	{
		$action1 = new ActionAuthorization('toto', 1);
		$action1->set_roles_auths(new RolesAuthorizations(array('r1' => 1, '2' => 1, 'm4' => 1)));

		$action2 = new ActionAuthorization('toto', 2);
		$action2->set_roles_auths(new RolesAuthorizations(array('r1' => 1, 'r0' => 1, '1' => 1, '2' => 1)));

		$settings = new AuthorizationsSettings(array($action1, $action2));

		self::assertEquals(array('r1' => 3, 'r0' => 2, '1' => 2, '2' => 3, 'm4' => 1), $settings->build_auth_array());
	}

	public function test_build_from_auth_array()
	{
		$action1 = new ActionAuthorization('toto', 1);
		$action2 = new ActionAuthorization('toto', 2);
		$settings = new AuthorizationsSettings(array($action1, $action2));

		$settings->build_from_auth_array(array('r1' => 3, 'r0' => 2, '1' => 2, '2' => 3, 'm4' => 1));

		self::assertEquals(array('r1' => 1, '2' => 1, 'm4' => 1), $action1->get_roles_auths()->build_auth_array());
		self::assertEquals(array('r1' => 1, 'r0' => 1, '1' => 1, '2' => 1), $action2->get_roles_auths()->build_auth_array());
	}
}
?>