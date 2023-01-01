<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 07 08
*/

class GraphicalEnvironmentConfig extends AbstractConfigData
{
	const VISIT_COUNTER_ENABLED = 'visit_counter_enabled';
	const DISPLAY_THEME_AUTHOR = 'display_theme_author';
	const PAGE_BENCH_ENABLED = 'page_bench_enabled';

	public function is_visit_counter_enabled()
	{
		return $this->get_property(self::VISIT_COUNTER_ENABLED);
	}

	public function set_visit_counter_enabled($enabled)
	{
		$this->set_property(self::VISIT_COUNTER_ENABLED, $enabled);
	}

	public function get_display_theme_author()
	{
		return $this->get_property(self::DISPLAY_THEME_AUTHOR);
	}

	public function set_display_theme_author($display)
	{
		$this->set_property(self::DISPLAY_THEME_AUTHOR, $display);
	}

	public function is_page_bench_enabled()
	{
		return $this->get_property(self::PAGE_BENCH_ENABLED);
	}

	public function set_page_bench_enabled($enabled)
	{
		$this->set_property(self::PAGE_BENCH_ENABLED, $enabled);
	}

	public function get_default_values()
	{
		return array(
			self::VISIT_COUNTER_ENABLED => false,
			self::DISPLAY_THEME_AUTHOR => false,
			self::PAGE_BENCH_ENABLED => false,
		);
	}

	/**
	 * Returns the configuration.
	 * @return GraphicalEnvironmentConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'graphical-environment-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'graphical-environment-config');
	}
}
?>
