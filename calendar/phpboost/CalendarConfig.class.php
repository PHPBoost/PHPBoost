<?php
/*##################################################
 *		             CalendarConfig.class.php
 *                            -------------------
 *   begin                : August 10, 2010
 *   copyright            : (C) 2010 Kévin MASSY
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class CalendarConfig extends AbstractConfigData
{
	const AUTHORIZATION = 'authorization';
	
	public function get_authorization()
	{
		return $this->get_property(self::AUTHORIZATION);
	}
	
	public function set_authorization(Array $array)
	{
		$this->set_property(self::AUTHORIZATION, $array);
	}
	
	public function get_default_values()
	{
		return array(
			self::AUTHORIZATION => array('r-1' => 1, 'r0' => 1, 'r1' => 5)
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return CalendarConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'main', 'calendar');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('main', self::load(), 'calendar');
	}
}
?>