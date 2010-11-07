<?php
/*##################################################
 *                            SessionData.class.php
 *                            -------------------
 *   begin                : November 04, 2010
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
 * @desc This class manages all sessions for the users.
 * @package {@package}
 */
class SessionData
{
	private static $KEY_USER_ID = 'user_id';
	private static $KEY_SESSION_ID = 'session_id';

	/**
	 * @desc
	 */
	public static function gc()
	{
		PersistenceContext::get_querier()->delete(DB_TABLE_SESSIONS, 'WHERE expiry < :now', array('now' => time()));
	}

	/**
	 * @desc
	 * @return SessionData
	 */
	public static function create_visitor()
	{
		return self::create_from_user_id(NewSession::VISITOR_SESSION_ID);
	}

	/**
	 * @desc
	 * @param int $user_id
	 * @return SessionData
	 */
	public static function create_from_user_id($user_id)
	{
		$data = new SessionData($user_id, Random::hexa64uid());
		$data->token = Random::hexa64uid(16);
		$data->expiry = time() + SessionsConfig::load()->get_session_duration();
		$data->ip = AppContext::get_request()->get_ip_address();
		$data->save();
		return $data;
	}

	/**
	 * @desc
	 * @param string $cookie_content
	 * @return SessionData
	 */
	public static function from_cookie($cookie_content)
	{
		$values = @unserialize($cookie_content);
		if ($values === false || empty($values[self::$KEY_USER_ID]) || empty($values[self::$KEY_SESSION_ID]))
		{
			throw UnexpectedValueException('invalid session data cookie content: "' . $cookie_content . '"');
		}
		try
		{
			$user_id = $values[self::$KEY_USER_ID];
			$session_id = $values[self::$KEY_SESSION_ID];
			$columns = array('token', 'expiry', 'ip', 'data');
			$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
			$parameters = array('user_id' => $user_id, 'session_id' => $session_id);
			$row = PersistenceContext::get_querier()->select_single_row(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
			$data = self::init_from_row($user_id, $session_id, $row);
			return self::update($data);
		}
		catch (RowNotFoundException $ex)
		{
			throw new SessionNotFoundException($user_id, $session_id);
		}
	}

	private static function init_from_row($user_id, $session_id, array $row)
	{
		$data = new SessionData($user_id, $session_id);
		$data->token = $row['token'];
		$data->expiry = $row['expiry'];
		$data->ip = $row['ip'];
		$data->data = unserialize($row['data']);
		return $data;
	}

	private static function update(SessionData $data)
	{
		$data->expiry = time() + SessionsConfig::load()->get_session_duration();
		$columns = array('expiry' => $data->expiry);
		$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
		$parameters = array('user_id' => $data->user_id, 'session_id' => $data->session_id);
		PersistenceContext::get_querier()->update(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
		return $data;
	}

	private $user_id;
	private $session_id;
	private $token;
	private $expiry;
	private $ip;
	private $data = array();

	protected function __construct($user_id, $session_id)
	{
		$this->user_id = $user_id;
		$this->session_id = $session_id;
	}

	public function get_user_id()
	{
		return $this->user_id;
	}

	public function get_session_id()
	{
		return $this->session_id;
	}

	public function get_token()
	{
		return $this->token;
	}

	public function get_expiry_date()
	{
		return $this->expiry;
	}

	public function get_ip()
	{
		return $this->ip;
	}

	public function get_data()
	{
		return $this->data;
	}

	public function save()
	{
		$this->create_in_db();
		$this->create_cookie();
	}

	public function delete()
	{
		$this->delete_in_db();
		$this->delete_cookie();
	}

	public function delete_in_db()
	{
		$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
		$parameters = array('user_id' => $this->user_id, 'session_id' => $this->session_id);
		PersistenceContext::get_querier()->delete(DB_TABLE_SESSIONS, $condition, $parameters);
	}

	public function delete_cookie()
	{
		$this->response->delete_cookie(self::$DATA_COOKIE_NAME);
	}

	private function create_in_db()
	{
		$columns = array(
			'user_id' => $this->user_id,
			'session_id' => $this->session_id,
			'token' => $this->token,
			'expiry' => $this->expiry,
			'ip' => $this->ip,
			'data' => serialize($this->data)
		);
		$row = PersistenceContext::get_querier()->insert(DB_TABLE_SESSIONS, $columns);
	}

	private function create_cookie()
	{
		$cookie = new HTTPCookie(NewSession::$DATA_COOKIE_NAME);
		$cookie->set_expiry_date(time() + 31536000);
		$cookie->set_value($this->get_serialized_content());
		AppContext::get_response()->set_cookie($cookie);
	}

	private function get_serialized_content()
	{
		return serialize(array(self::$KEY_USER_ID => $this->user_id, self::$KEY_SESSION_ID => $this->session_id));
	}
}

?>
