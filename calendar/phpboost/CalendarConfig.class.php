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
	const MEMBERS_BIRTHDAY_ACTIVATED = 'members_birthday_activated';
	
	const AUTHORIZATIONS = 'authorizations';
	
	 /**
	 * @method Check if the members birthday are activated
	 */
	public function get_members_birthday_activated()
	{
		return $this->get_members_birthday_activated(self::MEMBERS_BIRTHDAY_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the members birthday
	 * @params bool $value true/false
	 */
	public function set_members_birthday_activated($value)
	{
		$this->set_property(self::members_birthday_activated, $value);
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
			self::MEMBERS_BIRTHDAY_ACTIVATED => false,
			self::AUTHORIZATIONS => array('r1' => 15, 'r0' => 5, 'r-1' => 1)
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