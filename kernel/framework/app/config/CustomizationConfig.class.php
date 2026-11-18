<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2011 08 30
* @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CustomizationConfig extends AbstractConfigData
{
	const FAVICON_PATH = 'favicon_path';
	const HEADER_LOGO_PATH_ALL_THEMES = 'header_logo_path_all_themes';

	public function get_favicon_path(): string
	{
		return $this->get_property(self::FAVICON_PATH);
	}

	public function set_favicon_path(string $path): void
	{
		$this->set_property(self::FAVICON_PATH, $path);
	}

	public function favicon_exists(): bool
	{
		$favicon_file = new File(PATH_TO_ROOT . $this->get_favicon_path());
		return $favicon_file->exists();
	}

	public function favicon_type(): mixed
	{
		if ($this->favicon_exists())
		{
			$favicon = new Image(PATH_TO_ROOT . $this->get_favicon_path());
			return $favicon->get_mime_type();
		}
		return null;
	}

	public function set_header_logo_path_all_themes(string $path): void
	{
		$this->set_property(self::HEADER_LOGO_PATH_ALL_THEMES, $path);
	}

	public function remove_header_logo_path_all_themes(): void
	{
		$this->set_property(self::HEADER_LOGO_PATH_ALL_THEMES, null);
	}

	public function get_header_logo_path_all_themes(): string
	{
		return $this->get_property(self::HEADER_LOGO_PATH_ALL_THEMES);
	}

	public function get_default_values(): array
	{
		return [
			self::FAVICON_PATH => '/favicon.ico',
			self::HEADER_LOGO_PATH_ALL_THEMES => null
		];
	}

	/**
	 * Returns the configuration.
	 * @return CustomizationConfig
	 */
	public static function load(): CustomizationConfig
	{
		return ConfigManager::load(self::class, 'kernel', 'customization-config');
	}

	/**
	 * Saves the configuration in the database.
	 */
	public static function save(): void
	{
		ConfigManager::save('kernel', self::load(), 'customization-config');
	}
}
?>
