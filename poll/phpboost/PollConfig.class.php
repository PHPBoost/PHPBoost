<?php
/*##################################################
 *		                  PollConfig.class.php
 *                            -------------------
 *   begin                : March 2, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class PollConfig extends AbstractConfigData
{
	const POLL_AUTH = 'poll_auth';
	const POLL_MINI = 'poll_mini';
	const POLL_COOKIE = 'poll_cookie';
	const POLL_COOKIE_LENGHT = 'poll_cookie_lenght';
	
	public function get_poll_auth()
	{
		return $this->get_property(self::POLL_AUTH);
	}
	
	public function set_poll_auth($auth)
	{
		$this->set_property(self::POLL_AUTH, $auth);
	}
	
	public function get_poll_mini()
	{
		return $this->get_property(self::POLL_MINI);
	}
	
	public function set_poll_mini($value) 
	{
		$this->set_property(self::POLL_MINI, $value);
	}
	
	public function get_poll_cookie()
	{
		return $this->get_property(self::POLL_COOKIE);
	}
	
	public function set_poll_cookie($cookie) 
	{
		$this->set_property(self::POLL_COOKIE, $cookie);
	}
	
	public function get_poll_cookie_lenght()
	{
		return $this->get_property(self::POLL_COOKIE_LENGHT);
	}
	
	public function set_poll_cookie_lenght($cookie_lenght) 
	{
		$this->set_property(self::POLL_COOKIE_LENGHT, $cookie_lenght);
	}
	
	public function get_default_values()
	{
		return array(
			self::POLL_AUTH => -1,
			self::POLL_MINI => array(0 => 1),
			self::POLL_COOKIE => 'poll',
			self::POLL_COOKIE_LENGHT => 2592000
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return PollConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'module-poll', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('module-poll', self::load(), 'config');
	}
}
?>