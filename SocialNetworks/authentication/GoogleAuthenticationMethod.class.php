<?php
/*##################################################
 *                            GoogleAuthenticationMethod.class.php
 *                            -------------------
 *   begin                : November 28, 2014
 *   copyright            : (C) 2014 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc The AuthenticationMethod interface could be implemented in different ways to enable specifics
 * authentication mecanisms.
 * PHPBoost comes with a PHPBoostAuthenticationMethod which will be performed on the internal member
 * list. But it is possible to implement external authentication mecanism by providing others
 * implementations of this class to support LDAP authentication, OpenID, Facebook connect and more...
 *
 * @package {@package}
 */

session_start();

require_once PATH_TO_ROOT . '/SocialNetworks/lib/google/Google_Client.php';
require_once PATH_TO_ROOT . '/SocialNetworks/lib/google/contrib/Google_Oauth2Service.php';

class GoogleAuthenticationMethod extends AbstractSocialNetworkAuthenticationMethod
{
	private $google_client;
	private $google_auth;
	
	public function __construct()
	{
		$config = SocialNetworksConfig::load();

		$this->google_client = new Google_Client();
		$this->google_client->setClientId($config->get_client_id(GoogleSocialNetwork::SOCIAL_NETWORK_ID));
		$this->google_client->setClientSecret($config->get_client_secret(GoogleSocialNetwork::SOCIAL_NETWORK_ID));
		$this->google_client->setRedirectUri(UserUrlBuilder::connect(GoogleSocialNetwork::SOCIAL_NETWORK_ID)->absolute());
		$this->google_client->setScopes(array(
			  'https://www.googleapis.com/auth/userinfo.profile',
			  'https://www.googleapis.com/auth/userinfo.email',
		  ));
		$this->google_auth = new Google_Oauth2Service($this->google_client);
	}
	
	protected function get_external_authentication()
	{
		return new GoogleExternalAuthentication();
	}

	protected function get_user_data()
	{
		$request = AppContext::get_request();
		
		if ($request->has_parameter('reset')) 
		{
			unset($_SESSION['google_token']);
			$this->google_client->revokeToken();
			AppContext::get_response()->redirect($this->google_client->getRedirectUri());
		}
		
		if ($request->has_getparameter('code')) 
		{
			$this->google_client->authenticate($request->get_getvalue('code'));
			$_SESSION['google_token'] = $this->google_client->getAccessToken();
			AppContext::get_response()->redirect($this->google_client->getRedirectUri());
		}
		
		if (isset($_SESSION['google_token'])) 
			$this->google_client->setAccessToken($_SESSION['google_token']);
		
		if ($this->google_client->getAccessToken()) 
		{
			$user = $this->google_auth->userinfo->get();
			return array_merge($user, array('picture_url' => $user['picture']));
		}
		else if ($request->has_getparameter('error') && ($request->get_getvalue('error') == 'access_denied'))
		{
			$authenticate_type = $request->get_value('authenticate', false);
			if ($authenticate_type && $authenticate_type != PHPBoostAuthenticationMethod::AUTHENTICATION_METHOD && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
				AppContext::get_response()->redirect(UserUrlBuilder::edit_profile(AppContext::get_current_user()->get_id())->rel());
			else
				AppContext::get_response()->redirect(Environment::get_home_page());
		}
		else 
			AppContext::get_response()->redirect($this->google_client->createAuthUrl());
	}
}
?>
