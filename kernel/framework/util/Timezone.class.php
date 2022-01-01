<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 01 01
 * @since       PHPBoost 3.0 - 2010 10 30
 * @contributor Kevin MASSY <reidlos@phpboost.com>
*/

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
	 * Returns the PHP timezone corresponding to the timezone code
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
