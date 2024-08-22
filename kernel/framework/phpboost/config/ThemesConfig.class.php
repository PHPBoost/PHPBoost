<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 08 22
 * @since       PHPBoost 3.0 - 2011 04 11
*/

class ThemesConfig extends AbstractConfigData
{
	private static $themes_property = 'themes';

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return [
			self::$themes_property => []
		];
	}

	/**
	 * Return all Themes objects from config as array
	 * @return Theme[]
	 */
	public function get_themes():array
	{
		return $this->get_property(self::$themes_property);
	}

	/**
	 * Return the theme with the given id
	 * @param string $theme_id
	 * @return Theme|null
	 */
	public function get_theme($theme_id): ?Theme
	{
		$themes = $this->get_property(self::$themes_property);
		if (array_key_exists($theme_id, $themes))
		{
			return $themes[$theme_id];
		}
		return null;
	}

    /**
     * Sets the themes configuration.
     *
     * @param array $themes An array of themes to set as the new configuration.
     * @return void
     */
    public function set_themes(array $themes)
    {
        $this->set_property(self::$themes_property, $themes);
    }

    /**
     * Adds a theme to the themes configuration.
     *
     * @param Theme $theme The theme to add.
     * @return void
     */
    public function add_theme(Theme $theme)
    {
        $themes = $this->get_property(self::$themes_property);
        $themes[$theme->get_id()] = $theme;
        $this->set_property(self::$themes_property, $themes);
    }

    /**
     * Removes a theme from the themes configuration.
     *
     * @param Theme $theme The theme to remove.
     * @return void
     */
    public function remove_theme(Theme $theme)
    {
        $themes = $this->get_property(self::$themes_property);
        unset($themes[$theme->get_id()]);
        $this->set_property(self::$themes_property, $themes);
    }

    /**
     * Removes a theme from the themes configuration.
     *
     * @param string $theme_id The ID (name) of the theme to remove.
     * @return void
     */
    public function remove_theme_by_id($theme_id)
    {
        $themes = $this->get_property(self::$themes_property);
        unset($themes[$theme_id]);
        $this->set_property(self::$themes_property, $themes);
    }

	/**
	 * Updates the theme with the given theme ID (name).
	 *
	 * @param Theme $theme The theme to update.
	 * @return void
	 */
	public function update(Theme $theme)
	{
		$themes = $this->get_property(self::$themes_property);
        $themes[$theme->get_id()] = $theme;

        $this->set_property(self::$themes_property, $themes);
	}

	/**
	 * Loads and returns the themes cached data.
	 * @return ThemesConfig The cached data
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'themes');
	}

	/**
	 * Invalidates the current themes cached data.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'themes');
	}
}