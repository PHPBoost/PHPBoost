<?php
/*##################################################
 *		             OnlineConfig.class.php
 *                            -------------------
 *   begin                : September 19, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class OnlineConfig extends AbstractConfigData
{
	const DISPLAY_ORDER = 'display_order';
	const NUMBER_MEMBER_DISPLAYED = 'number_member_displayed';
	const NUMBER_MEMBERS_PER_PAGE = 'number_members_per_page';
	
	const LEVEL_DISPLAY_ORDER = 'level_display_order';
	const SESSION_TIME_DISPLAY_ORDER = 'session_time_display_order';
	const LEVEL_AND_SESSION_TIME_DISPLAY_ORDER = 'level_and_session_time_display_order';
	
	const AUTHORIZATIONS = 'authorizations';
	
	public function get_display_order()
	{
		return $this->get_property(self::DISPLAY_ORDER);
	}
	
	public function set_display_order($value)
	{
		$this->set_property(self::DISPLAY_ORDER, $value);
	}
	
	public function get_display_order_request() 
	{
		switch (self::DISPLAY_ORDER)
		{
			case self::LEVEL_DISPLAY_ORDER:
				return 'm.level DESC';
			break;
			case self::SESSION_TIME_DISPLAY_ORDER:
				return 's.timestamp DESC';
			break;
			case self::LEVEL_AND_SESSION_TIME_DISPLAY_ORDER:
			default:
				return 'm.level DESC, s.timestamp DESC';
		}
	}
	
	public function get_number_member_displayed()
	{
		return $this->get_property(self::NUMBER_MEMBER_DISPLAYED);
	}
	
	public function set_number_member_displayed($number)
	{
		$this->set_property(self::NUMBER_MEMBER_DISPLAYED, $number);
	}
	
	public function get_number_members_per_page()
	{
		return $this->get_property(self::NUMBER_MEMBERS_PER_PAGE);
	}
	
	public function set_number_members_per_page($number)
	{
		$this->set_property(self::NUMBER_MEMBERS_PER_PAGE, $number);
	}
	
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}
	
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}
	
	public function get_default_values()
	{
		return array(
			self::DISPLAY_ORDER => self::LEVEL_AND_SESSION_TIME_DISPLAY_ORDER,
			self::NUMBER_MEMBER_DISPLAYED => 4,
			self::NUMBER_MEMBERS_PER_PAGE => 20,
			self::AUTHORIZATIONS => array('r0' => 1, 'r1' => 1)
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return OnlineConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'online', 'config');
	}
	
	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('online', self::load(), 'config');
	}
}
?>
