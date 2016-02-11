<?php
/*##################################################
 *		                  DatabaseConfig.class.php
 *                            -------------------
 *   begin                : September 30, 2015
 *   copyright            : (C) 2015 j1.seth
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

class DatabaseConfig extends AbstractConfigData
{
	const DATABASE_TABLES_OPTIMIZATION_ENABLED = 'database_tables_optimization_enabled';
	const DATABASE_TABLES_OPTIMIZATION_DAY = 'database_tables_optimization_day';

	public function is_database_tables_optimization_enabled()
	{
		return $this->get_property(self::DATABASE_TABLES_OPTIMIZATION_ENABLED);
	}

	public function set_database_tables_optimization_enabled($enabled)
	{
		$this->set_property(self::DATABASE_TABLES_OPTIMIZATION_ENABLED, $enabled);
	}

	public function get_database_tables_optimization_day()
	{
		return $this->get_property(self::DATABASE_TABLES_OPTIMIZATION_DAY);
	}

	public function set_database_tables_optimization_day($day)
	{
		$this->set_property(self::DATABASE_TABLES_OPTIMIZATION_DAY, $day);
	}
	
	public function get_default_values()
	{
		return array(
			self::DATABASE_TABLES_OPTIMIZATION_ENABLED => true,
			self::DATABASE_TABLES_OPTIMIZATION_DAY => 0
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return DatabaseConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'database', 'config');
	}
	
	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('database', self::load(), 'config');
	}
}
?>
