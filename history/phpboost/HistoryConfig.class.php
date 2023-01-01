<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 17
 * @since       PHPBoost 6.0 - 2021 10 22
*/

class HistoryConfig extends AbstractConfigData
{
	const HISTORY_TOPICS_DISABLED = 'history_topics_disabled';
	const DISABLED_MODULES        = 'disabled_modules';
	const LOG_RETENTION_PERIOD    = 'log_retention_period';

	public function get_history_topics_disabled()
	{
		return $this->get_property(self::HISTORY_TOPICS_DISABLED);
	}

	public function set_history_topics_disabled(array $topics)
	{
		$this->set_property(self::HISTORY_TOPICS_DISABLED, $topics);
	}

	public function get_disabled_modules()
	{
		return $this->get_property(self::DISABLED_MODULES);
	}

	public function set_disabled_modules(array $modules)
	{
		$this->set_property(self::DISABLED_MODULES, $modules);
	}

	public function get_log_retention_period()
	{
		return $this->get_property(self::LOG_RETENTION_PERIOD);
	}

	public function set_log_retention_period(int $value)
	{
		$this->set_property(self::LOG_RETENTION_PERIOD, $value);
	}

	public function get_default_values()
	{
		return array(
			self::HISTORY_TOPICS_DISABLED => array(),
			self::DISABLED_MODULES        => array(),
			self::LOG_RETENTION_PERIOD    => 31557600
		);
	}

	/**
	 * Returns the configuration.
	 * @return DatabaseConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'history', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('history', self::load(), 'config');
	}
}
?>
