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

require_once PATH_TO_ROOT . '/SocialNetworks/lib/twitter/autoload.php';
use Twitter\TwitterOAuth;

class TwitterAuthenticationMethod extends AuthenticationMethod
{
	/**
	 * @var DBQuerier
	 */
	private $querier;
	
	public function __construct()
	{
		$this->querier = PersistenceContext::get_querier();
		session_start();
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function associate($user_id, $data = array())
	{
		if (!$data)
			$data = $this->get_user_data();

		if ($data)
		{
			$authentication_method_columns = array(
				'user_id' => $user_id,
				'method' => TwitterSocialNetwork::SOCIAL_NETWORK_ID,
				'identifier' => $data['id'],
				'data' => TextHelper::serialize($data)
			);
			try {
				$this->querier->insert(DB_TABLE_AUTHENTICATION_METHOD, $authentication_method_columns);
			} catch (SQLQuerierException $ex) {
				throw new IllegalArgumentException('User Id ' . $user_id .
					' is already associated with an authentication method [' . $ex->getMessage() . ']');
			}
		}
		else
			$this->error_msg = LangLoader::get_message('external-auth.email-not-found', 'user-common');
	}

	/**
	 * {@inheritDoc}
	 */
	public function dissociate($user_id)
	{
		if ($this->querier->count(DB_TABLE_AUTHENTICATION_METHOD, 'WHERE user_id=:user_id', array('user_id' => $user_id)) > 1)
		{
			try {
				$this->querier->delete(DB_TABLE_AUTHENTICATION_METHOD, 'WHERE user_id=:user_id AND method=:method', array(
					'user_id' => $user_id,
					'method' => TwitterSocialNetwork::SOCIAL_NETWORK_ID
				));
			} catch (SQLQuerierException $ex) {
				throw new IllegalArgumentException('User Id ' . $user_id .
					' is already dissociated with an authentication method [' . $ex->getMessage() . ']');
			}
			if (isset($_SESSION['twitter_token']))
				unset($_SESSION['twitter_token']);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function authenticate()
	{
		$user_id = 0;
		$data = $this->get_user_data();
		
		if ($data)
		{
			try {
				$user_id = $this->querier->get_column_value(DB_TABLE_AUTHENTICATION_METHOD, 'user_id', 'WHERE method=:method AND identifier=:identifier',  array('method' => TwitterSocialNetwork::SOCIAL_NETWORK_ID, 'identifier' => $data['id']));
			} catch (RowNotFoundException $e) {
				
				if (!empty($data['email']))
				{
					$email_exists = $this->querier->row_exists(DB_TABLE_MEMBER, 'WHERE email=:email', array('email' => $data['email']));
					if ($email_exists || !AppContext::get_current_user()->is_guest())
					{
						if (!AppContext::get_current_user()->is_guest())
							$this->associate(AppContext::get_current_user()->get_id(), $data);
						else
							$this->error_msg = LangLoader::get_message('external-auth.account-exists', 'user-common');
					}
					else
					{
						$user = new User();
						
						if (empty($data['name']))
						{
							$mail_split = explode('@', $data['email']);
							$name = $mail_split[0];
							$user->set_display_name(utf8_decode($name));
						}
						else
							$user->set_display_name(utf8_decode($data['name']));
						
						$user->set_level(User::MEMBER_LEVEL);
						$user->set_email($data['email']);
						
						$auth_method = new TwitterAuthenticationMethod();
						if (!empty($data['picture_url']))
							$fields_data = array('user_avatar' => $data['picture_url']);
						else
							$fields_data = array();
						return UserService::create($user, $auth_method, $fields_data, $data);
					}
				}
				else
					$this->error_msg = LangLoader::get_message('external-auth.email-not-found', 'user-common');
			}
		}
		else
			$this->error_msg = LangLoader::get_message('external-auth.email-not-found', 'user-common');
		
		$this->check_user_bannishment($user_id);
		
		if (!$this->error_msg)
		{
			$this->update_user_last_connection_date($user_id);
			return $user_id;
		}
	}

	private function get_user_data()
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
