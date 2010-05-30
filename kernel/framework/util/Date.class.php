<?php
/*##################################################
 *                               date.class.php
 *                            -------------------
 *   begin                : June 1st, 2008
 *   copyright            : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   Date 1.0
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

define('DATE_TIMESTAMP', 		0);
define('DATE_NOW', 				1);
define('DATE_YEAR_MONTH_DAY', 	2);
define('DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND', 3);
define('DATE_FROM_STRING', 		4);
define('DATE_FORMAT_TINY', 		1);
define('DATE_FORMAT_SHORT', 	2);
define('DATE_FORMAT', 			3);
define('DATE_FORMAT_LONG', 		4);
define('DATE_RFC822_F', 		5);
define('DATE_RFC3339_F', 		6);

define('DATE_RFC822_FORMAT', 	'D, d M Y H:i:s O');
define('DATE_RFC3339_FORMAT', 	'Y-m-d\TH:i:s');

define('TIMEZONE_AUTO', 		TIMEZONE_USER);

/**
 * @package util
 * @desc This class allows you to handle easily some dates. A date is a day and an hour (year, month, day, hour, minutes, seconds).
 * It supports the most common formats and manages timezones. Here are the definitions of the 3 existing timezones:
 * <ul>
 * 	<li>System timezone: it's the timezone of the server, configured by the hoster. For instance, if your server is in France, it should be GMT+1.</li>
 * 	<li>Site timezone: it's the timezone of the central place of the site. For example, if your site deals with the italian soccer championship, it will be GMT+1.</li>
 * 	<li>User timezone :  each registered user can specify its timezone. It's particulary useful for people who visit some sites from a foreign country.</li>
 * </ul>
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class Date
{
	/**
	 * @var int The timestamp of the current date
	 */
	private $timestamp = 0;

	/**
	 * @desc Builds and initializes a date. It admits a variable number of parameters depending on the value of the first one.
	 * The second parameter allows us to chose what time referential we use to create the date:
	 * <ul>
	 * 	<li>TIMEZONE_SYSTEM if that date comes from for example the database (dates must be stored under this referential).</li>
	 * 	<li>TIMEZONE_SITE if it's an entry coming from the site (nearly never used).</li>
	 * 	<li>TIMEZONE_USER if it's an entry coming from the user (it's own timezone will be used)</li>
	 * </ul>
	 * The first parameter determines how to initialize the date, here are the rules to use for the other parameters:
	 * <ul>
	 * 	<li>DATE_NOW will initialize the date to the current time.
	 * $date = new Date(DATE_NOW); will build a date with the current date.</li>
	 * 	<li>DATE_YEAR_MONTH_DAY if you want to build a date from a specified day (year, month, day). In this case the following parameters are:
	 * 		<ul>
	 * 			<li>int The year (for instance 2009)</li>
	 * 			<li>int The month (for example 11)</li>
	 * 			<li>int The day (for example 09)</li>
	 * 		</ul>
	 * For example, $date = new Date(DATE_YEAR_MONTH_DAY, TIMEZONE_USER, 2009, 11, 09);
	 * 	</li>
	 * 	<li>DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND if you want to build a date from a specified time. Here is the rule for the following parameters:
	 * 		<ul>
	 * 			<li>int The year (for instance 2009)</li>
	 * 			<li>int The month (for instance 11)</li>
	 * 			<li>int The day (for instance 09)</li>
	 * 			<li>int The hour (for instance 12)</li>
	 * 			<li>int The minutes (for instance 34)</li>
	 * 			<li>int The seconds (for instance 12)</li>
	 * 		</ul>
	 * For instance $date = new Date(DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND, 2009, 11, 09, 12, 34, 12);
	 * 	</li>
	 * 	<li>DATE_TIMESTAMP which builds a date from a UNIX timestamp.
	 * For example $date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, time()); is equivalent to $date = new Date(DATE_NOW);</li>
	 * 	<li>DATE_FROM_STRING which decodes a date written in a string by matching a pattern you have to specify.
	 * The pattern is easy to write: d for day, m for month and y for year and the separators have to be slashes only.
	 * For instance, if your third parameter is '12/24/2009' and the fourth is 'm/d/y', it will be the december 24th of 2009.</li>
	 * </ul>
	 * Here are the rules:
	 *
	 */
	public function __construct()
	{
		global $CONFIG;

		//Nombre d'arguments
		$num_args = func_num_args();

		if ($num_args == 0)
		{
			$format = DATE_NOW;
		}
		else
		{
			$format = func_get_arg(0);
		}

		if ($format != DATE_NOW)
		{
			// Fuseau horaire
			if ($num_args >= 2)
			{
				$referencial_timezone = func_get_arg(1);
			}
			else
			{
				$referencial_timezone = TIMEZONE_USER;
			}

			$time_difference = self::compute_server_user_difference($referencial_timezone);
		}

		switch ($format)
		{
			case DATE_NOW:
				$this->timestamp = time();
				break;

				// Année mois jour
			case DATE_YEAR_MONTH_DAY:
				if ($num_args >= 5)
				{
					$year 	= func_get_arg(2);
					$month 	= func_get_arg(3);
					$day 	= func_get_arg(4);
					$this->timestamp = mktime(0, 0, 0, $month, $day, $year) - $time_difference * 3600;
				}
				else
				{
					$this->timestamp = 0;
				}
				break;

				// Année mois jour heure minute seconde
			case DATE_YEAR_MONTH_DAY_HOUR_MINUTE_SECOND:
				if ($num_args >= 7)
				{
					$year 		= func_get_arg(2);
					$month 		= func_get_arg(3);
					$day 		= func_get_arg(4);
					$hour 		= func_get_arg(5);
					$minute 	= func_get_arg(6);
					$seconds 	= func_get_arg(7);
					$this->timestamp = mktime($hour, $minute, $seconds, $month, $day, $year) - $time_difference * 3600;
				}
				else
				{
					$this->timestamp = 0;
				}
				break;

			case DATE_TIMESTAMP:
				if ($num_args >= 3)
				{
					$this->timestamp = func_get_arg(2) - $time_difference * 3600;
				}
				else
				{
					$this->timestamp = 0;
				}
				break;

			case DATE_FROM_STRING:
				if ($num_args < 4)
				{
					$this->timestamp = 0;
					break;
				}
				list($month, $day, $year) = array(0, 0, 0);
				$str 				= func_get_arg(2);
				$date_format 		= func_get_arg(3);
				$given_times 	= explode('/', $str);
				$array_date 	 	= explode('/', $date_format);
				for ($i = 0; $i < 3; $i++)
				{
					switch ($array_date[$i])
					{
						case 'd':
							$day = (isset($given_times[$i])) ? NumberHelper::numeric($given_times[$i]) : 0;
							break;

						case 'm':
							$month = (isset($given_times[$i])) ? NumberHelper::numeric($given_times[$i]) : 0;
							break;

						case 'y':
							$year = (isset($given_times[$i])) ? NumberHelper::numeric($given_times[$i]) : 0;
							break;
					}
				}

				//Vérification du format de la date.
				if (self::check_date($month, $day, $year))
				{
					$this->timestamp = @mktime(0, 0, 1, $month, $day, $year) - $time_difference * 3600;
				}
				else
				{
					$this->timestamp = time();
				}
				break;

			default:
				$this->timestamp = 0;
		}
	}

	/**
	 * @desc Formats the date to a particular format.
	 * @param int $format One of the following enumeration:
	 * <ul>
	 * 	<li>DATE_FORMAT_TINY for a tiny formatting (only month and day)</li>
	 * 	<li>DATE_FORMAT_SHORT for a short formatting (month, day, year)</li>
	 * 	<li>DATE_FORMAT for a longer displaying (year, month, day, hour and minutes)</li>
	 * 	<li>DATE_FORMAT_LONG for a total displaying (year, month, day, hour, minutes and seconds)</li>
	 * 	<li>DATE_RFC822_F to format according to what the RFC822 announces</li>
	 * 	<li>DATE_RFC3339_F to format according to what the RFC3339 announces</li>
	 * </ul>
	 * @param int $referencial_timezone One of the following enumeration:
	 * <ul>
	 * 	<li>TIMEZONE_SYSTEM</li>
	 * 	<li>TIMZONE_SITE</li>
	 * 	<li>TIMEZONE_USER</li>
	 * </ul>
	 * @return string The formatted date
	 */
	public function format($format = DATE_FORMAT_TINY, $referencial_timezone = TIMEZONE_USER)
	{
		global $LANG, $CONFIG;

		$timestamp = $this->timestamp + self::compute_server_user_difference($referencial_timezone) * 3600;

		if ($timestamp <= 0)
		{
			return '';
		}

		switch ($format)
		{
			case DATE_FORMAT_TINY:
				return date($LANG['date_format_tiny'], $timestamp);
				break;

			case DATE_FORMAT_SHORT:
				return date($LANG['date_format_short'], $timestamp);
				break;
			case DATE_FORMAT:
				return date($LANG['date_format'], $timestamp);
				break;

			case DATE_FORMAT_LONG:
				return date($LANG['date_format_long'], $timestamp);
				break;

			case DATE_TIMESTAMP:
				return $timestamp;
				break;

			case DATE_RFC822_F:
				return date(DATE_RFC822_FORMAT, $timestamp);
				break;

			case DATE_RFC3339_F:
				return date(DATE_RFC3339_FORMAT, $timestamp) . ($CONFIG['timezone'] < 0 ? '-' : '+') . sprintf('%02d:00',$CONFIG['timezone']);
				break;

			default:
				return '';
		}
	}

	/**
	 * @desc Returns the timestamp associated to the date
	 * @param $timezone The timezone in which you want this value
	 * @return int The timestamp
	 */
	public function get_timestamp($timezone = TIMEZONE_SYSTEM)
	{
		return $this->get_adjusted_timestamp($timezone);
	}

	/**
	 * @desc Returns the year of the date
	 * @param $timezone The timezone in which you want this value
	 * @return string The year
	 */
	public function get_year($timezone = TIMEZONE_AUTO)
	{
		return date('Y', $this->get_adjusted_timestamp($timezone));
	}

	public function set_year($year)
	{
		$this->set_date_from_values($year, $this->get_month(), $this->get_day(),
		$this->get_hours(), $this->get_minutes(), $this->get_seconds());
	}

	/**
	 * @desc Returns the month of the date
	 * @param $timezone The timezone in which you want this value
	 * @return string The month
	 */
	public function get_month($timezone = TIMEZONE_AUTO)
	{
		return date('m', $this->get_adjusted_timestamp($timezone));
	}

	public function set_month($month)
	{
		$this->set_date_from_values($this->get_year(), $month, $this->get_day(),
		$this->get_hours(), $this->get_minutes(), $this->get_seconds());
	}

	/**
	 * @desc Returns the day of the date
	 * @param $timezone The timezone in which you want this value
	 * @return string The day
	 */
	public function get_day($timezone = TIMEZONE_AUTO)
	{
		return (int)date('d', $this->get_adjusted_timestamp($timezone));
	}

	public function set_day($day)
	{
		$this->set_date_from_values($this->get_year(), $this->get_month(), $day,
		$this->get_hours(), $this->get_minutes(), $this->get_seconds());
	}

	/**
	 * @desc Returns the hours of the date
	 * @param $timezone The timezone in which you want this value
	 * @return string The hours
	 */
	public function get_hours($timezone = TIMEZONE_AUTO)
	{
		return date('H', $this->get_adjusted_timestamp($timezone));
	}

	public function set_hours($hours, $referential_timezone = TIMEZONE_AUTO)
	{
		$adjusted_hours = $hours + self::compute_server_user_difference($referential_timezone);
		$this->set_date_from_values($this->get_year(), $this->get_month(), $this->get_day(),
		$adjusted_hours, $this->get_minutes(), $this->get_seconds());
	}

	/**
	 * @desc Returns the minutes of the date
	 * @return string The minutes
	 */
	public function get_minutes()
	{
		return date('i', $this->timestamp);
	}

	public function set_minutes($minutes)
	{
		$this->set_date_from_values($this->get_year(), $this->get_month(), $this->get_day(),
		$this->get_hours(), $minutes, $this->get_seconds());
	}

	/**
	 * @desc Returns the seconds of the date
	 * @return string The seconds
	 */
	public function get_seconds()
	{
		return date('s', $this->timestamp);
	}

	public function set_seconds($second)
	{
		$this->set_date_from_values($this->get_year(), $this->get_month(), $this->get_day(),
		$this->get_hours(), $this->get_minutes(), $second);
	}

	/**
	 * @desc Exports the date according to the format YYYY-mm-dd
	 * @return string The formatted date
	 */
	public function to_date()
	{
		return date('Y-m-d', $this->timestamp);
	}
	
	/**
	 * Tells whether this date is anterior to the given one
	 * @param Date $date The date to compare with
	 * @return bool
	 */
	public function is_anterior_to(Date $date)
	{
		return $this->timestamp < $date->timestamp;
	}
	
	/**
	 * Tells whether this date is posterior to the given one
	 * @param Date $date The date to compare with
	 * @return bool
	 */
	public function is_posterior_to(Date $date)
	{
		return !$this->is_anterior_to($date);
	}

	/**
	 * Tells whetehr this date equals the given one
	 * @param Date $date The date to compare with.
	 * @return bool true if the two dates are the same, false otherwise
	 */
	public function equals(Date $date)
	{
		return $this->timestamp == $date->timestamp;
	}
	/**
	 * @desc Determines whether a date is correct. For example the february 31st is not correct.
	 * @param int $month The month
	 * @param int $day The day
	 * @param int $year The year
	 * @return bool true if the date is correct and false otherwise.
	 */
	private static function check_date($month, $day, $year)
	{
		return checkdate($month, $day, $year);
	}

	/**
	 * @desc Computes the time difference between the server and the current user
	 * @return int The time difference (in hours)
	 */
	private static function compute_server_user_difference($referencial_timezone = TIMEZONE_SYSTEM)
	{
		global $CONFIG;

		// Décallage du serveur par rapport au méridien de greenwitch et à l'heure d'été
		$server_hour = intval(date('Z') / 3600) - intval(date('I'));
			
		switch ($referencial_timezone)
		{
			// Référentiel : heure du site
			case TIMEZONE_SITE:
				$timezone = $CONFIG['timezone'] - $server_hour;
				break;

				//Référentiel : heure du serveur
			case TIMEZONE_SYSTEM:
				$timezone = 0;
				break;

			case TIMEZONE_USER:
				$timezone = AppContext::get_user()->get_attribute('user_timezone')
					 - $server_hour;
				break;
					
			default:
				$timezone = 0;
		}
		return $timezone;
	}

	private function set_date_from_values($year, $month, $day, $hours, $minutes, $seconds)
	{
		$this->timestamp = mktime($hours, $minutes, $seconds, $month, $day, $year);
	}
	
	private function get_adjusted_timestamp($timezone)
	{
		return $this->timestamp + self::compute_server_user_difference($timezone) * 3600;
	}
}
?>