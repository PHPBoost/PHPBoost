<?php
/**
 * This class manages all sessions for the users.
 * @package     PHPBoost
 * @subpackage  User\session
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 3.0 - 2010 11 05
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AutoConnectData
{
	private static DBQuerier $querier;

	public static function __static(): void
	{
		self::$querier = PersistenceContext::get_querier();
	}

	public static function get_user_id_from_cookie(string $cookie_content): mixed
	{
		$autoconnect = false;
		if (preg_match('/user_id/', $cookie_content) && preg_match('/key/', $cookie_content) && !preg_match('/O:/', $cookie_content))
		{
			$autoconnect = TextHelper::unserialize($cookie_content);
		}

		if ($autoconnect === false || empty($autoconnect['user_id']) || empty($autoconnect['key']))
		{
			throw new UnexpectedValueException('invalid autoconnect cookie content: "' . $cookie_content . '"');
		}
		$data = new AutoConnectData($autoconnect['user_id'], $autoconnect['key']);
		if ($data->is_valid())
		{
			return $autoconnect['user_id'];
		}
		return Session::VISITOR_SESSION_ID;
	}

	public static function create_cookie(int $user_id): void
	{
		$data = $row = null;
		$columns = ['autoconnect_key'];
		$condition = 'WHERE user_id=:user_id';
		$parameters = ['user_id' => $user_id];

		try
        {
			$row = self::$querier->select_single_row(DB_TABLE_MEMBER, $columns, $condition, $parameters);
		}
        catch (RowNotFoundException $e) { }

		if (!empty($row) && !empty($row['autoconnect_key']))
		{
			$data = new AutoConnectData($user_id, $row['autoconnect_key']);
		}
		else
		{
			$data = self::change_key($user_id);
		}
		$data->save();
	}

	public static function change_key(int $user_id): AutoConnectData
	{
		$data = new AutoConnectData($user_id, KeyGenerator::generate_key(64));
		$data->save_in_db();
		return $data;
	}

	private int $user_id;
	private mixed $key;

	private function __construct(int $user_id, mixed $key)
	{
		$this->user_id = $user_id;
		$this->key = $key;
	}

	public function get_user_id(): int
	{
		return $this->user_id;
	}

	public function is_valid(): bool
	{
		$condition = 'WHERE user_id=:user_id AND autoconnect_key=:key AND delay_banned < :now AND warning_percentage < 100';
		$parameters = ['user_id' => $this->user_id, 'key' => $this->key, 'now' => time()];
		return self::$querier->row_exists(DB_TABLE_MEMBER, $condition, $parameters);
	}

	public function save(): void
	{
		$this->save_in_db();
		$this->save_in_cookie();
	}

	private function save_in_db(): void
	{
		$condition = 'WHERE user_id=:user_id';
		$parameters = ['user_id' => $this->user_id];
		$columns = ['autoconnect_key' => $this->key];
		self::$querier->update(DB_TABLE_MEMBER, $columns, $condition, $parameters);
	}

	private function save_in_cookie(): void
	{
		$expiry = time() + SessionsConfig::load()->get_autoconnect_duration();
		$cookie = new HTTPCookie(Session::$AUTOCONNECT_COOKIE_NAME, $this->get_serialized_content(), $expiry);
		AppContext::get_response()->set_cookie($cookie);
	}

	private function get_serialized_content(): string
	{
		return TextHelper::serialize(['user_id' => $this->user_id, 'key' => $this->key]);
	}
}
?>
