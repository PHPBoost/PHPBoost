<?php
/*##################################################
 *		             CustomizationConfig.class.php
 *                            -------------------
 *   begin                : August 30, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class CustomizationConfig extends AbstractConfigData
{
	const FAVICON_PATH = 'favicon_path';
	
	public function get_favicon_path()
	{
		return $this->get_property(self::FAVICON_PATH);
	}
	
	public function set_favicon_path($path)
	{
		$this->set_property(self::FAVICON_PATH, $path);
	}
	
	public function get_default_values()
	{
		return array(
			self::FAVICON_PATH => null
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