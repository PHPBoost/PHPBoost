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
	const SEASURF_ATTACK_ERROR_PAGE = '/member/csrf-attack.php';

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
		PersistenceContext::get_querier()->delete(DB_TABLE_SESSIONS, 'WHERE expiry < :now', array('now' => time()));
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
			$data = new SessionData($user_id, Random::hexa64uid());
			$data->token = Random::hexa64uid(16);
			$data->expiry = time() + SessionsConfig::load()->get_session_duration();
			$data->ip = AppContext::get_request()->get_ip_address();
			self::fill_user_cached_data($data);
			$data->create();
		}
		return $data;
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
		$parameters = array('user_id' => $user_id);
		$condition = 'WHERE user_id=:user_id';
		self::update_existing_session($condition, $parameters);
		$columns = array('session_id', 'token', 'expiry', 'ip', 'data', 'cached_data');
		$row = PersistenceContext::get_querier()->select_single_row(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
		$data = self::init_from_row($user_id, $row['session_id'], $row);
		$data->create_cookie();
		return $data;
	}

	private static function update_existing_session($condition, $parameters)
	{
		$columns = array(
			'expiry' => time() + SessionsConfig::load()->get_session_duration(),
			'ip' => AppContext::get_request()->get_ip_address()
		);
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
			$columns = array('token', 'expiry', 'ip', 'data', 'cached_data');
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
		$data->cached_data = unserialize($row['cached_data']);
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

	private static function fill_user_cached_data(SessionData $data)
	{
		$columns = array('display_name', 'level', 'email', 'show_email', 'locale', 'theme', 'timezone', 'editor',
			'unread_pm', 'registration_date', 'last_connection_date', 'groups', 'warning_percentage', 'is_banned', 'is_readonly');
		$condition = 'WHERE user_id=:user_id';
		$parameters = array('user_id' => $data->user_id);
		try
		{
			$data->cached_data = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, $columns, $condition, $parameters);
		}
		catch (RowNotFoundException $ex)
		{
			$config = UserAccountsConfig::load();
			$data->cached_data = array(
				'display_name' => LangLoader::get_message('guest', 'main'),
				'level' => -1,
				'email' => null,
				'show_mail' => 0,
				'locale' => $config->get_default_lang(),
				'theme' => $config->get_default_theme(),
				'timezone' => GeneralConfig::load()->get_site_timezone(),
				'editor' => 'bbcode',
				'unread_pm' => 0,
				'registration_date' => 0,
				'last_connection_date' => time(),
				'groups' => '',
				'warning_percentage' => 0,
				'is_banned' => 0,
				'is_readonly' => 0
			);
		}
	}

	protected $user_id;
	protected $session_id;
	protected $token;
	protected $expiry;
	protected $ip;
	protected $cached_data = array();
	protected $data = array();

	protected $cached_data_modified = false;
	protected $data_modified = false;

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

	public function save()
	{
		if ($this->cached_data_modified || $this->data_modified)
		{
			$columns = array();
			if ($this->cached_data_modified)
			{
				$columns['cached_data'] = $this->cached_data;
			}
			if ($this->data_modified)
			{
				$columns['data'] = $this->data;
			}
			$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
			$parameters = array('user_id' => $data->user_id, 'session_id' => $data->session_id);
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
			'expiry' => $this->expiry,
			'ip' => $this->ip,
			'cached_data' => serialize($this->cached_data),
			'data' => serialize($this->data)
		);
		$row = PersistenceContext::get_querier()->insert(DB_TABLE_SESSIONS, $columns);
	}

	private function create_cookie()
	{
		$cookie = new HTTPCookie(Session::$DATA_COOKIE_NAME);
		$cookie->set_expiry_date(time() + 31536000);
		$cookie->set_value($this->get_serialized_content());
		AppContext::get_response()->set_cookie($cookie);
	}

	private function get_serialized_content()
	{
		return serialize(array(self::$KEY_USER_ID => $this->user_id, self::$KEY_SESSION_ID => $this->session_id));
	}

	/**
	 * @desc Check the session against CSRF attacks by POST. Checks that POSTs are done from
	 * this site. 2 different cases are accepted but the first is safer:
	 * <ul>
	 *      <li>The request contains a parameter whose name is token and value is the value of the token of the current session.</li>
	 *      <li>If the token isn't in the request, we analyse the HTTP referer to be sure that the request comes from the current site and not from another which can be suspect</li>
	 * </ul>
	 * If the request doesn't match any of these two cases, this method will consider that it's a CSRF attack.
	 * @param mixed $redirect if string, redirect to the $redirect error page if the token is wrong
	 * if false, do not redirect
	 * @return bool true if no csrf attack by post is detected
	 */
	public function csrf_post_protect($redirect = self::SEASURF_ATTACK_ERROR_PAGE)
	{
		//The user sent a POST request
		if (!empty($_POST))
		{
			//First verification: does the token exist?
			$token = $this->get_token();
			if (!empty($token) && $this->get_request_token() === $token)
			{
				return true;
			}
			//Second chance: the referer is correct
			if (self::check_referer())
			{
				return true;
			}
			//If those two lines are executed, none of the two cases has been matched. Thow it's a potential attack.
			$this->csrf_attack($redirect);
			return false;
		}
		//It's not a POST request, there is no problem.
		else
		{
			return true;
		}
	}

	/**
	 * @desc Check the session against CSRF attacks by GET. Checks that GETs are done from
	 * this site with a correct token.
	 * @param mixed $redirect if string, redirect to the $redirect error page if the token is wrong
	 * if false, do not redirect
	 * @return true if no csrf attack by get is detected
	 */
	public function csrf_get_protect($redirect = self::SEASURF_ATTACK_ERROR_PAGE)
	{
		$token = $this->get_token();
		if (empty($token) || $this->get_request_token() !== $token)
		{
			$this->csrf_attack($redirect);
			return false;
		}
		return true;
	}

	/**
	 * @desc check that the operation is done from this site
	 * @return true if the referer is on this site
	 */
	private static function check_referer()
	{
		if (empty($_SERVER['HTTP_REFERER']))
		{
			return false;
		}
		$general_config = GeneralConfig::load();
		return strpos($_SERVER['HTTP_REFERER'], trim($general_config->get_site_url() . $general_config->get_site_path(), '/')) === 0;
	}

	/**
	 * @desc Redirect to the $redirect error page if the token is wrong
	 * if false, do not redirect
	 * @param mixed $redirect if string, redirect to the $redirect error page if the token is wrong
	 * if false, do not redirect
	 */
	private function csrf_attack($redirect = self::SEASURF_ATTACK_ERROR_PAGE)
	{
		$bad_token = $this->get_printable_token($this->get_request_token());
		$good_token = $this->get_printable_token($this->get_token());

		if ($redirect !== false && !empty($redirect))
		{
			AppContext::get_response()->redirect(Url::to_absolute($redirect));
		}
	}

	private function get_request_token()
	{
		return AppContext::get_request()->get_value('token');
	}
}

?>
