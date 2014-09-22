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
	const DEFAULT_VISITOR_DISPLAY_NAME = 'visitor';

	private static $KEY_USER_ID = 'user_id';
	private static $KEY_SESSION_ID = 'session_id';

	public static function admin_session()
	{
		return new SessionData(1, null);
	}

	/**
	 * @desc
	 */
	public static function gc()
	{
		PersistenceContext::get_querier()->delete(DB_TABLE_SESSIONS, 'WHERE timestamp < :now', array('now' => time() - SessionsConfig::load()->get_session_duration()));
	}

	/**
	 * @desc
	 * @return SessionData
	 */
	public static function create_visitor()
	{
		return self::create_from_user_id(Session::VISITOR_SESSION_ID);
	}

	/**
	 * @desc
	 * @param int $user_id
	 * @return SessionData
	 */
	public static function create_from_user_id($user_id)
	{
		$data = null;
		if ($user_id != Session::VISITOR_SESSION_ID && self::session_exists($user_id))
		{
			$data = self::use_existing_session($user_id);
		}
		else
		{
			$data = new SessionData($user_id, KeyGenerator::generate_key(64));
			$data->token = KeyGenerator::generate_key(16);
			$data->timestamp = time();
			$data->ip = AppContext::get_request()->get_ip_address();
			self::fill_user_cached_data($data);
			$data->create();
		}
		return $data;
	}

	public static function update_location($title_page)
	{
		$data = AppContext::get_session();
		
		if ($data->no_session_location)
			$columns = array('timestamp' => $data->timestamp);
		else
			$columns = array('timestamp' => $data->timestamp, 'location_title' => $title_page, 'location_script' => REWRITED_SCRIPT);
			
		$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
		$parameters = array('user_id' => $data->user_id, 'session_id' => $data->session_id);
		PersistenceContext::get_querier()->update(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
		return $data;
	}
	
	public static function recheck_cached_data_from_user_id($user_id)
	{
		if ($user_id != Session::VISITOR_SESSION_ID && self::session_exists($user_id))
		{
			$data = self::get_existing_session($user_id);
			$data->recheck_cached_data();
		}
	}

	/**
	 * @desc
	 * @param int $user_id
	 * @return SessionData
	 */
	private static function session_exists($user_id)
	{
		$condition = 'WHERE user_id=:user_id';
		$parameters = array('user_id' => $user_id);
		return PersistenceContext::get_querier()->row_exists(DB_TABLE_SESSIONS, $condition, $parameters);
	}

	/**
	 * @desc
	 * @param int $user_id
	 * @return SessionData
	 */
	private static function use_existing_session($user_id)
	{
		self::update_existing_session($user_id);
		$data = self::get_existing_session($user_id);
		$data->create_cookie();
		return $data;
	}
	
	private static function get_existing_session($user_id)
	{
		$parameters = array('user_id' => $user_id);
		$condition = 'WHERE user_id=:user_id';
		$columns = array('session_id', 'token', 'timestamp', 'ip', 'location_script', 'location_title', 'data', 'cached_data');
		$row = PersistenceContext::get_querier()->select_single_row(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
		return self::init_from_row($user_id, $row['session_id'], $row);
	}

	private static function update_existing_session($user_id)
	{
		$columns = array(
			'timestamp' => time() + SessionsConfig::load()->get_session_duration(),
			'ip' => AppContext::get_request()->get_ip_address()
		);
		$parameters = array('user_id' => $user_id);
		$condition = 'WHERE user_id=:user_id';
		PersistenceContext::get_querier()->update(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
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
			throw new UnexpectedValueException('invalid session data cookie content: "' . $cookie_content . '"');
		}
		try
		{
			$user_id = $values[self::$KEY_USER_ID];
			$session_id = $values[self::$KEY_SESSION_ID];
			$columns = array('token', 'timestamp', 'ip', 'location_script', 'location_title', 'data', 'cached_data');
			$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
			$parameters = array('user_id' => $user_id, 'session_id' => $session_id);
			$row = PersistenceContext::get_querier()->select_single_row(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
			$data = self::init_from_row($user_id, $session_id, $row);
			$data->timestamp = time();
			return $data;
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
		$data->timestamp = $row['timestamp'];
		$data->ip = $row['ip'];
		$data->location_script = $row['location_script'];
		$data->location_title = $row['location_title'];
		$data->cached_data = unserialize($row['cached_data']);
		$data->data = unserialize($row['data']);
		return $data;
	}

	private static function fill_user_cached_data(SessionData $data)
	{
		$columns = array('display_name', 'level', 'email', 'show_email', 'locale', 'theme', 'timezone', 'editor',
			'unread_pm', 'posted_msg', 'registration_date', 'last_connection_date', 'groups', 'warning_percentage', 'delay_banned', 'delay_readonly');
		$condition = 'WHERE user_id=:user_id';
		$parameters = array('user_id' => $data->user_id);
		try
		{
			$data->cached_data = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, $columns, $condition, $parameters);
		}
		catch (RowNotFoundException $ex)
		{
			$data->cached_data = User::get_visitor_properties(self::DEFAULT_VISITOR_DISPLAY_NAME);
		}
	}

	protected $user_id;
	protected $session_id;
	protected $token;
	protected $timestamp;
	protected $ip;
	protected $location_script;
	protected $location_title;
	protected $cached_data = array();
	protected $data = array();

	protected $cached_data_modified = false;
	protected $data_modified = false;
	
	/**
	 * True for not updating the location where the user is located
	 * @var bool
	 */
	protected $no_session_location = false;

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
		return $this->timestamp + SessionsConfig::load()->get_session_duration();
	}

	public function get_timestamp()
	{
		return $this->timestamp;
	}

	public function get_ip()
	{
		return $this->ip;
	}

	public function get_location_script()
	{
		return $this->location_script;
	}

	public function get_location_title()
	{
		return $this->location_title;
	}

	public function get_all_cached_data()
	{
		return $this->cached_data;
	}

	public function has_cached_data($key)
	{
		return isset($this->cached_data[$key]);
	}

	public function get_cached_data($key, $default = null)
	{
		if (array_key_exists($key, $this->cached_data))
		{
			return $this->cached_data[$key];
		}
		return $default;
	}

	public function add_cached_data($key, $value)
	{
		$this->cached_data_modified = true;
		$this->cached_data[$key] = $value;
	}

	public function remove_cached_data($key)
	{
		$this->cached_data_modified = true;
		unset($this->cached_data[$key]);
	}

	public function get_all_data()
	{
		return $this->data;
	}

	public function has_data($key)
	{
		return isset($this->data[$key]);
	}

	public function get_data($key)
	{
		if (array_key_exists($key, $this->cached_data))
		{
			return $this->data[$key];
		}
		return $default;
	}

	public function add_data($key, $value)
	{
		$this->data_modified = true;
		$this->data[$key] = $value;
	}

	public function remove_data($key)
	{
		$this->data_modified = true;
		unset($this->data[$key]);
	}

	public function recheck_cached_data()
	{
		self::fill_user_cached_data($this);
		$this->cached_data_modified = true;
		$this->save();
	}
	
	public function save()
	{
		if ($this->cached_data_modified || $this->data_modified)
		{
			$columns = array();
			if ($this->cached_data_modified)
			{
				$columns['cached_data'] = serialize($this->cached_data);
			}
			if ($this->data_modified)
			{
				$columns['data'] = serialize($this->data);
			}
			$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
			$parameters = array('user_id' => $this->user_id, 'session_id' => $this->session_id);
			PersistenceContext::get_querier()->update(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
			$this->cached_data_modified = false;
			$this->data_modified = false;
		}
	}

	private function create()
	{
		$this->create_in_db();
		$this->create_cookie();
	}

	public function delete()
	{
		$this->delete_in_db();
		$this->delete_cookie();
	}

	private function delete_in_db()
	{
		$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
		$parameters = array('user_id' => $this->user_id, 'session_id' => $this->session_id);
		PersistenceContext::get_querier()->delete(DB_TABLE_SESSIONS, $condition, $parameters);
	}

	private function delete_cookie()
	{
		AppContext::get_response()->delete_cookie(Session::$DATA_COOKIE_NAME);
	}

	private function create_in_db()
	{
		$columns = array(
			'user_id' => $this->user_id,
			'session_id' => $this->session_id,
			'token' => $this->token,
			'timestamp' => $this->timestamp,
			'ip' => $this->ip,
			'cached_data' => serialize($this->cached_data),
			'data' => serialize($this->data)
		);
		$row = PersistenceContext::get_querier()->insert(DB_TABLE_SESSIONS, $columns);
	}

	private function create_cookie()
	{
		$cookie = new HTTPCookie(Session::$DATA_COOKIE_NAME, $this->get_serialized_content(), time() + 31536000);
		AppContext::get_response()->set_cookie($cookie);
	}

	private function get_serialized_content()
	{
		return serialize(array(self::$KEY_USER_ID => $this->user_id, self::$KEY_SESSION_ID => $this->session_id));
	}

	public function no_session_location()
	{
		$this->no_session_location = true;
	}

	/**
	 * @desc Check the session against CSRF attacks by POST. Checks that POSTs are done from
	 * this site. 2 different cases are accepted but the first is safer:
	 * <ul>
	 *      <li>The request contains a parameter whose name is token and value is the value of the token of the current session.</li>
	 *      <li>If the token isn't in the request, we analyse the HTTP referer to be sure that the request comes from the current site and not from another which can be suspect</li>
	 * </ul>
	 * If the request doesn't match any of these two cases, this method will consider that it's a CSRF attack.
	 */
	public function csrf_post_protect()
	{
		if (!empty($_POST))
			$this->check_csrf_attack();
	}

	/**
	 * @desc Check the session against CSRF attacks by GET. Checks that GETs are done from
	 * this site with a correct token.
	 */
	public function csrf_get_protect()
	{
		$this->check_csrf_attack();
	}

	private function check_csrf_attack()
	{
		if (AppContext::get_request()->get_value('token') !== $this->get_token())
		{
			DispatchManager::redirect(PHPBoostErrors::CSRF());
		}
	}
}
?>