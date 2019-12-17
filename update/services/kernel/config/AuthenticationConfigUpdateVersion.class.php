<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 30
 * @since       PHPBoost 5.1 - 2018 09 26
*/

class AuthenticationConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('kernel-authentication-config', false);
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();

		if (class_exists('SocialNetworksConfig') && !empty($old_config))
		{
			$authentications_enabled = array();
			$client_ids = array();
			$client_secrets = array();

			$socialnetworks_config = SocialNetworksConfig::load();

			if ($old_config->is_fb_auth_enabled())
			{
				$authentications_enabled[] = FacebookSocialNetwork::SOCIAL_NETWORK_ID;
				$client_ids[FacebookSocialNetwork::SOCIAL_NETWORK_ID] = $old_config->get_fb_app_id();
				$client_secrets[FacebookSocialNetwork::SOCIAL_NETWORK_ID] = $old_config->get_fb_app_key();
			}

			if ($old_config->is_google_auth_enabled())
			{
				$authentications_enabled[] = GoogleSocialNetwork::SOCIAL_NETWORK_ID;
				$client_ids[GoogleSocialNetwork::SOCIAL_NETWORK_ID] = $old_config->get_google_client_id();
				$client_secrets[GoogleSocialNetwork::SOCIAL_NETWORK_ID] = $old_config->get_google_client_secret();
			}

			$socialnetworks_config->set_enabled_authentications($authentications_enabled);
			$socialnetworks_config->set_client_ids($client_ids);
			$socialnetworks_config->set_client_secrets($client_secrets);

			$this->save_new_config('social-networks-config', $socialnetworks_config);

			return true;
		}
		return false;
	}
}
?>
