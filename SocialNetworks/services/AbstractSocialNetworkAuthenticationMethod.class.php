<?php
/*##################################################
 *                        AbstractSocialNetworkAuthenticationMethod.class.php
 *                            -------------------
 *   begin                : April 16, 2018
 *   copyright            : (C) 2018 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
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

abstract class AbstractSocialNetworkAuthenticationMethod extends AuthenticationMethod
{
	/**
	 * @return ExternalAuthentication class
	 */
	abstract protected function get_external_authentication();
	
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
				'method' => $this->get_external_authentication()->get_authentication_id(),
				'identifier' => $data['id'],
				'data' => TextHelper::serialize($data)
			);
			try {
				PersistenceContext::get_querier()->insert(DB_TABLE_AUTHENTICATION_METHOD, $authentication_method_columns);
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
		$querier = PersistenceContext::get_querier();
		
		if ($querier->count(DB_TABLE_AUTHENTICATION_METHOD, 'WHERE user_id=:user_id', array('user_id' => $user_id)) > 1)
		{
			try {
				$querier->delete(DB_TABLE_AUTHENTICATION_METHOD, 'WHERE user_id=:user_id AND method=:method', array(
					'user_id' => $user_id,
					'method' => $this->get_external_authentication()->get_authentication_id()
				));
			} catch (SQLQuerierException $ex) {
				throw new IllegalArgumentException('User Id ' . $user_id .
					' is already dissociated with an authentication method [' . $ex->getMessage() . ']');
			}
			$this->get_external_authentication()->delete_session_token();
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function authenticate()
	{
		$querier = PersistenceContext::get_querier();
		$user_id = 0;
		$data = $this->get_user_data();
		
		if ($data)
		{
			try {
				$user_id = $querier->get_column_value(DB_TABLE_AUTHENTICATION_METHOD, 'user_id', 'WHERE method=:method AND identifier=:identifier',  array('method' => $this->get_external_authentication()->get_authentication_id(), 'identifier' => $data['id']));
			} catch (RowNotFoundException $e) {
				
				if (!empty($data['email']))
				{
					$email_exists = $querier->row_exists(DB_TABLE_MEMBER, 'WHERE email=:email', array('email' => $data['email']));
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
						
						$auth_method = new static();
						$fields_data = !empty($data['picture_url']) ? array('user_avatar' => $data['picture_url']) : array();
						
						return UserService::create($user, $auth_method, $fields_data, $data);
					}
				}
				else
					$this->error_msg = LangLoader::get_message('external-auth.email-not-found', 'user-common');
			}
		}
		else
			$this->error_msg = LangLoader::get_message('external-auth.user-data-not-found', 'user-common');
		
		$this->check_user_bannishment($user_id);
		
		if (!$this->error_msg)
		{
			$this->update_user_last_connection_date($user_id);
			return $user_id;
		}
	}
	
	/**
	 * @desc Retrieves user data from the social network
	 * @return string[] user data (id, name, email, picture_url)
	 */
	abstract protected function get_user_data();
}
?>
