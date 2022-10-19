<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 04 24
 * @since       PHPBoost 4.1 - 2014 08 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class SocialNetworksConfig extends AbstractConfigData
{
	const AUTHENTICATIONS_ENABLED      = 'authentications_enabled';
	const CLIENT_IDS                   = 'client_ids';
	const CLIENT_SECRETS               = 'client_secrets';
	const CONTENT_SHARING_ENABLED      = 'content_sharing_enabled';
	const SOCIAL_NETWORKS_ORDER        = 'social_networks_order';
	const ADDITIONAL_SOCIAL_NETWORKS   = 'additional_social_networks';

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

	/**
	 * @method Get additional social networks (from other modules)
	 */
	public function get_additional_social_networks()
	{
		return $this->get_property(self::ADDITIONAL_SOCIAL_NETWORKS);
	}

	/**
	 * @method Add additional social network
	 * @params string $value additional social network
	 */
	public function add_additional_social_networks($value)
	{
		$additional_social_networks = $this->get_property(self::ADDITIONAL_SOCIAL_NETWORKS);
		$additional_social_networks[] = $value;
		$this->set_property(self::ADDITIONAL_SOCIAL_NETWORKS, $additional_social_networks);
	}

	/**
	 * @method Remove additional social network
	 * @params string $value additional social network
	 */
	public function remove_additional_social_networks($value)
	{
		$key = array_search($value, $additional_social_networks);
		if ($key !== false)
		{
			unset($additional_social_networks[$key]);
			$this->set_property(self::ADDITIONAL_SOCIAL_NETWORKS, $additional_social_networks);
		}
	}

	public function get_default_values()
	{
		return array(
			self::AUTHENTICATIONS_ENABLED    => array(),
			self::CLIENT_IDS                 => array(),
			self::CLIENT_SECRETS             => array(),
			self::CONTENT_SHARING_ENABLED    => array(FacebookSocialNetwork::SOCIAL_NETWORK_ID, TwitterSocialNetwork::SOCIAL_NETWORK_ID),
			self::SOCIAL_NETWORKS_ORDER      => array(),
			self::ADDITIONAL_SOCIAL_NETWORKS => array()
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
