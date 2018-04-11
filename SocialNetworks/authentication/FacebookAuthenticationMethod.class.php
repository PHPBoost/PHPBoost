<?php
/*##################################################
 *                            FacebookAuthenticationMethod.class.php
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

require_once PATH_TO_ROOT . '/SocialNetworks/lib/facebook/autoload.php';

class FacebookAuthenticationMethod extends AuthenticationMethod
{
	const AUTHENTICATION_METHOD = 'facebook';
	
	/**
	 * @var DBQuerier
	 */
	private $querier;
	private $facebook;
	
	public function __construct()
	{
		$this->querier = PersistenceContext::get_querier();
		$config = SocialNetworksConfig::load();
		
		session_start();
		$this->facebook = new Facebook\Facebook(array(
			'app_id'  => $config->get_facebook_app_id(),
			'app_secret' => $config->get_facebook_app_key(),
			'persistent_data_handler' => 'session'
		));
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
				'method' => self::AUTHENTICATION_METHOD,
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
					'method' => self::AUTHENTICATION_METHOD
				));
			} catch (SQLQuerierException $ex) {
				throw new IllegalArgumentException('User Id ' . $user_id .
					' is already dissociated with an authentication method [' . $ex->getMessage() . ']');
			}
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
				$user_id = $this->querier->get_column_value(DB_TABLE_AUTHENTICATION_METHOD, 'user_id', 'WHERE method=:method AND identifier=:identifier',  array('method' => self::AUTHENTICATION_METHOD, 'identifier' => $data['id']));
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
						
						$auth_method = new FacebookAuthenticationMethod();
						$fields_data = array('user_avatar' => 'https://graph.facebook.com/'. $data['id'] .'/picture');
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
		
		$helper = $this->facebook->getRedirectLoginHelper();
		
		if (!$request->has_getparameter('state'))
			AppContext::get_response()->redirect($helper->getLoginUrl(UserUrlBuilder::connect(self::AUTHENTICATION_METHOD)->absolute(), array('email')));
		
		$_SESSION['FBRLH_state'] = $request->get_getvalue('state');
		$helper->getPersistentDataHandler()->set('state', $request->get_getvalue('state'));
		
		$accessToken = $helper->getAccessToken();
		
		if ($accessToken)
			$_SESSION['facebook_access_token'] = (string)$accessToken;
		else
			AppContext::get_response()->redirect($helper->getLoginUrl(UserUrlBuilder::connect(self::AUTHENTICATION_METHOD)->absolute(), array('email')));
		
		$data = array (
			'id'	=> '',
			'name'	=> '',
			'email'	=> ''
		);
		
		try {
			// Returns a `Facebook\FacebookResponse` object
			$response = $this->facebook->get('/me?fields=id,name,email', $accessToken);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {}
		  catch(Facebook\Exceptions\FacebookSDKException $e) {}
		
		$user = $response->getGraphUser();
		
		if ($user)
		{
			$data['id'] = $user->getId();
			$data['name'] = $user->getName();
			$data['email'] = $user->getEmail();
		}
		
		return $data;
	}
}
?>
