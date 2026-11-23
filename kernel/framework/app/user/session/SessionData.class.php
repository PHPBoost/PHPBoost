<?php
/**
 * This class manages all sessions for the users.
 * @package     PHPBoost
 * @subpackage  User\session
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 3.0 - 2010 11 04
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      Myster <https://www.phpboost.com/user/pm-3023>
*/

class SessionData
{
	const DEFAULT_VISITOR_DISPLAY_NAME = 'visitor';

	private static $KEY_USER_ID = 'user_id';
	private static $KEY_SESSION_ID = 'session_id';

	protected int $user_id;
	protected string $session_id = '';
	protected string $token = '';
	protected mixed $expiry = null;
	protected mixed $timestamp = null;
	protected string $ip = '';
	protected string $location_script = '';
	protected string $location_title = '';
	protected mixed $location_id = null;
	protected array $cached_data = [];
	protected array $data = [];

	protected bool $cached_data_modified = false;
	protected bool $data_modified = false;

	protected function __construct(int $user_id, string $session_id)
	{
		$this->user_id = $user_id;
		$this->session_id = $session_id;
	}

	public function get_user_id(): int
	{
		return $this->user_id;
	}

	public function get_session_id(): string
	{
		return $this->session_id;
	}

	public function get_token(): string
	{
		return $this->token;
	}

	public function get_expiry_date(): int
	{
		return $this->timestamp + SessionsConfig::load()->get_session_duration();
	}

	public function get_timestamp(): int
	{
		return $this->timestamp;
	}

	public function get_ip(): string
	{
		return $this->ip;
	}

	public function get_location_script(): string
	{
		return $this->location_script;
	}

	public function get_location_title(): string
	{
		return $this->location_title;
	}

	public function get_location_id(): mixed
	{
		return $this->location_id;
	}

	public function get_all_cached_data(): mixed
	{
		return $this->cached_data;
	}

	public function has_cached_data(mixed $key): bool
	{
		return isset($this->cached_data[$key]);
	}

	public function get_cached_data(mixed $key, mixed $default = null): mixed
	{
		if (is_array($this->cached_data) && array_key_exists($key, $this->cached_data))
		{
			return $this->cached_data[$key];
		}
		return $default;
	}

	public function add_cached_data(mixed $key, mixed $value): void
	{
		$this->cached_data_modified = true;
		$this->cached_data[$key] = $value;
	}

	public function remove_cached_data(mixed $key): void
	{
		$this->cached_data_modified = true;
		unset($this->cached_data[$key]);
	}

	public function get_all_data(): array
	{
		return $this->data;
	}

	public function has_data(mixed $key): bool
	{
		return isset($this->data[$key]);
	}

	public function get_data(mixed $key, mixed $default = null): mixed
	{
		if (array_key_exists($key, $this->data))
		{
			return $this->data[$key];
		}
		return $default;
	}

	public function add_data(mixed $key, mixed $value): void
	{
		$this->data_modified = true;
		$this->data[$key] = $value;
	}

	public function remove_data(mixed $key): void
	{
		$this->data_modified = true;
		unset($this->data[$key]);
	}

	public function recheck_cached_data(): void
	{
		self::fill_user_cached_data($this);
		$this->cached_data_modified = true;
		$this->save();
	}

	public function save(): void
	{
		if ($this->cached_data_modified || $this->data_modified)
		{
			$columns = [];
			if ($this->cached_data_modified)
			{
				$columns['cached_data'] = TextHelper::serialize($this->cached_data);
			}
			if ($this->data_modified)
			{
				$columns['data'] = TextHelper::serialize($this->data);
			}
			$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
			$parameters = ['user_id' => $this->user_id, 'session_id' => $this->session_id];
			PersistenceContext::get_querier()->update(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
			$this->cached_data_modified = false;
			$this->data_modified = false;
		}
	}

	private function create(): void
	{
		$this->create_in_db();
		$this->create_cookie();
	}

	public function delete(): void
	{
		$this->delete_in_db();
		$this->delete_cookie();
	}

	private function delete_in_db(): void
	{
		$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
		$parameters = ['user_id' => $this->user_id, 'session_id' => $this->session_id];
		PersistenceContext::get_querier()->delete(DB_TABLE_SESSIONS, $condition, $parameters);
	}

	private function delete_cookie(): void
	{
		AppContext::get_response()->delete_cookie(Session::$DATA_COOKIE_NAME);
	}

	private function create_in_db(): void
	{
		$columns = [
			'user_id' => $this->user_id,
			'session_id' => $this->session_id,
			'token' => $this->token,
			'timestamp' => $this->timestamp,
			'ip' => $this->ip,
			'cached_data' => TextHelper::serialize($this->cached_data),
			'data' => TextHelper::serialize($this->data)
		];
		$row = PersistenceContext::get_querier()->insert(DB_TABLE_SESSIONS, $columns);
	}

	private function create_cookie(): void
	{
		$cookie = new HTTPCookie(Session::$DATA_COOKIE_NAME, $this->get_serialized_content(), time() + 31536000);
		AppContext::get_response()->set_cookie($cookie);
	}

	private function get_serialized_content(): string
	{
		return TextHelper::serialize([self::$KEY_USER_ID => $this->user_id, self::$KEY_SESSION_ID => $this->session_id]);
	}

	public function no_session_location(): void
	{
		$data = AppContext::get_session();

		$columns = ['timestamp' => $data->timestamp];
		$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
		$parameters = ['user_id' => $data->user_id, 'session_id' => $data->session_id];
		PersistenceContext::get_querier()->update(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
	}

	public function location_id_already_exists(mixed $location_id): bool
	{
		$condition = 'WHERE location_id=:location_id AND user_id!=:user_id';
		$parameters = ['location_id' => $location_id, 'user_id' => $this->user_id];
		$value = PersistenceContext::get_querier()->count(DB_TABLE_SESSIONS, $condition, $parameters);

		return $value > 0;
	}

	public function get_user_on_location_id(mixed $location_id): int
	{
		try
        {
			$user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_SESSIONS, 'user_id', 'WHERE location_id=:location_id', ['location_id' => $location_id]);
		}
        catch (RowNotFoundException $e)
        {
			$user_id = User::VISITOR_LEVEL;
		}

		return $user_id;
	}

	public static function admin_session(): SessionData
	{
		return new SessionData(1, '');
	}

	public static function gc(): void
	{
		PersistenceContext::get_querier()->delete(DB_TABLE_SESSIONS, 'WHERE timestamp < :now', ['now' => time() - SessionsConfig::load()->get_session_duration()]);
	}

	public static function create_visitor(): SessionData
	{
		return self::create_from_user_id(Session::VISITOR_SESSION_ID);
	}

	public static function create_from_user_id(int $user_id): SessionData
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
		}
		return $data;
	}

	public static function add_in_visit_counter(): void
	{
		$ip_address = AppContext::get_request()->get_ip_address();
		$has_already_visited = PersistenceContext::get_querier()->row_exists(DB_TABLE_VISIT_COUNTER, 'WHERE ip=:ip', ['ip' => $ip_address]);
		$is_robot = Robots::is_robot();

		if (!$has_already_visited && !$is_robot)
		{
			$now = new Date(Date::DATE_NOW, Timezone::SITE_TIMEZONE);
			$time = $now->format('Y-m-d', Timezone::SITE_TIMEZONE);

			PersistenceContext::get_querier()->inject("UPDATE " . DB_TABLE_VISIT_COUNTER . " SET ip = ip + 1, time=:time, total = total + 1 WHERE id = 1", ['time' => $time]);
			PersistenceContext::get_querier()->insert(DB_TABLE_VISIT_COUNTER, ['ip' => $ip_address, 'time' => $time, 'total' => 0]);
		}

		foreach (AppContext::get_extension_provider_service()->get_extension_point(ScheduledJobExtensionPoint::EXTENSION_POINT) as $module_id => $job)
		{
			if ($job)
            {
				$job->on_new_session(!$has_already_visited, $is_robot);
            }
		}
	}

	public static function update_location(string $title_page, string $location_id = ''): mixed
	{
		$data = AppContext::get_session();

		$columns = ['timestamp' => $data->timestamp, 'location_title' => $title_page, 'location_script' => TextHelper::cut_string(REWRITED_SCRIPT, 200), 'location_id' => $location_id];
		$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
		$parameters = ['user_id' => $data->user_id, 'session_id' => $data->session_id];
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

	public static function recheck_cached_data_from_user_id(int $user_id): void
	{
		if ($user_id != Session::VISITOR_SESSION_ID && self::session_exists($user_id))
		{
			$data = self::get_existing_session($user_id);
			$data->recheck_cached_data();
		}
	}

	private static function session_exists(int $user_id): bool
	{
		$condition = 'WHERE user_id=:user_id';
		$parameters = ['user_id' => $user_id];
		return PersistenceContext::get_querier()->row_exists(DB_TABLE_SESSIONS, $condition, $parameters);
	}

	private static function use_existing_session(int $user_id): SessionData
	{
		self::update_existing_session($user_id);
		$data = self::get_existing_session($user_id);
		$data->create_cookie();
		return $data;
	}

	private static function get_existing_session(int $user_id): mixed
	{
		$parameters = ['user_id' => $user_id];
		$condition = 'WHERE user_id=:user_id';
		$columns = ['session_id', 'token', 'timestamp', 'ip', 'location_script', 'location_title', 'location_id', 'data', 'cached_data'];

		try
        {
			$row = PersistenceContext::get_querier()->select_single_row(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
		}
        catch (NotASingleRowFoundException $e)
        {
			$row = PersistenceContext::get_querier()->select_single_row_query('
                SELECT *
                FROM ' . DB_TABLE_SESSIONS . '
                WHERE user_id=:user_id
                ORDER BY timestamp DESC
                LIMIT 1', $parameters
            );

			PersistenceContext::get_querier()->delete(DB_TABLE_SESSIONS, 'WHERE user_id=:user_id AND session_id != :session_id', ['user_id' => $user_id, 'session_id' => $row['session_id']]);
		}
		return self::init_from_row($user_id, $row['session_id'], $row);
	}

	private static function update_existing_session(int $user_id): void
	{
		$columns = [
			'timestamp' => time() + SessionsConfig::load()->get_session_duration(),
			'ip' => AppContext::get_request()->get_ip_address()
		];
		$parameters = ['user_id' => $user_id];
		$condition = 'WHERE user_id=:user_id';
		PersistenceContext::get_querier()->update(DB_TABLE_SESSIONS, $columns, $condition, $parameters);
	}

	public static function from_cookie(string $cookie_content): SessionData
	{
		$values = false;
		if (preg_match('/user_id/', $cookie_content) && preg_match('/session_id/', $cookie_content) && !preg_match('/O:/', $cookie_content))
		{
			$values = TextHelper::unserialize($cookie_content);
		}

		if ($values === false || empty($values[self::$KEY_USER_ID]) || empty($values[self::$KEY_SESSION_ID]))
		{
			throw new UnexpectedValueException('invalid session data cookie content: "' . $cookie_content . '"');
		}
		try
		{
			$user_id = $values[self::$KEY_USER_ID];
			$session_id = $values[self::$KEY_SESSION_ID];
			$columns = ['token', 'timestamp', 'ip', 'location_script', 'location_title', 'location_id', 'data', 'cached_data'];
			$condition = 'WHERE user_id=:user_id AND session_id=:session_id';
			$parameters = ['user_id' => $user_id, 'session_id' => $session_id];
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

    private static function init_from_row(int $user_id, string $session_id, array $row): SessionData
    {
		// Correction function to be applied directly here
        $fix_serialized = function($serialized_string) {
            return preg_replace_callback(
                '/s:(\d+):"(.*?)";/s',
                function($matches) {
                    if (isset($matches[2])) {
                        // Use strlen to get size in bytes
                        return 's:' . strlen($matches[2]) . ':"' . $matches[2] . '";';
                    }
                    return $matches[0];
                },
                $serialized_string
            );
        };

        // Correct serialized data before deserialization
        $fixed_cached_data = $fix_serialized($row['cached_data']);
        $fixed_data = $fix_serialized($row['data']);

        $data = new SessionData($user_id, $session_id);
        $data->token = $row['token'];
        $data->timestamp = $row['timestamp'];
        $data->ip = $row['ip'];
        $data->location_script = $row['location_script'];
        $data->location_title = $row['location_title'];
        $data->location_id = $row['location_id'];

        // Use unserialize directly after correction
        $data->cached_data = unserialize($fixed_cached_data);
        if ($data->cached_data  ===  false) {
            // Fallback in case of failure
            $data->cached_data = [];
        }

        $data->data = unserialize($fixed_data);
        if ($data->data  ===  false) {
            // Fallback in case of failure
            $data->data = [];
        }

        return $data;
    }

	private static function fill_user_cached_data(SessionData $data)
	{
		try
		{
			$data->cached_data = PersistenceContext::get_querier()->select_single_row_query('
                SELECT member.user_id AS m_user_id, member.display_name, member.level, member.email, member.show_email, member.locale, member.theme, member.timezone, member.editor, member.unread_pm, member.posted_msg, member.registration_date, member.last_connection_date, member.user_groups, member.warning_percentage, member.delay_banned, member.delay_readonly, member_extended_fields.*
                FROM ' . DB_TABLE_MEMBER . ' member
                LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' member_extended_fields ON member_extended_fields.user_id = member.user_id
                WHERE member.user_id = :user_id'
                , [
                    'user_id' => $data->user_id
                ]
            );
		}
		catch (RowNotFoundException $ex)
		{
			$current_robot_name = Robots::get_current_robot_name();
			$data->cached_data = !empty($current_robot_name) ? User::get_visitor_properties($current_robot_name, User::ROBOT_LEVEL) : User::get_visitor_properties(self::DEFAULT_VISITOR_DISPLAY_NAME);
		}
	}

	protected function update_user_info(int $user_id): void
	{
		PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, ['last_connection_date' => time()], 'WHERE user_id=:user_id', ['user_id' => $user_id]);
	}

	/**
	 * Check the session against CSRF attacks by POST. Checks that POSTs are done with the token of the current session.
	 * If the token of the request doesn't match the token of the current session, this method will consider that it's a CSRF attack.
	 */
	public function csrf_post_protect(): void
	{
		if (AppContext::get_request()->is_post_method())
        {
			$this->check_csrf_attack();
        }
	}

	/**
	 * Check the session against CSRF attacks by GET. Checks that GETs are done with the token of the current session.
	 * If the token of the request doesn't match the token of the current session, this method will consider that it's a CSRF attack.
	 */
	public function csrf_get_protect(): void
	{
		$this->check_csrf_attack();
	}

	private function check_csrf_attack(): void
	{
		$request = AppContext::get_request();
		if (!$request->has_parameter('token') || $request->get_value('token') !== $this->get_token())
		{
			DispatchManager::redirect(PHPBoostErrors::CSRF());
		}
	}
}
?>
