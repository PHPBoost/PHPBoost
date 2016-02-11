<?php
/*##################################################
 *		                  PollConfig.class.php
 *                            -------------------
 *   begin                : March 2, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
	const COOKIE_NAME = 'cookie_name';
	const COOKIE_LENGHT = 'cookie_lenght';
	const DISPLAY_RESULTS_BEFORE_POLLS_END = 'display_results_before_polls_end';
	const DISPLAYED_IN_MINI_MODULE_LIST = 'displayed_in_mini_module_list';
	const AUTHORIZATIONS = 'authorizations';
	
	public function get_cookie_name()
	{
		return $this->get_property(self::COOKIE_NAME);
	}
	
	public function set_cookie_name($value) 
	{
		$this->set_property(self::COOKIE_NAME, $value);
	}
	
	public function get_cookie_lenght()
	{
		return $this->get_property(self::COOKIE_LENGHT);
	}
	
	public function set_cookie_lenght($value) 
	{
		$this->set_property(self::COOKIE_LENGHT, $value);
	}
	
	public function get_cookie_lenght_in_seconds()
	{
		return $this->get_property(self::COOKIE_LENGHT) * (3600 * 24);
	}
	
	public function display_results_before_polls_end()
	{
		$this->set_property(self::DISPLAY_RESULTS_BEFORE_POLLS_END, true);
	}
	
	public function hide_results_before_polls_end()
	{
		$this->set_property(self::DISPLAY_RESULTS_BEFORE_POLLS_END, false);
	}
	
	public function are_results_displayed_before_polls_end()
	{
		return $this->get_property(self::DISPLAY_RESULTS_BEFORE_POLLS_END);
	}
	
	public function get_displayed_in_mini_module_list()
	{
		return $this->get_property(self::DISPLAYED_IN_MINI_MODULE_LIST);
	}
	
	public function set_displayed_in_mini_module_list(Array $array) 
	{
		$this->set_property(self::DISPLAYED_IN_MINI_MODULE_LIST, $array);
	}
	
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}
	
	public function get_default_values()
	{
		return array(
			self::COOKIE_NAME => 'poll',
			self::COOKIE_LENGHT => 30, //La durée du cookie est de 30 jours par défaut
			self::DISPLAY_RESULTS_BEFORE_POLLS_END => true,
			self::DISPLAYED_IN_MINI_MODULE_LIST => array('1'),
			self::AUTHORIZATIONS => array('r-1' => 3, 'r0' => 3, 'r1' => 3)
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return PollConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'poll', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('poll', self::load(), 'config');
	}
}
?>