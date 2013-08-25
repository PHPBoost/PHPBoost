<?php
/*##################################################
 *                      	 ThemesConfig.class.php
 *                            -------------------
 *   begin                : April 11, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class ThemesConfig extends AbstractConfigData
{
	private static $themes_property = 'themes';

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::$themes_property => array()
		);
	}

	public function get_themes()
	{
		return $this->get_property(self::$themes_property);
	}

	public function get_theme($theme_id)
	{
		$themes = $this->get_property(self::$themes_property);
		if (array_key_exists($theme_id, $themes))
		{
			return $themes[$theme_id];
		}
		return null;
	}

    public function set_themes(array $themes)
    {
        $this->set_property(self::$themes_property, $themes);
    }

    public function add_theme(Theme $theme)
    {
        $themes = $this->get_property(self::$themes_property);
        $themes[$theme->get_id()] = $theme;
        $this->set_property(self::$themes_property, $themes);
    }

    public function remove_theme(Theme $theme)
    {
        $themes = $this->get_property(self::$themes_property);
        unset($themes[$theme->get_id()]);
        $this->set_property(self::$themes_property, $themes);
    }

    public function remove_theme_by_id($theme_id)
    {
        $themes = $this->get_property(self::$themes_property);
        unset($themes[$theme_id]);
        $this->set_property(self::$themes_property, $themes);
    }
	
	public function update(Theme $theme)
	{
		$themes = $this->get_property(self::$themes_property);
        $themes[$theme->get_id()] = $theme;

        $this->set_property(self::$themes_property, $themes);
	}

	/**
	 * @desc Loads and returns the themes cached data.
	 * @return themesConfig The cached data
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
?>