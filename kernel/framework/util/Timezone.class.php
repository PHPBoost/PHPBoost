<?php
/*##################################################
 *                            Timezone.class.php
 *                            -------------------
 *   begin                : October 30, 2010
 *   copyright            : (C) 2010 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

class Timezone
{	
	const SERVER_TIMEZONE = 1;
	const SITE_TIMEZONE = 2;
	const USER_TIMEZONE = 3;
	
	private static $server_timezone;
	private static $site_timezone;
	private static $user_timezone;
	
	public static function get_supported_timezones()
	{
		return DateTimeZone::listIdentifiers();
	}
	
	/**
	 * @desc Returns the PHP timezone corresponding to the timezone code
	 * @param int $timezone SERVER_TIMEZONE, SITE_TIMEZONE or USER_TIMEZONE
	 * @return DateTimeZone The PHP timezone
	 */
	public static function get_timezone($timezone_code)
	{
		switch($timezone_code)
		{
			case self::SERVER_TIMEZONE:
				return self::get_server_timezone();
			case self::SITE_TIMEZONE:
				return self::get_site_timezone();
			default:
				return self::get_user_timezone();
		}
	}
	
	public static function get_server_timezone()
	{
		if (self::$server_timezone == null)
		{
			self::$server_timezone = new DateTimeZone(date_default_timezone_get());
		}
		return self::$server_timezone;
	}
	
	public static function get_site_timezone()
	{
		if (self::$site_timezone == null)
		{
			self::$site_timezone = new DateTimeZone(GeneralConfig::load()->get_site_timezone());
		}
		return self::$site_timezone;
	}
	
	public static function get_user_timezone()
	{
		if (self::$user_timezone == null)
		{
			self::$user_timezone = new DateTimeZone(AppContext::get_current_user()->get_timezone());
		}
		return self::$user_timezone;
	}
}
?>