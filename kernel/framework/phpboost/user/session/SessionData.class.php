<?php
/**
 * This class manages all sessions for the users.
 * @package     PHPBoost
 * @subpackage  User\session
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 02 28
 * @since       PHPBoost 3.0 - 2010 11 04
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class SessionData
{
	const DEFAULT_VISITOR_DISPLAY_NAME = 'visitor';

	private static $KEY_USER_ID = 'user_id';
	private static $KEY_SESSION_ID = 'session_id';

	protected $user_id;
	protected $session_id;
	protected $token;
	protected $timestamp;
	protected $ip;
	protected $location_script;
	protected $location_title;
	protected $location_id;
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

	public function get_location_id()
	{
		return $this->location_id;
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
		if (is_array($this->cached_data) && array_key_exists($key, $this->cached_data))
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
		if (array_key_exists($key, $this->data))
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
				$columns['cached_data'] = TextHelper::serialize($this->cached_data);
			}
			if ($this->data_modified)
			{
				$columns['data'] = TextHelper::serialize($this->data);
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
			'cached_data' => TextHelper::serialize($this->cached_data),
			'data' => TextHelper::serialize($this->data)
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
		return TextHelper::serialize(array(self::$KEY_USER_ID => $this->user_id, self::$KEY_SESSION_ID => $this->session_id));
	}

	public function no_session_location()
	{
		$data = AppContext::get_session();

		$columns = array('timestamp' => $data->timestamp);
		$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
		$parameters = array('user_id' => $data->user_id, 'session_id' => $data->session_id);
		PersistenceContext::get_querier()->update(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
	}

	public function location_id_already_exists($location_id)
	{
		$condition = 'WHERE location_id=:location_id AND user_id!=:user_id';
		$parameters = array('location_id' => $location_id, 'user_id' => $this->user_id);
		$value = PersistenceContext::get_querier()->count(DB_TABLE_SESSIONS, $condition, $parameters);

		return $value > 0;
	}

	public function get_user_on_location_id($location_id)
	{
		try {
			$user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_SESSIONS, 'user_id', 'WHERE location_id=:location_id', array('location_id' => $location_id));
		} catch (RowNotFoundException $e) {
			$user_id = User::VISITOR_LEVEL;
		}

		return $user_id;
	}

	public static function admin_session()
	{
		return new SessionData(1, null);
	}

	/**
	 *
	 */
	public static function gc()
	{
		PersistenceContext::get_querier()->delete(DB_TABLE_SESSIONS, 'WHERE timestamp < :now', array('now' => time() - SessionsConfig::load()->get_session_duration()));
	}

	/**
	 *
	 * @return SessionData
	 */
	public static function create_visitor()
	{
		return self::create_from_user_id(Session::VISITOR_SESSION_ID);
	}

	/**
	 *
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
			$data = new self($user_id, KeyGenerator::generate_key(64));
			$data->token = KeyGenerator::generate_key(16);
			$data->timestamp = time();
			$data->ip = AppContext::get_request()->get_ip_address();
			self::fill_user_cached_data($data);
			$data->create();

			self::add_in_visit_counter();
		}
		return $data;
	}

	public static function add_in_visit_counter()
	{
		$ip_address = AppContext::get_request()->get_ip_address();
		$has_already_visited = PersistenceContext::get_querier()->row_exists(DB_TABLE_VISIT_COUNTER, 'WHERE ip=:ip', array('ip' => $ip_address));
		$is_robot = Robots::is_robot();

		if (!$has_already_visited && !$is_robot)
		{
			$now = new Date(Date::DATE_NOW, Timezone::SITE_TIMEZONE);
			$time = $now->format('Y-m-d', Timezone::SITE_TIMEZONE);

			PersistenceContext::get_querier()->inject("UPDATE " . DB_TABLE_VISIT_COUNTER . " SET ip = ip + 1, time=:time, total = total + 1 WHERE id = 1", array('time' => $time));
			PersistenceContext::get_querier()->insert(DB_TABLE_VISIT_COUNTER, array('ip' => $ip_address, 'time' => $time, 'total' => 0));
		}

		$jobs = AppContext::get_extension_provider_service()->get_extension_point(ScheduledJobExtensionPoint::EXTENSION_POINT);
		foreach ($jobs as $job)
		{
			$job->on_new_session(!$has_already_visited, $is_robot);
		}
	}

	public static function update_location($title_page, $location_id = '')
	{
		$data = AppContext::get_session();

		$columns = array('timestamp' => $data->timestamp, 'location_title' => $title_page, 'location_script' => TextHelper::cut_string(REWRITED_SCRIPT, 200), 'location_id' => $location_id);
		$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
		$parameters = array('user_id' => $data->user_id, 'session_id' => $data->session_id);
		PersistenceContext::get_querier()->update(DB_TABLE_SESSIONS, $columns, $condition, $parameters);

		if (is_array($data->cached_data) && array_key_exists('last_connection_date', $data->cached_data))
		{
			$now = new Date();
			$last_connection_date = new Date($data->cached_data['last_connection_date'], Timezone::SERVER_TIMEZONE);
			if (!($last_connection_date->get_day() == $now->get_day() && $last_connection_date->get_month() == $now->get_month() && $last_connection_date->get_year() == $now->get_year()))
			{
				$data->update_user_info($data->user_id);
				$data->recheck_cached_data_from_user_id($data->user_id);
			}
		}

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
	 *
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
	 *
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
		$columns = array('session_id', 'token', 'timestamp', 'ip', 'location_script', 'location_title', 'location_id', 'data', 'cached_data');

		try {
			$row = PersistenceContext::get_querier()->select_single_row(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
		} catch (NotASingleRowFoundException $e) {
			$row = PersistenceContext::get_querier()->select_single_row_query('SELECT *
			FROM ' . DB_TABLE_SESSIONS . '
			WHERE user_id=:user_id
			ORDER BY timestamp DESC
			LIMIT 1', $parameters);

			PersistenceContext::get_querier()->delete(DB_TABLE_SESSIONS, 'WHERE user_id=:user_id AND session_id != :session_id', array('user_id' => $user_id, 'session_id' => $row['session_id']));
		}
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
	 *
	 * @param string $cookie_content
	 * @return SessionData
	 */
	public static function from_cookie($cookie_content)
	{
		$values = TextHelper::unserialize($cookie_content);
		if ($values === false || empty($values[self::$KEY_USER_ID]) || empty($values[self::$KEY_SESSION_ID]))
		{
			throw new UnexpectedValueException('invalid session data cookie content: "' . $cookie_content . '"');
		}
		try
		{
			$user_id = $values[self::$KEY_USER_ID];
			$session_id = $values[self::$KEY_SESSION_ID];
			$columns = array('token', 'timestamp', 'ip', 'location_script', 'location_title', 'location_id', 'data', 'cached_data');
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
		$fixed_cached_data = preg_replace_callback( '!s:(\d+):"(.*?)";!u', function($match) {
			return ($match[1] == TextHelper::strlen($match[2])) ? $match[0] : 's:' . TextHelper::strlen($match[2]) . ':"' . $match[2] . '";';
		}, $row['cached_data']);

		$data = new SessionData($user_id, $session_id);
		$data->token = $row['token'];
		$data->timestamp = $row['timestamp'];
		$data->ip = $row['ip'];
		$data->location_script = $row['location_script'];
		$data->location_title = $row['location_title'];
		$data->location_id = $row['location_id'];
		$data->cached_data = TextHelper::unserialize($fixed_cached_data);
		$data->data = TextHelper::unserialize($row['data']);
		return $data;
	}

	private static function fill_user_cached_data(SessionData $data)
	{
		try
		{
			$data->cached_data = PersistenceContext::get_querier()->select_single_row_query('SELECT member.user_id AS m_user_id, member.display_name, member.level, member.email, member.show_email, member.locale, member.theme, member.timezone, member.editor, member.unread_pm, member.posted_msg, member.registration_date, member.last_connection_date, member.groups, member.warning_percentage, member.delay_banned, member.delay_readonly, member_extended_fields.*
			FROM ' . DB_TABLE_MEMBER . ' member
			LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' member_extended_fields ON member_extended_fields.user_id = member.user_id
			WHERE member.user_id = :user_id', array(
				'user_id' => $data->user_id
			));
		}
		catch (RowNotFoundException $ex)
		{
			$current_robot_name = Robots::get_current_robot_name();
			$data->cached_data = !empty($current_robot_name) ? User::get_visitor_properties($current_robot_name, User::ROBOT_LEVEL) : User::get_visitor_properties(self::DEFAULT_VISITOR_DISPLAY_NAME);
		}
	}

	protected function update_user_info($user_id)
	{
		PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('last_connection_date' => time()), 'WHERE user_id=:user_id', array('user_id' => $user_id));
	}

	/**
	 * Check the session against CSRF attacks by POST. Checks that POSTs are done with the token of the current session.
	 * If the token of the request doesn't match the token of the current session, this method will consider that it's a CSRF attack.
	 */
	public function csrf_post_protect()
	{
		if (AppContext::get_request()->is_post_method())
			$this->check_csrf_attack();
	}

	/**
	 * Check the session against CSRF attacks by GET. Checks that GETs are done with the token of the current session.
	 * If the token of the request doesn't match the token of the current session, this method will consider that it's a CSRF attack.
	 */
	public function csrf_get_protect()
	{
		$this->check_csrf_attack();
	}

	private function check_csrf_attack()
	{
		$request = AppContext::get_request();
		if (!$request->has_parameter('token') || $request->get_value('token') !== $this->get_token())
		{
			DispatchManager::redirect(PHPBoostErrors::CSRF());
		}
	}
}
?>
