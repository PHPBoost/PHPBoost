<?php
/*##################################################
 *		             SocialNetworksConfig.class.php
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
class SocialNetworksConfig extends AbstractConfigData
{
	const FACEBOOK_AUTH_ENABLED = 'facebook_auth_enabled';
	const FACEBOOK_APP_ID       = 'facebook_app_id';
	const FACEBOOK_APP_KEY      = 'facebook_app_key';

	const GOOGLE_AUTH_ENABLED  = 'google_auth_enabled';
	const GOOGLE_CLIENT_ID     = 'google_client_id';
	const GOOGLE_CLIENT_SECRET = 'google_client_secret';

	const LINKEDIN_AUTH_ENABLED  = 'linkedin_auth_enabled';
	const LINKEDIN_CLIENT_ID     = 'linkedin_client_id';
	const LINKEDIN_CLIENT_SECRET = 'linkedin_client_secret';

	const TWITTER_AUTH_ENABLED    = 'twitter_auth_enabled';
	const TWITTER_CONSUMER_KEY    = 'twitter_consumer_key';
	const TWITTER_CONSUMER_SECRET = 'twitter_consumer_secret';
	
	public function enable_facebook_auth()
	{
		$this->set_property(self::FACEBOOK_AUTH_ENABLED, true);
	}
	
	public function disable_facebook_auth()
	{
		$this->set_property(self::FACEBOOK_AUTH_ENABLED, false);
	}
	
	public function is_facebook_auth_enabled()
	{
		return $this->get_property(self::FACEBOOK_AUTH_ENABLED);
	}
	
	public function is_facebook_auth_available()
	{
		$server_configuration = new ServerConfiguration();
		return $this->get_property(self::FACEBOOK_AUTH_ENABLED) && $server_configuration->has_curl_library();
	}
	
	public function get_facebook_app_id()
	{
		return $this->get_property(self::FACEBOOK_APP_ID);
	}
	
	public function set_facebook_app_id($facebook_app_id)
	{
		$this->set_property(self::FACEBOOK_APP_ID, $facebook_app_id);
	}
	
	public function get_facebook_app_key()
	{
		return $this->get_property(self::FACEBOOK_APP_KEY);
	}
	
	public function set_facebook_app_key($facebook_app_key)
	{
		$this->set_property(self::FACEBOOK_APP_KEY, $facebook_app_key);
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
	
	public function enable_linkedin_auth()
	{
		$this->set_property(self::LINKEDIN_AUTH_ENABLED, true);
	}
	
	public function disable_linkedin_auth()
	{
		$this->set_property(self::LINKEDIN_AUTH_ENABLED, false);
	}
	
	public function is_linkedin_auth_enabled()
	{
		return $this->get_property(self::LINKEDIN_AUTH_ENABLED);
	}
	
	public function is_linkedin_auth_available()
	{
		$server_configuration = new ServerConfiguration();
		return $this->get_property(self::LINKEDIN_AUTH_ENABLED) && $server_configuration->has_curl_library();
	}

	public function get_linkedin_client_id()
	{
		return $this->get_property(self::LINKEDIN_CLIENT_ID);
	}

	public function set_linkedin_client_id($linkedin_client_id)
	{
		$this->set_property(self::LINKEDIN_CLIENT_ID, $linkedin_client_id);
	}

	public function get_linkedin_client_secret()
	{
		return $this->get_property(self::LINKEDIN_CLIENT_SECRET);
	}

	public function set_linkedin_client_secret($linkedin_client_secret)
	{
		$this->set_property(self::LINKEDIN_CLIENT_SECRET, $linkedin_client_secret);
	}
	
	public function enable_twitter_auth()
	{
		$this->set_property(self::TWITTER_AUTH_ENABLED, true);
	}
	
	public function disable_twitter_auth()
	{
		$this->set_property(self::TWITTER_AUTH_ENABLED, false);
	}
	
	public function is_twitter_auth_enabled()
	{
		return $this->get_property(self::TWITTER_AUTH_ENABLED);
	}
	
	public function is_twitter_auth_available()
	{
		$server_configuration = new ServerConfiguration();
		return $this->get_property(self::TWITTER_AUTH_ENABLED) && $server_configuration->has_curl_library();
	}

	public function get_twitter_consumer_key()
	{
		return $this->get_property(self::TWITTER_CONSUMER_KEY);
	}

	public function set_twitter_consumer_key($twitter_consumer_key)
	{
		$this->set_property(self::TWITTER_CONSUMER_KEY, $twitter_consumer_key);
	}

	public function get_twitter_consumer_secret()
	{
		return $this->get_property(self::TWITTER_CONSUMER_SECRET);
	}

	public function set_twitter_consumer_secret($twitter_consumer_secret)
	{
		$this->set_property(self::TWITTER_CONSUMER_SECRET, $twitter_consumer_secret);
	}
	
	public function get_default_values()
	{
		return array(
			self::FACEBOOK_AUTH_ENABLED => false,
			self::FACEBOOK_APP_ID       => '',
			self::FACEBOOK_APP_KEY      => '',

			self::GOOGLE_AUTH_ENABLED  => false,
			self::GOOGLE_CLIENT_ID     => '',
			self::GOOGLE_CLIENT_SECRET => '',

			self::LINKEDIN_AUTH_ENABLED  => false,
			self::LINKEDIN_CLIENT_ID     => '',
			self::LINKEDIN_CLIENT_SECRET => '',

			self::TWITTER_AUTH_ENABLED    => false,
			self::TWITTER_CONSUMER_KEY    => '',
			self::TWITTER_CONSUMER_SECRET => '',
		);
	}

	/**
	 * Returns the configuration.
	 * @return SocialNetworksConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'social_networks', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('social_networks', self::load(), 'config');
	}
}
?>