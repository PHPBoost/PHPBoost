<?php

import('core/config/UserAccountsConfig');

class UTUserAccountsConfig extends PHPBoostUnitTestCase
{
	public function __construct()
	{
	}
	
	public function test_are_user_accounts_enabled()
	{
		$config = new UserAccountsConfig();
				die('ici');
		$this->assertEquals(true, $config->are_user_accounts_enabled());
	}

	public function test_enable_user_accounts()
	{
		$config = new UserAccountsConfig();
		$config->enable_user_accounts();
		$this->assertEquals(true, $config->are_user_accounts_enabled());
	}

	public function test_disable_user_accounts()
	{
		$config = new UserAccountsConfig();
		$config->disable_user_accounts();
		$this->assertEquals(false, $config->are_user_accounts_enabled());
	}

	public function test_get_welcome_message()
	{
		$config = new UserAccountsConfig();
		$this->assertNotEqual('', $config->get_welcome_message());
	}
}

?>