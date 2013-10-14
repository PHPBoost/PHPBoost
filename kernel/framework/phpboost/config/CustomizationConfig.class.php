<?php
/*##################################################
 *		             CustomizationConfig.class.php
 *                            -------------------
 *   begin                : August 30, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class CustomizationConfig extends AbstractConfigData
{
	const FAVICON_PATH = 'favicon_path';
	const HEADER_LOGO_PATH_ALL_THEMES = 'header_logo_path_all_themes';
	
	public function get_favicon_path()
	{
		return $this->get_property(self::FAVICON_PATH);
	}
	
	public function set_favicon_path($path)
	{
		$this->set_property(self::FAVICON_PATH, $path);
	}
	
	public function favicon_exists()
	{
		$favicon_file = new File(PATH_TO_ROOT . $this->get_favicon_path());
		return $favicon_file->exists();
	}
	
	public function favicon_type()
	{
		if ($this->favicon_exists())
		{
			$favicon = new Image(PATH_TO_ROOT . $this->get_favicon_path());
			return $favicon->get_mime_type();
		}
		return null;
	}
	
	public function set_header_logo_path_all_themes($path)
	{
		$this->set_property(self::HEADER_LOGO_PATH_ALL_THEMES, $path);
	}
	
	public function remove_header_logo_path_all_themes()
	{
		$this->set_property(self::HEADER_LOGO_PATH_ALL_THEMES, null);
	}
	
	public function get_header_logo_path_all_themes()
	{
		return $this->get_property(self::HEADER_LOGO_PATH_ALL_THEMES);
	}
	
	public function get_default_values()
	{
		return array(
			self::FAVICON_PATH => '/favicon.ico',
			self::HEADER_LOGO_PATH_ALL_THEMES => null
		);
	}

	/**
	 * Returns the configuration.
	 * @return CustomizationConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'customization-config');
	}

	/**
	 * Saves the configuration in the database.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'customization-config');
	}
}
?>