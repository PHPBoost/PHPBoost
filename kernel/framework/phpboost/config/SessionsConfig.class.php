<?php
/*##################################################
 *		             SessionsConfig.class.php
 *                            -------------------
 *   begin                : July 14, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Sessions Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Sessions Public License for more details.
 *
 * You should have received a copy of the GNU Sessions Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class SessionsConfig extends AbstractConfigData
{
	const COOKIE_NAME = 'cookie_name';
	/**
	 * Duration of a session (in seconds).
	 */
	const SESSION_DURATION = 'session_duration';
	/**
	 * Time during which the sessions is considered as active (the user is online)
	 */
	const ACTIVE_SESSION_DURATION = 'active_session_duration';
	/**
	 * @desc Duration of autoconnect cookie (in seconds).
	 */
	const AUTOCONNECT_DURATION = 'autoconnect_duration';
	
	public function get_cookie_name()
	{
		return $this->get_property(self::COOKIE_NAME);
	}
	
	public function set_cookie_name($cookie_name)
	{
		$this->set_property(self::COOKIE_NAME, $cookie_name);
	}
	
	public function get_session_duration()
	{
		return $this->get_property(self::SESSION_DURATION);
	}
	
	public function set_session_duration($duration)
	{
		$this->set_property(self::SESSION_DURATION, $duration);
	}
	
	public function get_active_session_duration()
	{
		return $this->get_property(self::ACTIVE_SESSION_DURATION);
	}
	
	public function set_active_session_duration($duration)
	{
		$this->set_property(self::ACTIVE_SESSION_DURATION, $duration);
	}
	
	public function get_autoconnect_duration()
	{
		return $this->get_property(self::AUTOCONNECT_DURATION);
	}

	public function set_autoconnect_duration($duration)
	{
		$this->set_property(self::AUTOCONNECT_DURATION, $duration);
	}
	
	public function get_default_values()
	{
		return array(
			self::COOKIE_NAME => 'session',
			self::SESSION_DURATION => 3600,
			self::ACTIVE_SESSION_DURATION => 300,
			self::AUTOCONNECT_DURATION => 3600 * 24 * 30
		);
	}

	/**
	 * Returns the configuration.
	 * @return SessionsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'sessions-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'sessions-config');
	}
}
?>