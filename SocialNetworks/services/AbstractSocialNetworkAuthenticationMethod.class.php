<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 26
 * @since       PHPBoost 5.1 - 2018 04 16
*/

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
						if (UserAccountsConfig::load()->is_registration_enabled())
						{
							$user = new User();

							if (empty($data['name']))
							{
								$mail_split = explode('@', $data['email']);
								$name = $mail_split[0];
								$user->set_display_name($name);
							}
							else
								$user->set_display_name($data['name']);

							$user->set_level(User::MEMBER_LEVEL);
							$user->set_email($data['email']);

							$auth_method = new static();
							$fields_data = !empty($data['picture_url']) ? array('user_avatar' => $data['picture_url']) : array();

							return UserService::create($user, $auth_method, $fields_data, $data);
						}
						else
							$this->error_msg = LangLoader::get_message('error.auth.registration_disabled', 'user-common');
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
			$maintain_config = MaintenanceConfig::load();
			
			if ($maintain_config->is_under_maintenance())
			{
				$session = AppContext::get_session();
				if ($session != null)
				{
					Session::delete($session);
				}
				$session_data = Session::create($user_id, true);
				AppContext::set_session($session_data);
				
				$current_user = CurrentUser::from_session();
				
				if (!$current_user->check_auth($maintain_config->get_auth(), MaintenanceConfig::ACCESS_WHEN_MAINTAIN_ENABLED_AUTHORIZATIONS))
				{
					$session = AppContext::get_session();
					Session::delete($session);
					$this->error_msg = LangLoader::get_message('user.not_authorized_during_maintain', 'status-messages-common');
				}
				else
					AppContext::get_response()->redirect(Environment::get_home_page());
			}
			else
			{
				$this->update_user_last_connection_date($user_id);
				return $user_id;
			}
		}
	}

	/**
	 * @desc Retrieves user data from the social network
	 * @return string[] user data (id, name, email, picture_url)
	 */
	abstract protected function get_user_data();
}
?>
