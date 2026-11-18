<?php
/**
 * The AuthenticationMethod interface could be implemented in different ways to enable specifics
 * authentication mecanisms.
 * PHPBoost comes with a PHPBoostAuthenticationMethod which will be performed on the internal member
 * list. But it is possible to implement external authentication mecanism by providing others
 * implementations of this class to support LDAP authentication, OpenID, Facebook connect and more...
 * @package     PHPBoost
 * @subpackage  User\authentication
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 11 28
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PHPBoostAuthenticationMethod extends AuthenticationMethod
{
	const AUTHENTICATION_METHOD = 'internal';

	private static $MAX_AUTHORIZED_ATTEMPTS = 5;
	private static $MAX_AUTHORIZED_ATTEMPTS_RESET_DELAY = 600;
	private static $MAX_AUTHORIZED_ATTEMPTS_RESET_ATTEMPS = 0;
	private static $MAX_AUTHORIZED_ATTEMPTS_PARTIAL_RESET_DELAY = 300;
	private static $MAX_AUTHORIZED_ATTEMPTS_PARTIAL_RESET_ATTEMPS = 3;

	/**
	 * @var DBQuerier
	 */
	private $querier;

	private $login;
	private $password;

	private $approved = true;
	private $registration_pass = '';

	private $connection_attempts = 0;
	private $last_connection_date;

	public function __construct($login, $password)
	{
		$this->login = $login;
		// Store plain password for verification with both bcrypt and legacy hashes
		$this->password = $password;
		$this->querier = PersistenceContext::get_querier();
	}

	public function set_association_parameters($approved = true, $registration_pass = '')
	{
		$this->approved = $approved;
		$this->registration_pass = $registration_pass;
	}

	public function get_remaining_attemps()
	{
		return self::$MAX_AUTHORIZED_ATTEMPTS - $this->connection_attempts;
	}

	/**
	 * {@inheritDoc}
	 */
	public function associate($user_id)
	{
		$internal_authentication_columns = [
			'user_id' => $user_id,
			'login' => $this->login,
			'password' => PasswordHasher::hash($this->password),
			'registration_pass' => $this->registration_pass,
			'last_connection' => time(),
			'approved' => (int)$this->approved
		];
		$authentication_method_columns = [
			'user_id' => $user_id,
			'method' => self::AUTHENTICATION_METHOD,
			'identifier' => $user_id
		];
		try {
			$this->querier->insert(DB_TABLE_INTERNAL_AUTHENTICATION, $internal_authentication_columns);
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
			$this->querier->delete(DB_TABLE_AUTHENTICATION_METHOD, 'WHERE user_id=:user_id AND method=:method', [
				'user_id' => $user_id,
				'method' => self::AUTHENTICATION_METHOD
			]);
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
		if ($this->login)
			return $this->try2authenticate();

		return false;
	}

	private function try2authenticate()
	{
		$user_id = 0;
		try
		{
			$user_id = $this->find_user_id_by_username();
		}
		catch (RowNotFoundException $ex) { }

		if (!empty($user_id))
		{
			$this->check_max_authorized_attempts();
			$match = $this->check_user_password($user_id);
			$this->update_user_info($user_id);
		}
		else
		{
			$failure_id = 0;
			try
			{
				$failure_id = $this->find_failure_login_tried_id_by_username();
			}
			catch (RowNotFoundException $ex) { }

			$this->check_max_authorized_attempts();

			$this->connection_attempts++;
			$this->last_connection_date = time();

			$this->delete_too_old_failure_attemps();

			if (!empty($failure_id))
				$this->update_failure_info($failure_id);
			else
				$this->insert_failure_info();

			$match = false;
		}

		$auth_infos = [];
		try {
			$auth_infos = self::get_auth_infos($user_id);
		} catch (RowNotFoundException $e) {
		}

		if (!empty($auth_infos) && !$auth_infos['approved'])
			$this->error_msg = LangLoader::get_message('user.registration.not.approved', 'user-lang');

		if (!$match)
		{
			$remaining_attempts = $this->get_remaining_attemps();
			if ($remaining_attempts > 0)
			{
				$this->error_msg = StringVars::replace_vars(LangLoader::get_message('warning.user.auth.password.flood', 'warning-lang'), ['remaining_tries' => $remaining_attempts]);
			}
			else
			{
				$this->error_msg = LangLoader::get_message('warning.user.auth.password.flood.max', 'warning-lang');
			}
		}

		$this->check_user_bannishment($user_id);

		if ($match && !$this->error_msg)
		{
			$this->update_user_last_connection_date($user_id);
			return $user_id;
		}
	}

	private function find_user_id_by_username()
	{
		$columns = ['user_id', 'last_connection', 'connection_attemps'];
		$condition = 'WHERE login=:login AND approved=1';
		$parameters = ['login' => $this->login];
		$row = $this->querier->select_single_row(DB_TABLE_INTERNAL_AUTHENTICATION, $columns, $condition, $parameters);
		$this->connection_attempts = $row['connection_attemps'];
		$this->last_connection_date = $row['last_connection'];
		return $row['user_id'];
	}

	private function check_max_authorized_attempts()
	{
		$delay_since_last_attempt = time() - $this->last_connection_date;
		if ($delay_since_last_attempt >= self::$MAX_AUTHORIZED_ATTEMPTS_RESET_DELAY)
		{
			$this->connection_attempts = self::$MAX_AUTHORIZED_ATTEMPTS_RESET_ATTEMPS;
		}
		elseif ($delay_since_last_attempt >= self::$MAX_AUTHORIZED_ATTEMPTS_PARTIAL_RESET_DELAY)
		{
			$this->connection_attempts = min($this->connection_attempts, self::$MAX_AUTHORIZED_ATTEMPTS_PARTIAL_RESET_ATTEMPS);
		}
		elseif ($this->connection_attempts >= self::$MAX_AUTHORIZED_ATTEMPTS)
		{
			$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('warning.user.auth.password.flood.max', 'warning-lang'));
			DispatchManager::redirect($controller);
		}
	}

	private function check_user_password($user_id)
	{
		$condition = 'WHERE user_id=:user_id';
		$parameters = ['user_id' => $user_id];

		try
		{
			$row = $this->querier->select_single_row(DB_TABLE_INTERNAL_AUTHENTICATION, ['password'], $condition, $parameters);
			$stored_hash = $row['password'];

			// Verify password against bcrypt or legacy hash
			$verification = PasswordHasher::verify($this->password, $stored_hash);

			if ($verification['verified'])
			{
				// If password needs migration from legacy to bcrypt
				if ($verification['needs_migration'])
				{
					$this->migrate_password_to_bcrypt($user_id);
				}

				$this->connection_attempts = 0;
				return true;
			}
			else
			{
				$this->connection_attempts++;
				return false;
			}
		}
		catch (RowNotFoundException $ex)
		{
			$this->connection_attempts++;
			return false;
		}
	}

	private function migrate_password_to_bcrypt($user_id): void
	{
		try
		{
			$bcrypt_hash = PasswordHasher::hash($this->password);
			$columns = ['password' => $bcrypt_hash];
			$condition = 'WHERE user_id=:user_id';
			$parameters = ['user_id' => $user_id];
			$this->querier->update(DB_TABLE_INTERNAL_AUTHENTICATION, $columns, $condition, $parameters);
		}
		catch (Exception $ex)
		{
			// Log error but don't break authentication
			error_log('Error migrating password to bcrypt for user ' . $user_id . ': ' . $ex->getMessage());
		}
	}

	private function update_user_info($user_id)
	{
		$this->last_connection_date = time();
		$columns = [
			'last_connection' => $this->last_connection_date,
			'connection_attemps' => $this->connection_attempts,
		];
		$condition = 'WHERE user_id=:user_id';
		$parameters = ['user_id' => $user_id];
		$this->querier->update(DB_TABLE_INTERNAL_AUTHENTICATION, $columns, $condition, $parameters);
	}

	public static function get_auth_infos($user_id)
	{
		$columns = ['login', 'password', 'approved'];
		$condition = 'WHERE user_id=:user_id';
		$parameters = ['user_id' => $user_id];
		return PersistenceContext::get_querier()->select_single_row(DB_TABLE_INTERNAL_AUTHENTICATION, $columns, $condition, $parameters);
	}

	public static function update_auth_infos($user_id, $login = null, $approved = null, $password = null, $registration_pass = null, $change_password_pass = null)
	{
        $columns = [];
		if (!empty($login))
			$columns['login'] = $login;

		if ($approved !== null)
			$columns['approved'] = (int)$approved;

		if (!empty($password))
			$columns['password'] = PasswordHasher::hash($password);

		if ($registration_pass !== null)
			$columns['registration_pass'] = $registration_pass;

		if ($change_password_pass !== null)
			$columns['change_password_pass'] = $change_password_pass;

		$condition = 'WHERE user_id=:user_id';
		$parameters = ['user_id' => $user_id];
		PersistenceContext::get_querier()->update(DB_TABLE_INTERNAL_AUTHENTICATION, $columns, $condition, $parameters);

		UserService::regenerate_cache();

		if ($approved !== null && !$approved)
		{
			PersistenceContext::get_querier()->delete(DB_TABLE_SESSIONS, $condition, $parameters);
			AutoConnectData::change_key($user_id);
		}
	}

	public static function registration_pass_exists($registration_pass)
	{
		try {
			$condition = 'WHERE registration_pass=:registration_pass';
			$parameters = ['registration_pass' => $registration_pass];
			return PersistenceContext::get_querier()->get_column_value(DB_TABLE_INTERNAL_AUTHENTICATION, 'user_id', $condition, $parameters);
		} catch (RowNotFoundException $e) {
			return false;
		}
	}

	public static function change_password_pass_exists($change_password_pass)
	{
		try {
			$condition = 'WHERE change_password_pass=:change_password_pass';
			$parameters = ['change_password_pass' => $change_password_pass];
			return PersistenceContext::get_querier()->get_column_value(DB_TABLE_INTERNAL_AUTHENTICATION, 'user_id', $condition, $parameters);
		} catch (RowNotFoundException $e) {
			return false;
		}
	}

	private function delete_too_old_failure_attemps()
	{
		$condition = 'WHERE last_connection < :reset_delay';
		$parameters = ['reset_delay' => time() - self::$MAX_AUTHORIZED_ATTEMPTS_RESET_DELAY];
		$this->querier->delete(DB_TABLE_INTERNAL_AUTHENTICATION_FAILURES, $condition, $parameters);
	}

	private function find_failure_login_tried_id_by_username()
	{
		$columns = ['id', 'last_connection', 'connection_attemps'];
		$condition = 'WHERE login=:login AND session_id=:session_id';
		$parameters = ['login' => $this->login, 'session_id' => AppContext::get_session()->get_session_id()];
		$row = $this->querier->select_single_row(DB_TABLE_INTERNAL_AUTHENTICATION_FAILURES, $columns, $condition, $parameters);
		$this->connection_attempts = $row['connection_attemps'];
		$this->last_connection_date = $row['last_connection'];
		return $row['id'];
	}

	private function insert_failure_info()
	{
		$columns = [
			'session_id' => AppContext::get_session()->get_session_id(),
			'login' => $this->login,
			'last_connection' => $this->last_connection_date,
			'connection_attemps' => $this->connection_attempts,
		];
		$this->querier->insert(DB_TABLE_INTERNAL_AUTHENTICATION_FAILURES, $columns);
	}

	private function update_failure_info($failure_id)
	{
		$columns = [
			'last_connection' => $this->last_connection_date,
			'connection_attemps' => $this->connection_attempts
		];
		$condition = 'WHERE id=:id';
		$parameters = ['id' => $failure_id];
		$this->querier->update(DB_TABLE_INTERNAL_AUTHENTICATION_FAILURES, $columns, $condition, $parameters);
	}
}
?>
