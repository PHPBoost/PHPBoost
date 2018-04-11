<?php
/*##################################################
 *                            LinkedInAuthenticationMethod.class.php
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

require_once PATH_TO_ROOT . '/SocialNetworks/lib/linkedin/LinkedIn.php';

class LinkedInAuthenticationMethod extends AuthenticationMethod
{
	const AUTHENTICATION_METHOD = 'linkedin';
	
	/**
	 * @var DBQuerier
	 */
	private $querier;
	private $linkedin;
	
	public function __construct()
	{
		$this->querier = PersistenceContext::get_querier();
		$config = SocialNetworksConfig::load();
		
		$this->linkedin = new LinkedIn\LinkedIn(array(
			'api_key'  => $config->get_linkedin_client_id(),
			'api_secret' => $config->get_linkedin_client_secret(),
			'callback_url' => UserUrlBuilder::connect(self::AUTHENTICATION_METHOD)->absolute()
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
						
						$auth_method = new LinkedInAuthenticationMethod();
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
		$now = new Date();
		
		$scope = array(
			LinkedIn\LinkedIn::SCOPE_BASIC_PROFILE, 
			LinkedIn\LinkedIn::SCOPE_EMAIL_ADDRESS
		);
		
		$request = AppContext::get_request();
		
		if ($request->has_getparameter('code')) 
			$_SESSION['linkedin_token'] = $this->linkedin->getAccessToken($request->get_getvalue('code'));

		if (isset($_SESSION['linkedin_token'])) 
			$this->linkedin->setAccessToken($_SESSION['linkedin_token']);

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
		else 
			AppContext::get_response()->redirect($this->linkedin->getLoginUrl($scope));
	}
}
?>
