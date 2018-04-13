<?php
/*##################################################
 *		                        TwitterExternalAuthentication.class.php
 *                            -------------------
 *   begin                : April 10, 2018
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

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class TwitterExternalAuthentication implements ExternalAuthentication
{
	public function get_authentication_id()
	{
		return TwitterSocialNetwork::SOCIAL_NETWORK_ID;
	}
	
	public function get_social_network()
	{
		return new TwitterSocialNetwork();
	}

	public function get_authentication_name()
	{
		return StringVars::replace_vars(LangLoader::get_message('sign-in-label', 'common', 'SocialNetworks'), array('name' => $this->get_social_network()->get_name()));
	}

	public function authentication_actived()
	{
		return SocialNetworksConfig::load()->is_authentication_available(TwitterSocialNetwork::SOCIAL_NETWORK_ID);
	}

	public function get_image_renderer_html()
	{
		$tpl = new FileTemplate('SocialNetworks/auth_image_render.tpl');
		$tpl->put_all(array(
			'ID' => TwitterSocialNetwork::SOCIAL_NETWORK_ID,
			'NAME' => $this->get_social_network()->get_name()
		));
		return $tpl->render();
	}

	public function get_authentication()
	{
		return new TwitterAuthenticationMethod();
	}

	public function delete_session_token()
	{
		if (isset($_SESSION['twitter_token']))
			unset($_SESSION['twitter_token']);
	}
}
?>