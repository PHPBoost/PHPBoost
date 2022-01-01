<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 10 25
 * @since       PHPBoost 5.1 - 2015 07 17
*/

class SecurityConfig extends AbstractConfigData
{
	const INTERNAL_PASSWORD_MIN_LENGTH = 'internal_password_min_length';
	const INTERNAL_PASSWORD_STRENGTH = 'internal_password_strength';
	const LOGIN_AND_EMAIL_FORBIDDEN_IN_PASSWORD = 'login_and_email_forbidden_in_password';
	const FORBIDDEN_MAIL_DOMAINS = 'forbidden_mail_domains';

	const PASSWORD_STRENGTH_WEAK = 'weak';
	const PASSWORD_STRENGTH_MEDIUM = 'medium';
	const PASSWORD_STRENGTH_STRONG = 'strong';
	const PASSWORD_STRENGTH_VERY_STRONG = 'very_strong';

	public function get_internal_password_min_length()
	{
		return $this->get_property(self::INTERNAL_PASSWORD_MIN_LENGTH);
	}

	public function set_internal_password_min_length($value)
	{
		$this->set_property(self::INTERNAL_PASSWORD_MIN_LENGTH, $value);
	}

	public function get_internal_password_strength()
	{
		return $this->get_property(self::INTERNAL_PASSWORD_STRENGTH);
	}

	public function set_internal_password_strength($value)
	{
		$this->set_property(self::INTERNAL_PASSWORD_STRENGTH, $value);
	}

	public function forbid_login_and_email_in_password()
	{
		$this->set_property(self::LOGIN_AND_EMAIL_FORBIDDEN_IN_PASSWORD, true);
	}

	public function allow_login_and_email_in_password()
	{
		$this->set_property(self::LOGIN_AND_EMAIL_FORBIDDEN_IN_PASSWORD, false);
	}

	public function are_login_and_email_forbidden_in_password()
	{
		return $this->get_property(self::LOGIN_AND_EMAIL_FORBIDDEN_IN_PASSWORD);
	}

	public function get_forbidden_mail_domains()
	{
		return $this->get_property(self::FORBIDDEN_MAIL_DOMAINS);
	}

	public function set_forbidden_mail_domains(array $value)
	{
		$this->set_property(self::FORBIDDEN_MAIL_DOMAINS, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::INTERNAL_PASSWORD_MIN_LENGTH => 6,
			self::INTERNAL_PASSWORD_STRENGTH => self::PASSWORD_STRENGTH_WEAK,
			self::LOGIN_AND_EMAIL_FORBIDDEN_IN_PASSWORD => false,
			self::FORBIDDEN_MAIL_DOMAINS => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return SecurityConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'security');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'security');
	}
}
?>
