<?php
/*##################################################
 *                         AdminLoginService.class.php
 *                            -------------------
 *   begin                : December 14 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

class AdminLoginService
{
	private static $admin_uid;
	private static $admin_data;

	public static function try_to_connect()
	{
		$request = AppContext::get_request();
		if (self::is_authentication_form_submitted($request))
		{
			return self::try_connect($request->get_poststring('login'), $request->get_poststring('password'));

		}
		return false;
	}

	private static function is_authentication_form_submitted($request)
	{
		return $request->has_postparameter('connect') && $request->has_postparameter('login') &&
		$request->has_postparameter('password');
	}

	private static function try_connect($login, $password)
	{
		try
		{
			return self::connect($login, $password);
		}
		catch (Exception $exception)
		{
			return false;
		}
	}

	private static function connect($login, $password)
	{
		self::load_admin_data($login);
		if (self::is_admin_allowed())
		{
			if (self::start_session($password))
			{
				self::reset_attempts();
				return true;
			}
			else
			{
				self::log_connection_error();
			}
		}
		else
		{
			self::unlock_admin();
		}
		return false;
	}

	private static function load_admin_data($login)
	{
		self::$admin_data = AppContext::get_sql_common_query()->select_single_row(DB_TABLE_MEMBER,
		array('user_id', 'level', 'user_warning', 'last_connect', 'test_connect', 'user_ban', 'user_aprob'),
				'WHERE login=:login AND level=2', array('login' => $login));
		self::$admin_uid = self::$admin_data['user_id'];
		AppContext::get_request()->set_getvalue('flood', self::$admin_data['test_connect']);
	}

	private static function is_admin_allowed()
	{
		$is_not_banned = (time() - self::$admin_data['user_ban']) >= 0;
		$is_approved = self::$admin_data['user_aprob'] == '1';
		$has_not_maximum_warnings = self::$admin_data['user_warning'] < 100;
		return $is_not_banned && $is_approved && $has_not_maximum_warnings;
	}

	private static function start_session($password)
	{
		$error_report = false;
		$delay_connect = (time() - self::$admin_data['last_connect']);
		
		if (self::$admin_data['test_connect'] < 5)
		{
			$error_report = $Session->start(self::$admin_uid, $password, self::$admin_data['level'], '', '', '', $autoconnexion); //On lance la session.
		}
		elseif ($delay_connect >= 600 && self::$admin_data['test_connect'] == 5) //5 nouveau essais, 10 minutes après.
		{
			self::set_test_connections(0);
			$error_report = $Session->start(self::$admin_uid, $password, self::$admin_data['level'], '', '', '', $autoconnexion); //On lance la session.
		}
		elseif ($delay_connect >= 300 && self::$admin_data['test_connect'] == 5) //2 essais 5 minutes après
		{
			self::set_test_connections(3);
			$error_report = $Session->start(self::$admin_uid, $password, self::$admin_data['level'], '', '', '', $autoconnexion); //On lance la session.
		}
		else
		{
			return false;
		}
		return empty($error_report);
	}

	private static function reset_attempts()
	{
		self::set_test_connections(0);
	}

	private static function log_connection_error()
	{
		self::$admin_data['test_connect']++;
		self::set_test_connections(self::$admin_data['test_connect']);
		$remaining_connections = 5 - (self::$admin_data['test_connect']);
	}

	private static function unlock_admin()
	{
		$unlock = strhash(AppContext::get_request()->get_poststring('unlock', ''));
		global $CONFIG;
		if (!empty($unlock) && $unlock !== $CONFIG['unlock_admin'])
		{
			AppContext::get_session()->end();
		}
	}

	private static function set_test_connections($tests)
	{
		AppContext::get_sql_querier()->inject(
			'UPDATE ' . DB_TABLE_MEMBER . ' SET last_connect=:last_connect, test_connect=:test_connect
			WHERE user_id=:user_id', array(
				'last_connect' => time(),
				'test_connect' => $tests,
				'user_id' => self::$admin_uid
		));
	}
}
?>