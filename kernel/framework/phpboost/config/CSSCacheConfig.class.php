<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2012 05 16
*/

class CSSCacheConfig extends AbstractConfigData
{
	const ACTIVATED = 'activated';
	const OPTIMIZATION_LEVEL = 'optimization_level';

	public function enable()
	{
		$this->set_property(self::ACTIVATED, true);
	}

	public function disable()
	{
		$this->set_property(self::ACTIVATED, false);
	}

	public function is_enabled()
	{
		return $this->get_property(self::ACTIVATED);
	}

	public function set_optimization_level($level)
	{
		$this->set_property(self::OPTIMIZATION_LEVEL, $level);
	}

	public function get_optimization_level()
	{
		return $this->get_property(self::OPTIMIZATION_LEVEL);
	}

	public function get_default_values()
	{
		return array(
			self::ACTIVATED => true,
			self::OPTIMIZATION_LEVEL => CSSFileOptimizer::HIGH_OPTIMIZATION
		);
	}

	/**
	 * Returns the configuration.
	 * @return CSSCacheConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'css-cache-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'css-cache-config');
	}
}
?>
