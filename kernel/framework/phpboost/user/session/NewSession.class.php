<?php
/*##################################################
 *                            NewSession.class.php
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
 *
 * Session::gc();
 * Session::start();
 *
 * @package {@package}
 */
class NewSession
{
	const VISITOR_SESSION_ID = -1;

	public static $DATA_COOKIE_NAME;
	public static $AUTOCONNECT_COOKIE_NAME;

	/**
	 * @var SessionData
	 */
	private static $data;

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
		self::$DATA_COOKIE_NAME = $config->get_cookie_name() . '_autoconnect';
		self::$request = AppContext::get_request();
		self::$response = AppContext::get_response();
	}

	public static function gc()
	{
		SessionData::gc();
	}

	public static function start()
	{
		self::load();
		return self::$data;
	}

	public static function create($user_id, $autoconnect = false)
	{
		return self::$data;
	}

	/**
	 * @desc Delete the session in database. The current session stays alive for the rest of the
	 * request and a visitor session will be created at the next request.
	 */
	public static function delete()
	{
		self::$data->delete_cookies_and_db();
	}

	private static function load()
	{
		try
		{
			if (self::$request->has_cookieparameter(self::$DATA_COOKIE_NAME))
			{
				self::connect();
			}
			elseif (self::$request->has_cookieparameter(self::$AUTOCONNECT_COOKIE_NAME))
			{
				self::autoconnect();
			}
			else
			{
				SessionData::create_visitor();
			}
		}
		catch (UnexpectedValueException $ex)
		{
			SessionData::create_visitor();
		}
	}

	private static function connect()
	{
		try
		{
			self::$data = SessionData::from_cookie(self::$request->get_cookie(self::$DATA_COOKIE_NAME));
		}
		catch (SessionNotFoundException $ex)
		{
			self::autoconnect();
		}
	}

	private static function autoconnect()
	{
		if (self::$request->has_cookieparameter(self::$AUTOCONNECT_COOKIE_NAME))
		{
			$user_id = AutoConnectData::from_cookie(self::$request->get_cookie(self::$AUTOCONNECT_COOKIE_NAME));
			self::$data = SessionData::create_from_user_id($user_id);
		}
		else
		{
			self::$data = SessionData::create_visitor();
		}
	}

	private static function delete_cookies_and_db()
	{
		self::$data->delete();
		self::$response->delete_cookie(self::$AUTOCONNECT_COOKIE_NAME);
	}
}

?>
