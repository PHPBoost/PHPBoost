<?php
/**
 * This class manages all sessions for the users.
 * Session::gc();
 * Session::start();
 * @package     PHPBoost
 * @subpackage  User\session
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 3.0 - 2010 11 04
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Session
{
	const VISITOR_SESSION_ID = -1;

	public static string $DATA_COOKIE_NAME;
	public static string $AUTOCONNECT_COOKIE_NAME;

	private static ?HTTPRequestCustom $request = null;
	private static ?HTTPResponseCustom $response = null;

	public static function __static(): void
	{
		$config = SessionsConfig::load();
		self::$DATA_COOKIE_NAME = $config->get_cookie_name() . '_data';
		self::$AUTOCONNECT_COOKIE_NAME = $config->get_cookie_name() . '_autoconnect';
		self::$request = AppContext::get_request();
		self::$response = AppContext::get_response();
	}

	public static function gc(): void
	{
		SessionData::gc();
	}

	public static function start(): mixed
	{
		try
		{
			if (self::$request->has_cookieparameter(self::$DATA_COOKIE_NAME))
			{
				return self::connect();
			}
			if (self::$request->has_cookieparameter(self::$AUTOCONNECT_COOKIE_NAME))
			{
				return self::autoconnect();
			}
			return self::create_visitor();
		}
		catch (UnexpectedValueException $ex)
		{
			return self::create_visitor();
		}
	}

	public static function create(int $user_id, bool $autoconnect = false): mixed
	{
		if ($user_id == Session::VISITOR_SESSION_ID)
		{
			return self::create_visitor();
		}
		else
		{
			$data = SessionData::create_from_user_id($user_id);
			if ($autoconnect)
			{
				AutoConnectData::create_cookie($user_id);
			}
			return $data;
		}
	}

	/**
	 * Delete the session in database. The current session stays alive for the rest of the
	 * request and a visitor session will be created at the next request.
	 */
	public static function delete(SessionData $session): void
	{
		$session->delete();
		self::$response->delete_cookie(self::$AUTOCONNECT_COOKIE_NAME);
	}

	private static function connect(): mixed
	{
		try
		{
			return SessionData::from_cookie(self::$request->get_cookie(self::$DATA_COOKIE_NAME));
		}
		catch (SessionNotFoundException $ex)
		{
			if (self::$request->has_cookieparameter(self::$AUTOCONNECT_COOKIE_NAME))
			{
				return self::autoconnect();
			}
			return self::create_visitor();
		}
	}

	private static function autoconnect(): mixed
	{
		$cookie = self::$request->get_cookie(self::$AUTOCONNECT_COOKIE_NAME);
		$user_id = AutoConnectData::get_user_id_from_cookie($cookie);
		if ($user_id != Session::VISITOR_SESSION_ID)
		{
			return SessionData::create_from_user_id($user_id);
		}
		else
		{
			self::$response->delete_cookie(self::$AUTOCONNECT_COOKIE_NAME);
			return self::create_visitor();
		}
	}

	private static function create_visitor(): mixed
	{
		return SessionData::create_visitor();
	}
}
?>
