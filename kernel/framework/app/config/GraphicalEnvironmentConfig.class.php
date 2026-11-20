<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 07 08
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GraphicalEnvironmentConfig extends AbstractConfigData
{
	const VISIT_COUNTER_ENABLED = 'visit_counter_enabled';
	const DISPLAY_COPYRIGHT     = 'display_copyright';
	const COPYRIGHT_NAME        = 'copyright_name';
	const COPYRIGHT_URL         = 'copyright_url';
	const DISPLAY_THEME_AUTHOR  = 'display_theme_author';
	const PAGE_BENCH_ENABLED    = 'page_bench_enabled';

	public function is_visit_counter_enabled(): bool
	{
		return $this->get_property(self::VISIT_COUNTER_ENABLED);
	}

	public function set_visit_counter_enabled(bool $enabled): void
	{
		$this->set_property(self::VISIT_COUNTER_ENABLED, $enabled);
	}

	public function get_display_copyright(): bool
	{
		return $this->get_property(self::DISPLAY_COPYRIGHT);
	}

	public function set_display_copyright(bool $display): void
	{
		$this->set_property(self::DISPLAY_COPYRIGHT, $display);
	}

	public function get_copyright_name(): string
	{
		return $this->get_property(self::COPYRIGHT_NAME);
	}

	public function set_copyright_name(string $name): void
	{
		$this->set_property(self::COPYRIGHT_NAME, $name);
	}

	public function get_copyright_url(): string
	{
		return $this->get_property(self::COPYRIGHT_URL);
	}

	public function set_copyright_url(string $url): void
	{
		$this->set_property(self::COPYRIGHT_URL, $url);
	}

	public function get_display_theme_author(): bool
	{
		return $this->get_property(self::DISPLAY_THEME_AUTHOR);
	}

	public function set_display_theme_author(bool $display): void
	{
		$this->set_property(self::DISPLAY_THEME_AUTHOR, $display);
	}

	public function is_page_bench_enabled(): bool
	{
		return $this->get_property(self::PAGE_BENCH_ENABLED);
	}

	public function set_page_bench_enabled(bool $enabled): void
	{
		$this->set_property(self::PAGE_BENCH_ENABLED, $enabled);
	}

	public function get_default_values(): array
	{
		return [
			self::VISIT_COUNTER_ENABLED => false,
			self::DISPLAY_COPYRIGHT     => false,
			self::COPYRIGHT_NAME        => '',
			self::COPYRIGHT_URL         => '',
			self::DISPLAY_THEME_AUTHOR  => false,
			self::PAGE_BENCH_ENABLED    => false,
		];
	}

	/**
	 * Returns the configuration.
	 * @return GraphicalEnvironmentConfig
	 */
	public static function load(): GraphicalEnvironmentConfig
	{
		return ConfigManager::load(self::class, 'kernel', 'graphical-environment-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save(): void
	{
		ConfigManager::save('kernel', self::load(), 'graphical-environment-config');
	}
}
?>
