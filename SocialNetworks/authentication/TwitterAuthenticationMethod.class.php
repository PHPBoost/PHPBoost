<?php
/*##################################################
 *                            TwitterAuthenticationMethod.class.php
 *                            -------------------
 *   begin                : April 10, 2018
 *   copyright            : (C) 2018 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @desc The AuthenticationMethod interface could be implemented in different ways to enable specifics
 * authentication mecanisms.
 * PHPBoost comes with a PHPBoostAuthenticationMethod which will be performed on the internal member
 * list. But it is possible to implement external authentication mecanism by providing others
 * implementations of this class to support LDAP authentication, OpenID, Facebook connect and more...
 *
 * @package {@package}
 */

session_start();

require_once PATH_TO_ROOT . '/SocialNetworks/lib/twitter/autoload.php';
use Twitter\TwitterOAuth;

class TwitterAuthenticationMethod extends AbstractSocialNetworkAuthenticationMethod
{
	protected function get_external_authentication()
	{
		return new TwitterExternalAuthentication();
	}
	
	protected function get_user_data()
	{
		$request = AppContext::get_request();
		$config = SocialNetworksConfig::load();
		
		if (!empty($_SESSION['twitter_token']) && !empty($_SESSION['twitter_token']['oauth_token']) && !empty($_SESSION['twitter_token']['oauth_token_secret']))
		{
			$access_token = $_SESSION['twitter_token'];
			$connection = new TwitterOAuth($config->get_client_id(TwitterSocialNetwork::SOCIAL_NETWORK_ID), $config->get_client_secret(TwitterSocialNetwork::SOCIAL_NETWORK_ID), $access_token['oauth_token'], $access_token['oauth_token_secret']);
		}
		else
		{
			if ($request->has_getparameter('oauth_token') && $_SESSION['twitter_oauth_token'] === $request->get_getvalue('oauth_token'))
			{
				$connection = new TwitterOAuth($config->get_client_id(TwitterSocialNetwork::SOCIAL_NETWORK_ID), $config->get_client_secret(TwitterSocialNetwork::SOCIAL_NETWORK_ID), $_SESSION['twitter_oauth_token'], $_SESSION['twitter_oauth_token_secret']);
				
				unset($_SESSION['twitter_oauth_token']);
				unset($_SESSION['twitter_oauth_token_secret']);
				$_SESSION['twitter_token'] = $access_token = $connection->oauth('oauth/access_token', array('oauth_verifier' => $request->get_getvalue('oauth_verifier')));
				
				$connection = new TwitterOAuth($config->get_client_id(TwitterSocialNetwork::SOCIAL_NETWORK_ID), $config->get_client_secret(TwitterSocialNetwork::SOCIAL_NETWORK_ID), $access_token['oauth_token'], $access_token['oauth_token_secret']);
			}
			else if ($request->has_getparameter('denied') && ($request->get_getvalue('denied') != ''))
			{
				$authenticate_type = $request->get_value('authenticate', false);
				if ($authenticate_type && $authenticate_type != PHPBoostAuthenticationMethod::AUTHENTICATION_METHOD && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
					AppContext::get_response()->redirect(UserUrlBuilder::edit_profile(AppContext::get_current_user()->get_id())->rel());
				else
					AppContext::get_response()->redirect(Environment::get_home_page());
			}
			else
			{
				$connection = new TwitterOAuth($config->get_client_id(TwitterSocialNetwork::SOCIAL_NETWORK_ID), $config->get_client_secret(TwitterSocialNetwork::SOCIAL_NETWORK_ID));
				$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => UserUrlBuilder::connect(TwitterSocialNetwork::SOCIAL_NETWORK_ID)->absolute()));
				$_SESSION['twitter_oauth_token'] = $request_token['oauth_token'];
				$_SESSION['twitter_oauth_token_secret'] = $request_token['oauth_token_secret'];
				
				AppContext::get_response()->redirect($connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token'])));
			}
		}
		
		$user = $connection->get('account/verify_credentials', array('include_email' => 'true'));
		
		return array(
			'id' => $user->id,
			'email' => $user->email,
			'name' => $user->name,
			'picture_url' => $user->profile_image_url_https
		);
	}
}
?>
