<?php
/*##################################################
 *		             CalendarConfig.class.php
 *                            -------------------
 *   begin                : August 10, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kevin MASSY <soldier.weasel@gmail.com>
 */
class CalendarConfig extends AbstractConfigData
{
	const ITEMS_NUMBER_PER_PAGE = 'items_number_per_page';
	const COMMENTS_ENABLED = 'comments_enabled';
	const MEMBERS_BIRTHDAY_ENABLED = 'members_birthday_enabled';
	const EVENT_COLOR = 'event_color';
	const BIRTHDAY_COLOR = 'birthday_color';
	
	const AUTHORIZATIONS = 'authorizations';
	
	public function get_items_number_per_page()
	{
		return $this->get_property(self::ITEMS_NUMBER_PER_PAGE);
	}
	
	public function set_items_number_per_page($value)
	{
		$this->set_property(self::ITEMS_NUMBER_PER_PAGE, $value);
	}
	
	public function enable_comments()
	{
		$this->set_property(self::COMMENTS_ENABLED, true);
	}
	
	public function disable_comments()
	{
		$this->set_property(self::COMMENTS_ENABLED, false);
	}
	
	public function are_comments_enabled()
	{
		return $this->get_property(self::COMMENTS_ENABLED);
	}
	
	public function enable_members_birthday()
	{
		$this->set_property(self::MEMBERS_BIRTHDAY_ENABLED, true);
	}
	
	public function disable_members_birthday()
	{
		$this->set_property(self::MEMBERS_BIRTHDAY_ENABLED, false);
	}
	
	public function is_members_birthday_enabled()
	{
		return $this->get_property(self::MEMBERS_BIRTHDAY_ENABLED);
	}
	
	public function get_event_color()
	{
		return $this->get_property(self::EVENT_COLOR);
	}
	
	public function set_event_color($value)
	{
		$this->set_property(self::EVENT_COLOR, $value);
	}
	
	public function get_birthday_color()
	{
		return $this->get_property(self::BIRTHDAY_COLOR);
	}
	
	public function set_birthday_color($value)
	{
		$this->set_property(self::BIRTHDAY_COLOR, $value);
	}
	
	 /**
	 * @method Get authorizations
	 */
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}
	
	 /**
	 * @method Set authorizations
	 * @params string[] $array Array of authorizations
	 */
	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}
	
	/**
	 * @method Get default values.
	 */
	public function get_default_values()
	{
		return array(
			self::ITEMS_NUMBER_PER_PAGE => 15,
			self::COMMENTS_ENABLED => true,
			self::MEMBERS_BIRTHDAY_ENABLED => false,
			self::EVENT_COLOR => '#dbe5ee',
			self::BIRTHDAY_COLOR => '#f77c91',
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 15)
		);
	}
	
	/**
	 * @method Load the calendar configuration.
	 * @return CalendarConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'calendar', 'config');
	}
	
	/**
	 * @method Saves the calendar configuration in the database. It becomes persistent.
	 */
	public static function save()
	{
		ConfigManager::save('calendar', self::load(), 'config');
	}
}
?>
