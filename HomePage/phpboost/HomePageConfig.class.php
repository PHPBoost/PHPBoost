<?php
/*##################################################
 *                           HomePageConfig.class.php
 *                            -------------------
 *   begin                : February 21, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class HomePageConfig extends AbstractConfigData
{
	const NUMBER_COLUMNS = 'number_columns';
	
	public function get_number_columns()
	{
		return $this->get_property(self::NUMBER_COLUMNS);
	}

	public function set_number_columns($number_columns)
	{
		$this->set_property(self::NUMBER_COLUMNS, $number_columns);
	}
	
	public function get_default_values()
	{
		return array(
			self::NUMBER_COLUMNS => 2
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return HomePageConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'modules', 'homepage-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('modules', self::load(), 'homepage-config');
	}
}
?>