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
	const AUTHENTICATIONS_ENABLED = 'authentications_enabled';
	const CLIENT_IDS              = 'client_ids';
	const CLIENT_SECRETS          = 'client_secrets';
	const CONTENT_SHARING_ENABLED = 'content_sharing_enabled';
	const SOCIAL_NETWORKS_ORDER   = 'social_networks_order';
	
	 /**
	 * @method Get enabled authentications
	 */
	public function get_enabled_authentications()
	{
		return $this->get_property(self::AUTHENTICATIONS_ENABLED);
	}
	
	 /**
	 * @method Set enabled authentications
	 * @params string[] $array Array of enabled authentications
	 */
	public function set_enabled_authentications(Array $array)
	{
		$this->set_property(self::AUTHENTICATIONS_ENABLED, $array);
	}
	
	 /**
	 * @method Get check if social network authentication is enabled
	 */
	public function is_authentication_enabled($social_network_id)
	{
		return in_array($social_network_id, $this->get_enabled_authentications());
	}
	
	 /**
	 * @method Get check if social network authentication is available
	 */
	public function is_authentication_available($social_network_id)
	{
		$server_configuration = new ServerConfiguration();
		return in_array($social_network_id, $this->get_enabled_authentications()) && $server_configuration->has_curl_library();
	}
	
	 /**
	 * @method Get client ids
	 */
	public function get_client_ids()
	{
		return $this->get_property(self::CLIENT_IDS);
	}
	
	 /**
	 * @method Set client ids
	 * @params string[] $array Array of client ids
	 */
	public function set_client_ids(Array $array)
	{
		$this->set_property(self::CLIENT_IDS, $array);
	}
	
	 /**
	 * @method Get the client id of a social network
	 */
	public function get_client_id($social_network_id)
	{
		$client_ids = $this->get_property(self::CLIENT_IDS);
		return isset($client_ids[$social_network_id]) ? $client_ids[$social_network_id] : '';
	}
	
	 /**
	 * @method Get client secrets
	 */
	public function get_client_secrets()
	{
		return $this->get_property(self::CLIENT_SECRETS);
	}
	
	 /**
	 * @method Set client secrets
	 * @params string[] $array Array of client secrets
	 */
	public function set_client_secrets(Array $array)
	{
		$this->set_property(self::CLIENT_SECRETS, $array);
	}
	
	 /**
	 * @method Get the client secret of a social network
	 */
	public function get_client_secret($social_network_id)
	{
		$client_secrets = $this->get_property(self::CLIENT_SECRETS);
		return isset($client_secrets[$social_network_id]) ? $client_secrets[$social_network_id] : '';
	}
	
	 /**
	 * @method Get enabled content sharing
	 */
	public function get_enabled_content_sharing()
	{
		return $this->get_property(self::CONTENT_SHARING_ENABLED);
	}
	
	 /**
	 * @method Set enabled content sharing
	 * @params string[] $array Array of enabled content sharing
	 */
	public function set_enabled_content_sharing(Array $array)
	{
		$this->set_property(self::CONTENT_SHARING_ENABLED, $array);
	}
	
	 /**
	 * @method Get check if social network content sharing is enabled
	 */
	public function is_content_sharing_enabled($social_network_id)
	{
		return in_array($social_network_id, $this->get_enabled_content_sharing());
	}
	
	 /**
	 * @method Get social networks order
	 */
	public function get_social_networks_order()
	{
		return $this->get_property(self::SOCIAL_NETWORKS_ORDER);
	}
	
	 /**
	 * @method Set social networks order
	 * @params string[] $array Array of social networks ids
	 */
	public function set_social_networks_order(Array $array)
	{
		$this->set_property(self::SOCIAL_NETWORKS_ORDER, $array);
	}
	
	public function get_default_values()
	{
		return array(
			self::AUTHENTICATIONS_ENABLED => array(),
			self::CLIENT_IDS              => array(),
			self::CLIENT_SECRETS          => array(),
			self::CONTENT_SHARING_ENABLED => array(FacebookSocialNetwork::SOCIAL_NETWORK_ID, GoogleSocialNetwork::SOCIAL_NETWORK_ID, TwitterSocialNetwork::SOCIAL_NETWORK_ID),
			self::SOCIAL_NETWORKS_ORDER   => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return SocialNetworksConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'social-networks', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('social-networks', self::load(), 'config');
	}
}
?>