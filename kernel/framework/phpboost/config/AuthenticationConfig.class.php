<?php
/*##################################################
 *		             AuthenticationConfig.class.php
 *                            -------------------
 *   begin                : August 27, 2014
 *   copyright            : (C) 2014 Kévin MASSY
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
 * @author Kévin MASSY <kevin.massy@phpboost.com>
 */
class AuthenticationConfig extends AbstractConfigData
{
	const FB_APP_ID = 'fb_app_id';
	const FB_APP_KEY = 'fb_app_key';
	
	public function get_fb_app_id()
	{
		return $this->get_property(self::FB_APP_ID);
	}
	
	public function set_fb_app_id($fb_app_id)
	{
		$this->set_property(self::FB_APP_ID, $fb_app_id);
	}
	
	public function get_fb_app_key()
	{
		return $this->get_property(self::FB_APP_KEY);
	}
	
	public function set_fb_app_key($fb_app_key)
	{
		$this->set_property(self::FB_APP_KEY, $fb_app_key);
	}
	
	public function get_default_values()
	{
		return array(
			self::FB_APP_ID => '',
			self::FB_APP_KEY => '',
		);
	}

	/**
	 * Returns the configuration.
	 * @return AuthenticationConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'authentification-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'authentification-config');
	}
}
?>