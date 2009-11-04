<?php
/*##################################################
 *		                 UserAccountsConfig.class.php
 *                            -------------------
 *   begin                : October 28, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/




/**
 * This class contains all the data related to the user accounts configuration.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class UserAccountsConfig extends DefaultConfigData
{
	/**
	 * Name of the property indicating if the interaction with users is enabled
	 */
	const USER_ACCOUNTS_ENABLED_PROPERTY = 'user_accounts_enabled';
	/**
	 * Name of the property containing the welcome message visible at the entry of the member zone
	 */
	const WELCOME_MESSAGE_PROPERTY = 'welcome_message';
	/**
	 * Name of the property indicating if guests can register
	 */
	const REGISTRATION_ENABLED_PROPERTY = 'registration_enabled';
	/**
	 * Name of the property containing the registration agreement that user must accept
	 * to register on the site
	 */
	const REGISTRATION_AGREEMENT_PROPERTY = 'registration_agreement';
	/**
	 * Name of the property indicating if the captcha is enabled when users register to prevent
	 * robot registrations.
	 */
	const REGISTRATION_CAPTCHA_ENABLED_PROPERTY = 'registration_captcha_enabled';
	/**
	 * Name of the property containing the ifficulty level of the registration captcha 
	 * (if it's enabled)
	 */
	const REGISTRATION_CAPTCHA_DIFFICULTY_PROPERTY = 'registration_captcha_difficulty';
	/**
	 * Name of the property containing the time (in days) after which a member account
	 * which hasn't been activated will be automatically removed.
	 */
	const UNACTIVATED_ACCOUNTS_TIMEOUT_PROPERTY = 'unactivated_accounts_timeout';
	/**
	 * Name of the property indicating if the users can choose their theme (value is false) 
	 * or if the have to use the site default one (value is true).
	 */
	const FORCE_USERS_THEME_PROPERTY = 'force_users_theme';

	/**
	 * Tells whether the interaction between members is enabled or not.
	 */
	public function get_user_accounts_enabled()
	{
		return $this->get_property(USER_ACCOUNTS_ENABLED_PROPERTY);
	}

	/**
	 * Enables the interaction between members.
	 */
	public function enable_user_accounts()
	{
		$this->set_property(USER_ACCOUNTS_ENABLED_PROPERTY, true);
	}

	/**
	 * Disables the interaction between members.
	 */
	public function disable_user_accounts()
	{
		$this->set_property(USER_ACCOUNTS_ENABLED_PROPERTY, false);
	}

	/**
	 * Returns the welcome message
	 * @return string the message
	 */
	public function get_welcome_message()
	{
		return $this->get_property(WELCOME_MESSAGE_PROPERTY);
	}

	/**
	 * Sets the welcome message displayed on the member profile main page.
	 * @param string $message The welcome message
	 */
	public function set_welcome_message($message)
	{
		$this->set_property(WELCOME_MESSAGE_PROPERTY, $message);
	}

	/**
	 * Tells whether the member registration is enabled
	 * @return bool true if it is, false otherwise.
	 */
	public function is_registration_enabled()
	{
		return $this->get_property(REGISTRATION_ENABLED_PROPERTY);
	}

	/**
	 * Enables the member registration
	 */
	public function enable_registration()
	{
		$this->set_property(REGISTRATION_ENABLED_PROPERTY, true);
	}

	/**
	 * Disables the member registration
	 */
	public function disable_registration()
	{
		$this->set_property(REGISTRATION_ENABLED_PROPERTY, false);
	}

	/**
	 * Gets the agreement that users must accept to register
	 * @return unknown_type
	 */
	public function get_registration_agreement()
	{
		return $this->get_property(REGISTRATION_AGREEMENT_PROPERTY);
	}

	/**
	 * Sets the agreement that users mut accept to register
	 * @param $agreement The agreement
	 */
	public function set_registration_agreement($agreement)
	{
		$this->set_property(REGISTRATION_AGREEMENT_PROPERTY, $agreement);
	}

	/**
	 * Tells whether the captcha is enabled in the registration form.
	 * @return bool true if enabled, false otherwise
	 */
	public function is_registration_captcha_enabled()
	{
		return $this->get_property(REGISTRATION_CAPTCHA_ENABLED_PROPERTY);
	}

	/**
	 * Enables the registration captcha.
	 */
	public function enable_registration_captcha()
	{
		$this->set_property(REGISTRATION_CAPTCHA_ENABLED_PROPERTY, true);
	}

	/**
	 * Disables the registration captcha.
	 */
	public function disable_registration_captcha()
	{
		$this->set_property(REGISTRATION_CAPTCHA_ENABLED_PROPERTY, false);
	}
	
	/**
	 * Returns the difficulty level of the registration captcha
	 * @return int The level
	 */
	public function get_registration_captcha_difficulty()
	{
		return $this->get_property(REGISTRATION_CAPTCHA_DIFFICULTY_PROPERTY);
	}
	
	/**
	 * Sets the difficulty level of the registration captcha
	 * @param $level The level
	 */
	public function set_registration_captcha_difficulty($level)
	{
		$this->set_property(REGISTRATION_CAPTCHA_DIFFICULTY_PROPERTY, $level);
	}

	/**
	 * Returns the delay after which unactivated member accounts will be automatically removed
	 * @return The duration (in days)
	 */
	public function get_unactivated_accounts_timeout()
	{
		return $this->get_property(UNACTIVATED_ACCOUNTS_TIMEOUT_PROPERTY);
	}
	
	public function is_users_theme_forced()
	{
		return $this->get_property(FORCE_USERS_THEME_PROPERTY);
	}
	
	public function force_users_theme()
	{
		$this->set_property(FORCE_USERS_THEME_PROPERTY, true);
	}
	
	public function do_not_force_users_theme()
	{
		$this->set_property(FORCE_USERS_THEME_PROPERTY, false);
	}
	
	/**
	 * Sets the duration of the unactivated accounts timeout
	 * @param $duration The duration (in days)
	 */
	public function set_unactivated_accounts_timeout($duration)
	{
		$this->set_property(UNACTIVATED_ACCOUNTS_TIMEOUT_PROPERTY, $duration);
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/config/DefaultConfigData#set_default_values()
	 */
	public function set_default_values()
	{
		global $LANG;
			
		$this->enable_registration();
		$this->set_welcome_message($LANG['site_config_msg_mbr']);
		$this->set_registration_agreement($LANG['register_agreement']);
		$this->enable_user_accounts();
		$this->enable_registration_captcha();
		$this->set_registration_captcha_difficulty(1);
		$this->set_unactivated_accounts_timeout(20);
	}

	/**
	 * Returns the configuration.
	 * @return UserAccountsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'user-accounts');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 * @param UserAccountsConfig $config The configuration to push in the database.
	 */
	public static function save(UserAccountsConfig $config)
	{
		ConfigManager::save('kernel', $config, 'user-accounts');
	}
}