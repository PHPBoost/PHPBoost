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

require_once PATH_TO_ROOT . '/kernel/lib/php/google/Google_Client.php';
require_once PATH_TO_ROOT . '/kernel/lib/php/google/contrib/Google_Oauth2Service.php';

class GoogleAuthenticationMethod extends AuthenticationMethod
{
	const AUTHENTICATION_METHOD = 'google';
	
	/**
	 * @var DBQuerier
	 */
	private $querier;
	private $google_client;
	private $google_auth;
	
	public function __construct()
	{
		$this->querier = PersistenceContext::get_querier();
		$auth_config = AuthenticationConfig::load();

		$this->google_client = new Google_Client();
		$this->google_client->setClientId($auth_config->get_google_client_id());
		$this->google_client->setClientSecret($auth_config->get_google_client_secret());
		$this->google_client->setRedirectUri(UserUrlBuilder::connect('google')->absolute());
		$this->google_client->setScopes(array(
			  'https://www.googleapis.com/auth/userinfo.profile',
			  'https://www.googleapis.com/auth/userinfo.email',
		  ));
		$this->google_auth = new Google_Oauth2Service($this->google_client);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function associate($user_id)
	{
		$data = $this->get_google_user_data();

		$authentication_method_columns = array(
			'user_id' => $user_id,
			'method' => self::AUTHENTICATION_METHOD,
			'identifier' => $data['id'],
			'data' => serialize($data)
		);
		try {
			$this->querier->insert(DB_TABLE_AUTHENTICATION_METHOD, $authentication_method_columns);
		} catch (SQLQuerierException $ex) {
			throw new IllegalArgumentException('User Id ' . $user_id .
				' is already associated with an authentication method [' . $ex->getMessage() . ']');
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function dissociate($user_id)
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


	/**
	 * {@inheritDoc}
	 */
	public function authenticate()
	{
		$user_id = 0;
		$data = $this->get_google_user_data();
		$google_id = $data['id'];

		try {
			$user_id = $this->querier->get_column_value(DB_TABLE_AUTHENTICATION_METHOD, 'user_id', 'WHERE method=:method AND identifier=:identifier',  array('method' => self::AUTHENTICATION_METHOD, 'identifier' => $google_id));
		} catch (RowNotFoundException $e) {
			
			if (!empty($data['email']))
			{
				$email_exists = $this->querier->row_exists(DB_TABLE_MEMBER, 'WHERE email=:email', array('email' => $data['email']));
				if ($email_exists)
				{
					$this->error_msg = LangLoader::get_message('external-auth.account-exists', 'user-common');
				}
				else
				{
					$user = new User();
					$user->set_display_name(utf8_decode($data['name']));
					$user->set_level(User::MEMBER_LEVEL);
					$user->set_email($data['email']);
					
					$auth_method = new GoogleAuthenticationMethod();
					$fields_data = array('user_avatar' => $data['picture']);
					return UserService::create($user, $auth_method, $fields_data);
				}
			}
			else
				$this->error_msg = LangLoader::get_message('external-auth.email-not-found', 'user-common');
		}
		
		$this->update_user_info($user_id);
		return $user_id;
	}

	private function get_google_user_data()
	{
		$request = AppContext::get_request();
		
		if ($request->has_parameter('reset')) 
		{
			unset($_SESSION['token']);
			$this->google_client->revokeToken();
			AppContext::get_response()->redirect($this->google_client->getRedirectUri());
		}

		if ($request->has_getparameter('code')) 
		{
			$this->google_client->authenticate($request->get_getvalue('code'));
			$_SESSION['token'] = $this->google_client->getAccessToken();
			AppContext::get_response()->redirect($this->google_client->getRedirectUri());
		}

		if (isset($_SESSION['token'])) 
		{
			$this->google_client->setAccessToken($_SESSION['token']);
		}

		if ($this->google_client->getAccessToken()) 
		{
			$user 				= $this->google_auth->userinfo->get();
			$_SESSION['token'] 	= $this->google_client->getAccessToken();
			return $user;
		}
		else 
		{
			AppContext::get_response()->redirect($this->google_client->createAuthUrl());
		}
	}

	private function update_user_info($user_id)
	{
		$this->querier->update(DB_TABLE_MEMBER, array('last_connection_date' => time()), 'WHERE user_id=:user_id', array('user_id' => $user_id));
	}
}
?>
