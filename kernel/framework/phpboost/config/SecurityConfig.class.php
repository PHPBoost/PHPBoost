<?php
/*##################################################
 *		                   SecurityConfig.class.php
 *                            -------------------
 *   begin                : July 17, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Maintenance Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Maintenance Public License for more details.
 *
 * You should have received a copy of the GNU Maintenance Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class SecurityConfig extends AbstractConfigData
{
	const INTERNAL_PASSWORD_MIN_LENGTH = 'internal_password_min_length';
	
	public function get_internal_password_min_length()
	{
		return $this->get_property(self::INTERNAL_PASSWORD_MIN_LENGTH);
	}

	public function set_internal_password_min_length($value)
	{
		$this->set_property(self::INTERNAL_PASSWORD_MIN_LENGTH, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::INTERNAL_PASSWORD_MIN_LENGTH => 6
		);
	}

	/**
	 * Returns the configuration.
	 * @return SecurityConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'security');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'security');
	}
}
?>