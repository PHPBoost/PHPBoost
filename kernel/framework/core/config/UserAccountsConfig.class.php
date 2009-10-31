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
     * Tells whether the member registering is enabled
     * @return bool true if it is, false otherwise.
     */
	public function is_registering_enabled()
	{
	    return $this->get_property('enable_registering');
	}
	
	/**
	 * Enables or disables the member registering
	 * @param bool $enabled true if you want to enable it, false otherwise. 
	 */
	public function set_registering_enabled($enabled)
	{
	    $this->set_property('enable_registering', $enabled);
	}
	
	/**
	 * Returns the welcome message
	 * @return string the message
	 */
	public function get_welcome_message()
	{
	    return $this->get_property('welcome_message');
	}
	
	/**
	 * Sets the welcome message displayed on the member profile main page.
	 * @param string $message The welcome message
	 */
	public function set_welcome_message($message)
	{
	    $this->set_property('welcome_message', $message);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/config/DefaultConfigData#set_default_values()
	 */
	public function set_default_values()
	{
	    global $LANG;
	    
		$this->set_registering_enabled(true);
		$this->set_welcome_message($LANG['site_config_msg_mbr']);
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