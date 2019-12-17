<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2015 09 30
*/

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
