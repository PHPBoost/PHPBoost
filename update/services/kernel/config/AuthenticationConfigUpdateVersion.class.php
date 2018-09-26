<?php
/*##################################################
 *                           AuthenticationConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : September 26, 2018
 *   copyright            : (C) 2018 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AuthenticationConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('kernel-authentication-config', false);
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();
		$authentications_enabled = array();
		$client_ids = array();
		$client_secrets = array();
		
		$socialetworks_config = SocialNetworksConfig::load();
		
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
		
		$socialetworks_config->set_enabled_authentications($authentications_enabled);
		$socialetworks_config->set_client_ids($client_ids);
		$socialetworks_config->set_client_secrets($client_secrets);
		
		$this->save_new_config('social-networks-config', $socialetworks_config);
		
		return true;
	}
}
?>