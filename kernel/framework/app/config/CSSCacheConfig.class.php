<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2012 05 16
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CSSCacheConfig extends AbstractConfigData
{
	const ACTIVATED = 'activated';
	const OPTIMIZATION_LEVEL = 'optimization_level';

	public function enable(): void
	{
		$this->set_property(self::ACTIVATED, true);
	}

	public function disable(): void
	{
		$this->set_property(self::ACTIVATED, false);
	}

	public function is_enabled(): bool
	{
		return $this->get_property(self::ACTIVATED);
	}

	public function set_optimization_level(string $level): void
	{
		$this->set_property(self::OPTIMIZATION_LEVEL, $level);
	}

	public function get_optimization_level(): string
	{
		return $this->get_property(self::OPTIMIZATION_LEVEL);
	}

	public function get_default_values(): array
	{
		return [
			self::ACTIVATED => true,
			self::OPTIMIZATION_LEVEL => CSSFileOptimizer::HIGH_OPTIMIZATION
		];
	}

	/**
	 * Returns the configuration.
	 * @return CSSCacheConfig
	 */
	public static function load(): CSSCacheConfig
	{
		return ConfigManager::load(self::class, 'kernel', 'css-cache-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save(): void
	{
		ConfigManager::save('kernel', self::load(), 'css-cache-config');
	}
}
?>
