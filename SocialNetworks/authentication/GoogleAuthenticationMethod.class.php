<?php
/**
 * The AuthenticationMethod interface could be implemented in different ways to enable specifics
 * authentication mecanisms.
 * PHPBoost comes with a PHPBoostAuthenticationMethod which will be performed on the internal member
 * list. But it is possible to implement external authentication mecanism by providing others
 * implementations of this class to support LDAP authentication, OpenID, Facebook connect and more...
 * This class provides easy ways to create several type of charts.
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 14
 * @since       PHPBoost 4.1 - 2014 11 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

if (session_status() == PHP_SESSION_NONE)
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
