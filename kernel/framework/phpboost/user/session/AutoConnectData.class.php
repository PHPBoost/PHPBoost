<?php
/*##################################################
 *                           AutoConnectData.class.php
 *                            -------------------
 *   begin                : November 05, 2010
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
		$autoconnect = @unserialize($cookie_content);
		if ($autoconnect === false || empty($autoconnect['user_id']) || empty($autoconnect['key']))
		{
			throw new UnexpectedValueException('invalid autoconnect cookie content: "' . $cookie_content . '"');
		}
		$data = new AutoConnectData($autoconnect['user_id'], $autoconnect['key']);
		if ($data->is_valid())
		{
			return $user_id;
		}
		return Session::VISITOR_SESSION_ID;
	}

	public static function create_cookie($user_id)
	{
		$columns = array('autoconnect_key');
		$condition = 'WHERE user_id=:user_id';
		$parameters = array('user_id' => $user_id);
		$row = self::$querier->select_single_row(DB_TABLE_MEMBER, $columns, $condition, $parameters);
		$data = null;
		if (!empty($row['autoconnect_key']))
		{
			$data = new AutoConnectData($autoconnect['user_id'], $row['autoconnect_key']);
		}
		else
		{
			$data = self::change_key($user_id);
		}
		$data->save();
	}

	public static function change_key($user_id)
	{
		$data = new AutoConnectData($autoconnect['user_id'], Random::hexa64uid());
		$data->save_in_db();
		return $data;
	}

	private $user_id;
	private $key;

	private function __construct($user_id, $key)
	{
		$this->user_id= $user_id;
		$this->key= $key;
	}

	public function get_user_id()
	{
		return $this->user_id;
	}

	public static function is_valid()
	{
		$condition = 'WHERE user_id=:user_id AND autoconnect_key=:key';
		$parameters = array('user_id' => $this->user_id, 'key' => $this->key);
		return self::$querier->row_exists(DB_TABLE_MEMBER, $condition, $parameters);
	}

	public function save()
	{
		$this->save_in_db();
		$this->create_cookie();
	}

	private function save_in_db()
	{
		$condition = 'WHERE user_id=:user_id';
		$parameters = array('user_id' => $user_id);
		$columns = array('autoconnect_key' => $key);
		self::$querier->update(DB_TABLE_MEMBER, $columns, $condition, $parameters);
	}

	private function save_in_cookie()
	{
		$expiry = time() + SessionsConfig::load()->get_autoconnect_duration();
		$cookie = new HTTPCookie(Session::$AUTOCONNECT_COOKIE_NAME);
		$cookie->set_expiry_date($expiry);
		$cookie->set_value($this->get_serialized_content());
		AppContext::get_response()->set_cookie($cookie);
	}

	private function get_serialized_content()
	{
		return serialize(array('user_id' => $this->user_id, 'key' => $this->key));
	}
}

?>
