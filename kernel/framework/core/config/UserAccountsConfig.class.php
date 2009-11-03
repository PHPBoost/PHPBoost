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

import('io/config/DefaultConfigData');
import('util/Date');

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
	const REGISTRATION_ENABLED_PROPERTY = 'registering_enabled';
	/**
	 * Name of the property containing the registering agreement that user must accept
	 * to register on the site
	 */
	const REGISTERING_AGREEMENT_PROPERTY = 'registering_agreement';

	/**
	 * Tells whether the interaction between members is enabled or not.
	 */
	public function get_user_accounts_enabled()
	{
		return $this->get_property(self::USER_ACCOUNTS_ENABLED_PROPERTY);
	}

	/**
	 * Enables the interaction between members.
	 */
	public function enable_user_accounts()
	{
		$this->set_property(self::USER_ACCOUNTS_ENABLED_PROPERTY, true);
	}

	/**
	 * Disables the interaction between members.
	 */
	public function disable_user_accounts()
	{
		$this->set_property(self::USER_ACCOUNTS_ENABLED_PROPERTY, false);
	}

	/**
	 * Returns the welcome message
	 * @return string the message
	 */
	public function get_welcome_message()
	{
		return $this->get_property(self::WELCOME_MESSAGE_PROPERTY);
	}

	/**
	 * Sets the welcome message displayed on the member profile main page.
	 * @param string $message The welcome message
	 */
	public function set_welcome_message($message)
	{
		$this->set_property(self::WELCOME_MESSAGE_PROPERTY, $message);
	}

	/**
	 * Tells whether the member registering is enabled
	 * @return bool true if it is, false otherwise.
	 */
	public function is_registration_enabled()
	{
		return $this->get_property(self::REGISTRATION_ENABLED_PROPERTY);
	}

	/**
	 * Enables the member registering
	 */
	public function enable_registration()
	{
		$this->set_property(self::REGISTRATION_ENABLED_PROPERTY, true);
	}

	/**
	 * Disables the member registering
	 */
	public function disable_registration()
	{
		$this->set_property(self::REGISTRATION_ENABLED_PROPERTY, false);
	}
	
	/**
	 * Gets the agreement that users must accept to register
	 * @return unknown_type
	 */
	public function get_registering_agreement()
	{
		return $this->get_property(self::REGISTERING_AGREEMENT_PROPERTY);
	}

	/**
	 * Sets the agreement that users mut accept to register
	 * @param $agreement The agreement
	 */
	public function set_registering_agreement($agreement)
	{
		$this->set_property(self::REGISTERING_AGREEMENT_PROPERTY, $agreement);
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
		$this->set_registering_agreement($LANG['register_agreement']);
		$this->enable_user_accounts();
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