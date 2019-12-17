<?php
/**
 * This class manages all sessions for the users.
 * Session::gc();
 * Session::start();
 * @package     PHPBoost
 * @subpackage  User\session
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 11 04
*/

class Session
{
	const VISITOR_SESSION_ID = -1;

	public static $DATA_COOKIE_NAME;
	public static $AUTOCONNECT_COOKIE_NAME;

	/**
	 * @var HTTPRequest
	 */
	private static $request;
	/**
	 * @var HTTPResponse
	 */
	private static $response;

	public static function __static()
	{
		$config = SessionsConfig::load();
		self::$DATA_COOKIE_NAME = $config->get_cookie_name() . '_data';
		self::$AUTOCONNECT_COOKIE_NAME = $config->get_cookie_name() . '_autoconnect';
		self::$request = AppContext::get_request();
		self::$response = AppContext::get_response();
	}

	public static function gc()
	{
		SessionData::gc();
	}

	public static function start()
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

	public static function create($user_id, $autoconnect = false)
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
	public static function delete(SessionData $session)
	{
		$session->delete();
		self::$response->delete_cookie(self::$AUTOCONNECT_COOKIE_NAME);
	}

	private static function connect()
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

	private static function autoconnect()
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

	private static function create_visitor()
	{
		return SessionData::create_visitor();
	}
}
?>
