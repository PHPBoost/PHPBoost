<?php
/**
 * The AuthenticationMethod interface could be implemented in different ways to enable specifics
 * authentication mecanisms.
 * PHPBoost comes with a PHPBoostAuthenticationMethod which will be performed on the internal member
 * list. But it is possible to implement external authentication mecanism by providing others
 * implementations of this class to support LDAP authentication, OpenID, Facebook connect and more...
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 14
 * @since       PHPBoost 5.1 - 2018 04 10
*/

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once PATH_TO_ROOT . '/SocialNetworks/lib/linkedin/LinkedIn.php';

class LinkedInAuthenticationMethod extends AbstractSocialNetworkAuthenticationMethod
{
	private $linkedin;

	public function __construct()
	{
		$config = SocialNetworksConfig::load();

		$this->linkedin = new LinkedIn\LinkedIn(array(
			'api_key'  => $config->get_client_id(LinkedInSocialNetwork::SOCIAL_NETWORK_ID),
			'api_secret' => $config->get_client_secret(LinkedInSocialNetwork::SOCIAL_NETWORK_ID),
			'callback_url' => UserUrlBuilder::connect(LinkedInSocialNetwork::SOCIAL_NETWORK_ID)->absolute()
		));
	}

	protected function get_external_authentication()
	{
		return new LinkedInExternalAuthentication();
	}

	protected function get_user_data()
	{
		$scope = array(
			LinkedIn\LinkedIn::SCOPE_BASIC_PROFILE,
			LinkedIn\LinkedIn::SCOPE_EMAIL_ADDRESS
		);

		$request = AppContext::get_request();

		if ($request->has_getparameter('code'))
			$_SESSION[LinkedInSocialNetwork::SOCIAL_NETWORK_ID . '_token'] = $this->linkedin->getAccessToken($request->get_getvalue('code'));

		if (isset($_SESSION[LinkedInSocialNetwork::SOCIAL_NETWORK_ID . '_token']))
			$this->linkedin->setAccessToken($_SESSION[LinkedInSocialNetwork::SOCIAL_NETWORK_ID . '_token']);

		if ($this->linkedin->hasAccessToken())
		{
			$user = $this->linkedin->get('/people/~:(id,email-address,first-name,last-name,picture-url)');

			return array(
				'id' => $user['id'],
				'email' => $user['emailAddress'],
				'name' => $user['firstName'] . ' ' . $user['lastName'],
				'picture_url' => isset($user['pictureUrl']) ? $user['pictureUrl'] : ''
			);
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
			AppContext::get_response()->redirect($this->linkedin->getLoginUrl($scope));
	}
}
?>
