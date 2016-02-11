<?php
/*##################################################
 *		                   SecurityConfig.class.php
 *                            -------------------
 *   begin                : July 17, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Maintenance Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Maintenance Public License for more details.
 *
 * You should have received a copy of the GNU Maintenance Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class SecurityConfig extends AbstractConfigData
{
	const INTERNAL_PASSWORD_MIN_LENGTH = 'internal_password_min_length';
	const INTERNAL_PASSWORD_STRENGTH = 'internal_password_strength';
	const LOGIN_AND_EMAIL_FORBIDDEN_IN_PASSWORD = 'login_and_email_forbidden_in_password';
	
	const PASSWORD_STRENGTH_WEAK = 'weak';
	const PASSWORD_STRENGTH_MEDIUM = 'medium';
	const PASSWORD_STRENGTH_STRONG = 'strong';
	
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

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::INTERNAL_PASSWORD_MIN_LENGTH => 6,
			self::INTERNAL_PASSWORD_STRENGTH => self::PASSWORD_STRENGTH_WEAK,
			self::LOGIN_AND_EMAIL_FORBIDDEN_IN_PASSWORD => false
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