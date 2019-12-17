<?php
/**
 * This class manages all sessions for the users.
 * @package     PHPBoost
 * @subpackage  User\session
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 30
 * @since       PHPBoost 3.0 - 2010 11 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AutoConnectData
{
	/**
	 * @var DBQuerier
	 */
	private static $querier;

	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}

	public static function get_user_id_from_cookie($cookie_content)
	{
		$autoconnect = TextHelper::unserialize($cookie_content);
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

	public static function create_cookie($user_id)
	{
		$data = $row = null;
		$columns = array('autoconnect_key');
		$condition = 'WHERE user_id=:user_id';
		$parameters = array('user_id' => $user_id);

		try {
			$row = self::$querier->select_single_row(DB_TABLE_MEMBER, $columns, $condition, $parameters);
		} catch (RowNotFoundException $e) { }

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

	public static function change_key($user_id)
	{
		$data = new AutoConnectData($user_id, KeyGenerator::generate_key(64));
		$data->save_in_db();
		return $data;
	}

	private $user_id;
	private $key;

	private function __construct($user_id, $key)
	{
		$this->user_id = $user_id;
		$this->key = $key;
	}

	public function get_user_id()
	{
		return $this->user_id;
	}

	public function is_valid()
	{
		$condition = 'WHERE user_id=:user_id AND autoconnect_key=:key';
		$parameters = array('user_id' => $this->user_id, 'key' => $this->key);
		return self::$querier->row_exists(DB_TABLE_MEMBER, $condition, $parameters);
	}

	public function save()
	{
		$this->save_in_db();
		$this->save_in_cookie();
	}

	private function save_in_db()
	{
		$condition = 'WHERE user_id=:user_id';
		$parameters = array('user_id' => $this->user_id);
		$columns = array('autoconnect_key' => $this->key);
		self::$querier->update(DB_TABLE_MEMBER, $columns, $condition, $parameters);
	}

	private function save_in_cookie()
	{
		$expiry = time() + SessionsConfig::load()->get_autoconnect_duration();
		$cookie = new HTTPCookie(Session::$AUTOCONNECT_COOKIE_NAME, $this->get_serialized_content(), $expiry);
		AppContext::get_response()->set_cookie($cookie);
	}

	private function get_serialized_content()
	{
		return TextHelper::serialize(array('user_id' => $this->user_id, 'key' => $this->key));
	}
}
?>
