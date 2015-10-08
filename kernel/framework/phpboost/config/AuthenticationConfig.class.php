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
	const FB_AUTH_ENABLED = 'fb_auth_enabled';
	const FB_APP_ID = 'fb_app_id';
	const FB_APP_KEY = 'fb_app_key';

	const GOOGLE_AUTH_ENABLED = 'google_auth_enabled';
	const GOOGLE_CLIENT_ID = 'google_client_id';
	const GOOGLE_CLIENT_SECRET = 'google_client_secret';
	
	public function enable_fb_auth()
	{
		$this->set_property(self::FB_AUTH_ENABLED, true);
	}
	
	public function disable_fb_auth()
	{
		$this->set_property(self::FB_AUTH_ENABLED, false);
	}
	
	public function is_fb_auth_enabled()
	{
		return $this->get_property(self::FB_AUTH_ENABLED);
	}
	
	public function is_fb_auth_available()
	{
		$server_configuration = new ServerConfiguration();
		return $this->get_property(self::FB_AUTH_ENABLED) && $server_configuration->has_curl_library();
	}
	
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
	
	public function enable_google_auth()
	{
		$this->set_property(self::GOOGLE_AUTH_ENABLED, true);
	}
	
	public function disable_google_auth()
	{
		$this->set_property(self::GOOGLE_AUTH_ENABLED, false);
	}
	
	public function is_google_auth_enabled()
	{
		return $this->get_property(self::GOOGLE_AUTH_ENABLED);
	}
	
	public function is_google_auth_available()
	{
		$server_configuration = new ServerConfiguration();
		return $this->get_property(self::GOOGLE_AUTH_ENABLED) && $server_configuration->has_curl_library();
	}

	public function get_google_client_id()
	{
		return $this->get_property(self::GOOGLE_CLIENT_ID);
	}

	public function set_google_client_id($google_client_id)
	{
		$this->set_property(self::GOOGLE_CLIENT_ID, $google_client_id);
	}

	public function get_google_client_secret()
	{
		return $this->get_property(self::GOOGLE_CLIENT_SECRET);
	}

	public function set_google_client_secret($google_client_secret)
	{
		$this->set_property(self::GOOGLE_CLIENT_SECRET, $google_client_secret);
	}
	
	public function get_default_values()
	{
		return array(
			self::FB_AUTH_ENABLED => false,
			self::FB_APP_ID => '',
			self::FB_APP_KEY => '',

			self::GOOGLE_AUTH_ENABLED => false,
			self::GOOGLE_CLIENT_ID => '',
			self::GOOGLE_CLIENT_SECRET => '',
		);
	}

	/**
	 * Returns the configuration.
	 * @return AuthenticationConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'authentication-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'authentication-config');
	}
}
?>