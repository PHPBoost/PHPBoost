<?php

import('core/config/UserAccountsConfig');

class UTUserAccountsConfig extends PHPBoostUnitTestCase
{
	public function __construct()
	{
	}

	public function test_are_user_accounts_enabled()
	{
		$config = self::prepare_config();
		$this->assertEquals(true, $config->are_user_accounts_enabled());
	}

	public function test_enable_user_accounts()
	{
		$config = self::prepare_config();
		$config->enable_user_accounts();
		$this->assertEquals(true, $config->are_user_accounts_enabled());
	}

	public function test_disable_user_accounts()
	{
		$config = self::prepare_config();
		$config->disable_user_accounts();
		$this->assertEquals(false, $config->are_user_accounts_enabled());
	}

	public function test_get_welcome_message()
	{
		$config = self::prepare_config();
		$this->assertEquals('toto', $config->get_welcome_message());
	}
	
	public function test_set_welcome_message()
	{
		$config = self::prepare_config();
		$config->set_welcome_message('tutu');
		$this->assertEquals('tutu', $config->get_welcome_message());
	}

	/**
	 * @return UserAccountsConfig
	 */
	private static function prepare_config()
	{
		global $LANG;

		$LANG = array();
		$LANG['site_config_msg_mbr'] = 'toto';
		$LANG['register_agreement'] = 'tata';
		$config = new UserAccountsConfig();
		$config->set_default_values();
		return $config;
	}
}

?>