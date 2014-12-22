<?php

class UserAccountsConfigTest extends PHPBoostUnitTestCase
{
	public function __construct()
	{
	}

	public function test_get_member_accounts_validation_method()
	{
		$config = self::prepare_config();
		self::assertEquals(1, $config->get_member_accounts_validation_method());
	}

	public function test_set_member_accounts_validation_method()
	{
		$config = self::prepare_config();
		$config->set_member_accounts_validation_method(0);
		self::assertEquals(0,  $config->get_member_accounts_validation_method());
		$config->set_member_accounts_validation_method(2);
		self::assertEquals(2,  $config->get_member_accounts_validation_method());
		$config->set_member_accounts_validation_method(3);
		self::assertEquals(0,  $config->get_member_accounts_validation_method());
	}

	public function test_get_welcome_message()
	{
		$config = self::prepare_config();
		self::assertEquals('toto', $config->get_welcome_message());
	}

	public function test_set_welcome_message()
	{
		$config = self::prepare_config();
		$config->set_welcome_message('tutu');
		self::assertEquals('tutu', $config->get_welcome_message());
	}

	public function test_is_registration_enabled()
	{
		$config = self::prepare_config();
		self::assertEquals(true, $config->is_registration_enabled());
	}

	public function test_enable_registration()
	{
		$config = self::prepare_config();
		$config->enable_registration();
		self::assertEquals(true, $config->is_registration_enabled());
	}

	public function test_disable_registration()
	{
		$config = self::prepare_config();
		$config->disable_registration();
		self::assertEquals(false, $config->is_registration_enabled());
	}

	public function test_is_registration_captcha_enabled()
	{
		$config = self::prepare_config();
		self::assertEquals(@extension_loaded('gd'), $config->is_registration_captcha_enabled());
	}

	public function test_enable_registration_captcha()
	{
		$config = self::prepare_config();
		$config->enable_registration_captcha();
		self::assertEquals(true, $config->is_registration_captcha_enabled());
	}

	public function test_disable_registration_captcha()
	{
		$config = self::prepare_config();
		$config->disable_registration_captcha();
		self::assertEquals(false, $config->is_registration_captcha_enabled());
	}

	public function test_get_registration_captcha_difficulty()
	{
		$config = self::prepare_config();
		self::assertEquals(1, $config->get_registration_captcha_difficulty());
	}

	public function test_set_registration_captcha_difficulty()
	{
		$config = self::prepare_config();
		$config->set_registration_captcha_difficulty(3);
		self::assertEquals(3, $config->get_registration_captcha_difficulty());
		$config->set_registration_captcha_difficulty(5);
		self::assertEquals(2, $config->get_registration_captcha_difficulty());
		$config->set_registration_captcha_difficulty(-1);
		self::assertEquals(2, $config->get_registration_captcha_difficulty());
	}

	public function test_get_unactivated_accounts_timeout()
	{
		$config = self::prepare_config();
		self::assertEquals(20, $config->get_unactivated_accounts_timeout());
	}

	public function test_set_unactivated_accounts_timeout()
	{
		$config = self::prepare_config();
		$config->set_unactivated_accounts_timeout(50);
		self::assertEquals(50, $config->get_unactivated_accounts_timeout());
	}

	public function test_is_users_theme_forced()
	{
		$config = self::prepare_config();
		self::assertEquals(false, $config->is_users_theme_forced());
	}

	public function test_force_users_theme()
	{
		$config = self::prepare_config();
		$config->force_users_theme();
		self::assertEquals(true, $config->is_users_theme_forced());
	}

	public function test_dont_force_users_theme()
	{
		$config = self::prepare_config();
		$config->dont_force_users_theme();
		self::assertEquals(false, $config->is_users_theme_forced());
	}

	public function test_is_avatar_upload_enabled()
	{
		$config = self::prepare_config();
		self::assertEquals(true, $config->is_avatar_upload_enabled());
	}

	public function test_enable_avatar_upload()
	{
		$config = self::prepare_config();
		$config->enable_avatar_upload();
		self::assertEquals(true, $config->is_avatar_upload_enabled());
	}

	public function test_disable_avatar_upload()
	{
		$config = self::prepare_config();
		$config->disable_avatar_upload();
		self::assertEquals(false, $config->is_avatar_upload_enabled());
	}

	public function test_get_default_avatar_name()
	{
		$config = self::prepare_config();
		self::assertEquals('no_avatar.png', $config->get_default_avatar_name());
	}

	public function test_set_default_avatar_name()
	{
		$config = self::prepare_config();
		$config->set_default_avatar_name('avatar.png');
		self::assertEquals('avatar.png', $config->get_default_avatar_name());
		$config->set_default_avatar_name('');
		self::assertEquals('no_avatar.png', $config->get_default_avatar_name());
	}

	public function test_get_max_avatar_width()
	{
		$config = self::prepare_config();
		self::assertEquals(120, $config->get_max_avatar_width());
	}

	public function test_set_max_avatar_width()
	{
		$config = self::prepare_config();
		$config->set_max_avatar_width(150);
		self::assertEquals(150, $config->get_max_avatar_width());
	}

	public function test_get_max_avatar_height()
	{
		$config = self::prepare_config();
		self::assertEquals(120, $config->get_max_avatar_height());
	}

	public function test_set_max_avatar_height()
	{
		$config = self::prepare_config();
		$config->set_max_avatar_height(150);
		self::assertEquals(150, $config->get_max_avatar_height());
	}

	public function test_get_max_avatar_weight()
	{
		$config = self::prepare_config();
		self::assertEquals(20, $config->get_max_avatar_weight());
	}

	public function test_set_max_avatar_weight()
	{
		$config = self::prepare_config();
		$config->set_max_avatar_weight(50);
		self::assertEquals(50, $config->get_max_avatar_weight());
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