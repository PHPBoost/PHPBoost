<?php
/*##################################################
 *		                  PollConfig.class.php
 *                            -------------------
 *   begin                : September 25, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

/**
 * This class contains the configuration of the poll module.
 * @author Patrick Dubeau <daaxwizeman@gmail.com>
 *
 */
class PollConfig extends AbstractConfigData
{	
	public function get_mini_poll_selected()
	{
		return $this->get_property('poll_mini');
	}
	
	public function set_mini_poll_selected(Array $id_poll) 
	{
		$this->set_property('poll_mini', $id_poll);
	}
	
	public function get_min_rank_poll()
	{
		return $this->get_property('poll_auth');
	}
	
	public function set_min_rank_poll($rank) 
	{
		$this->set_property('poll_auth', $rank);
	}
	
	public function get_poll_cookie_name()
	{
		return $this->get_property('poll_cookie');
	}
	
	public function set_poll_cookie_name($name) 
	{
		$this->set_property('poll_cookie', $name);
	}
	
	public function get_poll_cookie_lenght()
	{
		return $this->get_property('poll_cookie_lenght');
	}
	
	public function set_poll_cookie_lenght($days) 
	{
		$this->set_property('poll_cookie_lenght', $days);
	}
	
	public function get_default_values()
	{
		return array(
			'poll_mini' => array('1'),
			'poll_auth' => -1,
			'poll_cookie' => 'poll',
			'poll_cookie_lenght' => 30
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return PollConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'poll', 'main');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('poll', self::load(), 'main');
	}
}
?>