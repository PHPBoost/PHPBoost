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

require_once PATH_TO_ROOT . '/SocialNetworks/lib/facebook/autoload.php';

class FacebookAuthenticationMethod extends AbstractSocialNetworkAuthenticationMethod
{
	private $facebook;

	public function __construct()
	{
		$config = SocialNetworksConfig::load();

		$this->facebook = new Facebook\Facebook(array(
			'app_id'  => $config->get_client_id(FacebookSocialNetwork::SOCIAL_NETWORK_ID),
			'app_secret' => $config->get_client_secret(FacebookSocialNetwork::SOCIAL_NETWORK_ID),
			'persistent_data_handler' => 'session'
		));
	}

	protected function get_external_authentication()
	{
		return new FacebookExternalAuthentication();
	}

	protected function get_user_data()
	{
		$request = AppContext::get_request();

		$helper = $this->facebook->getRedirectLoginHelper();

		if ($request->has_getparameter('error') && ($request->get_getvalue('error') == 'access_denied'))
		{
			$authenticate_type = $request->get_value('authenticate', false);
			if ($authenticate_type && $authenticate_type != PHPBoostAuthenticationMethod::AUTHENTICATION_METHOD && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
				AppContext::get_response()->redirect(UserUrlBuilder::edit_profile(AppContext::get_current_user()->get_id())->rel());
			else
				AppContext::get_response()->redirect(Environment::get_home_page());
		}
		else if (!$request->has_getparameter('state'))
			AppContext::get_response()->redirect($helper->getLoginUrl(UserUrlBuilder::connect(FacebookSocialNetwork::SOCIAL_NETWORK_ID)->absolute(), array('email')));

		$helper->getPersistentDataHandler()->set('state', $request->get_getvalue('state'));

		$accessToken = $helper->getAccessToken();

		if ($accessToken)
			$_SESSION[FacebookSocialNetwork::SOCIAL_NETWORK_ID . '_token'] = (string)$accessToken;
		else
			AppContext::get_response()->redirect($helper->getLoginUrl(UserUrlBuilder::connect(FacebookSocialNetwork::SOCIAL_NETWORK_ID)->absolute(), array('email')));

		try {
			$response = $this->facebook->get('/me?fields=id,name,email', $accessToken);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {}
		  catch(Facebook\Exceptions\FacebookSDKException $e) {}

		$user = !empty($response) ? $response->getGraphUser() : '';

		return array (
			'id'	=> !empty($user) ? $user->getId() : '',
			'name'	=> !empty($user) ? $user->getName() : '',
			'email'	=> !empty($user) ? $user->getEmail() : '',
			'picture_url'	=> !empty($user) ? 'https://graph.facebook.com/' . $user->getId() . '/picture' : ''
		);
	}
}
?>
