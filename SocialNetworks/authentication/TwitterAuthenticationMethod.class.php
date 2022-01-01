<?php
/**
 * The AuthenticationMethod interface could be implemented in different ways to enable specifics
 * authentication mecanisms.
 * PHPBoost comes with a PHPBoostAuthenticationMethod which will be performed on the internal member
 * list. But it is possible to implement external authentication mecanism by providing others
 * implementations of this class to support LDAP authentication, OpenID, Facebook connect and more...
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 14
 * @since       PHPBoost 5.1 - 2018 04 10
*/

if (session_status() == PHP_SESSION_NONE)
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
