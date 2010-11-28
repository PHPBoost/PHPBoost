<?php
/*##################################################
 *                            PHPBoostAuthenticationMethod.class.php
 *                            -------------------
 *   begin                : November 28, 2010
 *   copyright            : (C) 2010 loic rouchon
 *   email                : horn@phpboost.com
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
 * @author Loic Rouchon <horn@phpboost.com>
 * @desc The AuthenticationMethod interface could be implemented in different ways to enable specifics
 * authentication mecanisms.
 * PHPBoost comes with a PHPBoostAuthenticationMethod which will be performed on the internal member
 * list. But it is possible to implement external authentication mecanism by providing others
 * implementations of this class to support LDAP authentication, OpenID, Facebook connect and more...
 *
 * @package {@package}
 */
class PHPBoostAuthenticationMethod implements AuthenticationMethod
{
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

	private $user_id = -1;
	private $connection_attemps = 0;
	private $last_connection_date;

	private $success = false;

	public function __construct($login, $password)
	{
		$this->login = $login;
		$this->password = $password;
		$this->querier = PersistenceContext::get_querier();
	}

	/**
	 * {@inheritDoc}
	 */
	public function authenticate()
	{
		try
		{
			return $this->try2authenticate();
		}
		catch (RowNotFoundException $ex)
		{
			return false;
		}
		catch (NotASingleRowFoundException $ex)
		{
			return false;
		}
	}

	private function try2authenticate()
	{
		$this->find_user_id_by_login();
		$this->check_max_authorized_attempts();
		$match = $this->check_user_password();
		$this->update_user_info();
		return $match;
	}

	private function find_user_id_by_login()
	{
		$columns = array('user_id', 'last_connect', 'test_connect');
		$condition = 'WHERE login=:login';
		$parameters = array('login' => $this->login);
		$row = $this->querier->select_single_row(DB_TABLE_MEMBERS, $columns, $condition, $parameters);
		$this->user_id = $row['user_id'];
		$this->connection_attemps = $row['test_connect'];
		$this->last_connection_date = $row['last_connect'];
	}

	private function check_max_authorized_attempts()
	{
		$delay_since_last_attempt = time() - $this->last_connection_date;
		if ($delay_since_last_attempt >= self::$MAX_AUTHORIZED_ATTEMPTS_RESET_DELAY)
		{
			$this->connection_attemps = self::$MAX_AUTHORIZED_ATTEMPTS_RESET_ATTEMPS;
		}
		elseif ($delay_since_last_attempt >= self::$MAX_AUTHORIZED_ATTEMPTS_PARTIAL_RESET_DELAY)
		{
			$this->connection_attemps = min($this->connection_attemps, self::$MAX_AUTHORIZED_ATTEMPTS_PARTIAL_RESET_ATTEMPS);
		}
		elseif ($this->connection_attemps > self::$MAX_AUTHORIZED_ATTEMPTS)
		{
			AppContext::get_response()->redirect('/member/error.php?e=e_member_flood#errorh');
		}
	}

	private function check_user_password()
	{
		$condition = 'WHERE user_id=:user_id and password=:password';
		$parameters = array('user_id' => $this->user_id, 'password' => strhash($this->password));
		$match = $this->querier->count(DB_TABLE_MEMBERS, $condition, $parameters, '*') == 1;
		if ($match)
		{
			$this->connection_attemps = 0;
		}
		else
		{
			$this->connection_attemps++;
		}
		return $match;
	}

	private function update_user_info()
	{
		$this->last_connection_date = time();
		$columns = array(
			'last_connect' => $this->last_connection_date,
			'test_connect' => $this->connection_attemps,
		);
		$condition = 'WHERE user_id=:user_id';
		$parameters = array('user_id' => $this->user_id);
		$this->querier->update(DB_TABLE_MEMBERS, $columns, $condition, $parameters);
	}
}

?>
