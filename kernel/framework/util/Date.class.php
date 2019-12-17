<?php
/**
 * This class allows you to handle easily some dates. A date is a day and an hour (year, month, day, hour, minutes, seconds).
 * It supports the most common formats and manages timezones. Here are the definitions of the 3 existing timezones:
 * <ul>
 * 	<li>System timezone: it's the timezone of the server, configured by the hoster. For instance, if your server is in France, it should be GMT+1.</li>
 * 	<li>Site timezone: it's the timezone of the central place of the site. For example, if your site deals with the italian soccer championship, it will be GMT+1.</li>
 * 	<li>User timezone :  each registered user can specify its timezone. It's particulary useful for people who visit some sites from a foreign country.</li>
 * </ul>
 * @package     Util
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 03 23
 * @since       PHPBoost 2.0 - 2008 06 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class Date
{
	const DATE_NOW = 'now';

	const FORMAT_TIMESTAMP = 0;
	const FORMAT_DAY_MONTH = 1;
	const FORMAT_DAY_MONTH_YEAR = 2;
	const FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE = 3;
	const FORMAT_RFC2822 = 4;
	const FORMAT_ISO8601 = 5;
	const FORMAT_DAY_MONTH_YEAR_LONG = 6;
	const FORMAT_DAY_MONTH_YEAR_TEXT = 7;
	const FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE_TEXT = 8;
	const FORMAT_RELATIVE = 9;
	const FORMAT_ISO_DAY_MONTH_YEAR = 10;
	const FORMAT_DIFF_NOW = 11;

	/**
	 * @var DateTime Representation of date and time.
	 */
	private $date_time;

	/**
	 * Builds and initializes a date.
	 * The first parameter is the date in a standardized format defined in the PHP documentation. To get the current date, use the Date::DATE_NOW
	 * The second parameter allows us to chose what time referential we use to create the date:
	 * <ul>
	 * 	<li>Timezone::SERVER_TIMEZONE if that date comes from for example the database (dates must be stored under this referential).</li>
	 * 	<li>Timezone::SITE_TIMEZONE if it's an entry coming from the site (nearly never used).</li>
	 * 	<li>Timezone::USER_TIMEZONE if it's an entry coming from the user (it's own timezone will be used)</li>
	 * </ul>
	 */
	public function __construct($time = self::DATE_NOW, $referencial_timezone = Timezone::USER_TIMEZONE)
	{
		$date_timezone = Timezone::get_timezone($referencial_timezone);

		if (preg_match('`^([0-9]+)$`iu', $time))
		{
			$this->date_time = new DateTime();
			$this->date_time->setTimezone($date_timezone);
			$this->date_time->setTimestamp($time);
		}
		else if (preg_match('`^-([0-9]+)$`iu', $time))
		{
			$this->date_time = new DateTime('@' . $time, $date_timezone);
		}
		else
		{
			$this->date_time = new DateTime($time, $date_timezone);
		}
	}

	/**
	 * Formats the date to a particular format.
	 * @param int $format One of the following enumeration:
	 * <ul>
	 * 	<li>Date::FORMAT_DAY_MONTH for a tiny formatting (only month and day)</li>
	 * 	<li>Date::FORMAT_DAY_MONTH_YEAR for a short formatting (month, day, year)</li>
	 * 	<li>Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE for a longer displaying (year, month, day, hour and minutes)</li>
	 *  <li>Date::FORMAT_TIMESTAMP for a timestamp</li>
	 * 	<li>Date::FORMAT_RFC822 to format according to what the RFC822 announces</li>
	 * 	<li>Date::FORMAT_ISO8601 to format according to what the ISO8601 announces</li>
	 * 	<li>Date::FORMAT_DAY_MONTH_YEAR_LONG</li>
	 * 	<li>Date::FORMAT_DAY_MONTH_YEAR_TEXT</li>
	 * 	<li>Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE_TEXT</li>
	 * 	<li>Date::FORMAT_RELATIVE</li>
	 * 	<li>Date::FORMAT_ISO_DAY_MONTH_YEAR</li>
	 * 	<li>Date::FORMAT_DIFF_NOW</li>
	 * </ul>
	 * @param int $referencial_timezone One of the following enumeration:
	 * <ul>
	 * 	<li>Timezone::SERVER_TIMEZONE</li>
	 * 	<li>Timezone::SITE_TIMEZONE</li>
	 * 	<li>Timezone::USER_TIMEZONE</li>
	 * </ul>
	 * @return string The formatted date
	 */
	public function format($format = self::FORMAT_DAY_MONTH, $referencial_timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($referencial_timezone);

		if (is_string($format))
		{
			return $this->date_time->format($format);
		}

		switch ($format)
		{
			case self::FORMAT_DAY_MONTH:
				return $this->date_time->format(LangLoader::get_message('date_format_day_month', 'date-common'));
				break;

			case self::FORMAT_DAY_MONTH_YEAR:
				return $this->date_time->format(LangLoader::get_message('date_format_day_month_year', 'date-common'));
				break;

			case self::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE:
				return $this->date_time->format(LangLoader::get_message('date_format_day_month_year_hour_minute', 'date-common'));
				break;

			case self::FORMAT_TIMESTAMP:
				return $this->date_time->getTimestamp();
				break;

			case self::FORMAT_RFC2822:
				return $this->date_time->format('r');
				break;

			case self::FORMAT_ISO8601:
				return $this->date_time->format('c');
				break;

			case self::FORMAT_DAY_MONTH_YEAR_LONG:
				return self::transform_date($this->date_time->format(LangLoader::get_message('date_format_day_month_year_long', 'date-common')));
				break;

			case self::FORMAT_DAY_MONTH_YEAR_TEXT:
				return self::transform_date($this->date_time->format(LangLoader::get_message('date_format_day_month_year_text', 'date-common')));
				break;

			case self::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE_TEXT:
				return self::transform_date($this->date_time->format(LangLoader::get_message('date_format_day_month_year_hour_minute_text', 'date-common')));
				break;

			case self::FORMAT_RELATIVE:
				return self::get_date_relative($this->get_timestamp(), $referencial_timezone);
				break;

			case self::FORMAT_ISO_DAY_MONTH_YEAR:
				return $this->date_time->format('Y-m-d');
				break;

			case self::FORMAT_DIFF_NOW:
				$time = self::get_date_relative($this->get_timestamp(), $referencial_timezone);
				if ($time !== LangLoader::get_message('instantly', 'date-common'))
					$time = StringVars::replace_vars(LangLoader::get_message('ago', 'date-common'), array('time' => $time));
				return $time;
				break;

			default:
				return '';
		}
	}

	/**
	 * Returns the relative time associated to the date
	 * @param int $timestamp
	 * @param int $referencial_timezone
	 * @return string The relative time
	 */
	public function get_date_relative($timestamp, $referencial_timezone)
	{
		$now = new Date(Date::DATE_NOW, $referencial_timezone);

		if ($now->get_timestamp() > $timestamp)
			$time_diff = $now->get_timestamp() - $timestamp;
		else
			$time_diff = $timestamp - $now->get_timestamp();

		$secondes = $time_diff;
		$minutes  = round($time_diff/60);
		$hours    = round($time_diff/3600);
		$days     = round($time_diff/86400);
		$weeks    = round($time_diff/604800);
		$months   = round($time_diff/2419200);
		$years    = round($time_diff/29030400);

		if ($secondes == 1)
			return LangLoader::get_message('instantly', 'date-common');
		elseif ($secondes < 60)
			return $secondes . ' ' . LangLoader::get_message('seconds', 'date-common');
		elseif ($minutes < 60)
			return $minutes . ' ' . ($minutes > 1 ? LangLoader::get_message('minutes', 'date-common') : LangLoader::get_message('minute', 'date-common'));
		elseif ($hours < 24)
			return $hours . ' ' . ($hours > 1 ? LangLoader::get_message('hours', 'date-common') : LangLoader::get_message('hour', 'date-common'));
		elseif ($days < 7)
			return $days . ' ' . ($days > 1 ? LangLoader::get_message('days', 'date-common') : LangLoader::get_message('day', 'date-common'));
		elseif ($weeks < 4)
			return $weeks . ' ' . ($weeks > 1 ? LangLoader::get_message('weeks', 'date-common') : LangLoader::get_message('week', 'date-common'));
		elseif ($months < 12)
			return $months . ' ' . ($months > 1 ? LangLoader::get_message('months', 'date-common') : LangLoader::get_message('month', 'date-common'));
		else
			return $years . ' ' . ($years > 1 ? LangLoader::get_message('years', 'date-common') : LangLoader::get_message('year', 'date-common'));
	}

	/**
	 * Returns the timestamp associated to the date
	 * @return int The timestamp
	 */
	public function get_timestamp()
	{
		return $this->date_time->getTimestamp();
	}

	/**
	 * Returns DateTime
	 * @return DateTime
	 */
	public function get_date_time()
	{
		return $this->date_time;
	}

	/**
	 * Returns the year of the date
	 * @param $timezone The timezone in which you want this value
	 * @return string The year
	 */
	public function get_year($timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($timezone);
		return $this->date_time->format('Y');
	}

	public function set_year($year, $referential_timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($referential_timezone);
		$this->date_time->setDate($year, $this->get_month(), $this->get_day());
	}

	/**
	 * Returns the month of the date
	 * @param $timezone The timezone in which you want this value
	 * @return string The month
	 */
	public function get_month($timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($timezone);
		return $this->date_time->format('m');
	}

	public function set_month($month, $referential_timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($referential_timezone);
		$this->date_time->setDate($this->get_year(), $month, $this->get_day());
	}

	/**
	 * Returns first charaters (all per default) of the month name
	 * @param $characters_number The characters number requested (usually 2 or 3)
	 * @param $timezone The timezone in which you want this value
	 * @return string The first letters of the month name
	 */
	public function get_month_text($characters_number = '', $timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($timezone);
		return Texthelper::ucfirst(TextHelper::mb_substr(self::transform_date($this->date_time->format('F')), 0, $characters_number));
	}

	/**
	 * Returns the week number of the date
	 * @param $timezone The timezone in which you want this value
	 * @return string The week number
	 */
	public function get_week_number($referential_timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($referential_timezone);
		return $this->date_time->format('W');
	}

	public function set_week_number($week_number)
	{
		$this->date_time->setISODate($this->get_year(), $week_number);
	}

	/**
	 * Returns the day of the date
	 * @param $timezone The timezone in which you want this value
	 * @return string The day
	 */
	public function get_day($timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($timezone);
		return $this->date_time->format('j');
	}

	public function get_day_two_digits($timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($timezone);
		return $this->date_time->format('d');
	}

	public function set_day($day, $referential_timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($referential_timezone);
		$this->date_time->setDate($this->get_year(), $this->get_month(), $day);
	}

	/**
	 * Returns first charaters (all per default) of the day of week name
	 * @param $characters_number The characters number requested (usually 2 or 3)
	 * @param $timezone The timezone in which you want this value
	 * @return string The first letters of the day name
	 */
	public function get_day_text($characters_number = '', $timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($timezone);
		return Texthelper::ucfirst(TextHelper::substr(self::transform_date($this->date_time->format('l')), 0, $characters_number));
	}

	/**
	 * Returns the day of the week (0 for sunday to 6 for saturday)
	 * @param $timezone The timezone in which you want this value
	 * @return string The day of the year
	 */
	public function get_day_of_week($timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($timezone);
		return (int)$this->date_time->format('w');
	}

	/**
	 * Returns the day of the year
	 * @param $timezone The timezone in which you want this value
	 * @return string The day of the year
	 */
	public function get_day_of_year($timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($timezone);
		return (int)$this->date_time->format('z');
	}

	public function set_day_of_year($day_of_year)
	{
		$this->date_time->modify($this->get_year() . '-01-00 ' . $day_of_year. 'days');
	}

	/**
	 * Returns the hours of the date
	 * @param $timezone The timezone in which you want this value
	 * @return string The hours
	 */
	public function get_hours($timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($timezone);
		return $this->date_time->format('H');
	}

	public function set_hours($hours, $referential_timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($referential_timezone);
		$this->date_time->setTime($hours, $this->get_minutes(), $this->get_seconds());
	}

	/**
	 * Returns the minutes of the date
	 * @return string The minutes
	 */
	public function get_minutes()
	{
		return $this->date_time->format('i');
	}

	public function set_minutes($minutes, $referential_timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($referential_timezone);
		$this->date_time->setTime($this->get_hours(), $minutes, $this->get_seconds());
	}

	/**
	 * Returns the seconds of the date
	 * @return string The seconds
	 */
	public function get_seconds()
	{
		return $this->date_time->format('s');
	}

	public function set_seconds($seconds, $referential_timezone = Timezone::USER_TIMEZONE)
	{
		$this->compute_server_user_difference($referential_timezone);
		$this->date_time->setTime($this->get_hours(), $this->get_minutes(), $seconds);
	}

	/**
	 * Exports the date according to the format YYYY-mm-dd
	 * @return string The formatted date
	 */
	public function to_date()
	{
		return $this->date_time->format('Y-m-d');
	}

	/**
	 * Tells whether this date is anterior to the given one
	 * @param Date $date The date to compare with
	 * @return bool
	 */
	public function is_anterior_to(Date $date)
	{
		return $this->get_date_time() < $date->get_date_time();
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
	 * Tells whether this date equals the given one
	 * @param Date $date The date to compare with.
	 * @return bool true if the two dates are the same, false otherwise
	 */
	public function equals(Date $date)
	{
		return $this->get_date_time() == $date->get_date_time();
	}

	/**
	 * Adds the given number of days to the date
	 * @param int $number_days The number of days to add.
	 */
	public function add_days($number_days)
	{
		$this->date_time->modify('+'.$number_days.' days');
	}

	/**
	 * Adds the given number of weeks to the date
	 * @param int $number_weeks The number of weeks to add.
	 */
	public function add_weeks($number_weeks)
	{
		$this->date_time->modify('+'.$number_weeks.' weeks');
	}

	/**
	 * Tells whether the year of the date is bissextile
	 * @return bool true if the year is bissextile, false otherwise
	 */
	public function is_date_year_bissextile()
	{
		return $this->date_time->format('L') == 1;
	}

	/**
	 * Determines whether a date is correct. For example the february 31st is not correct.
	 * @param int $month The month
	 * @param int $day The day
	 * @param int $year The year
	 * @return bool true if the date is correct and false otherwise.
	 */
	private static function check_date($month, $day, $year)
	{
		return checkdate($month, $day, $year);
	}

	public static function to_format($time, $format = self::FORMAT_DAY_MONTH, $referencial_timezone = Timezone::USER_TIMEZONE)
	{
		$date = new Date($time, $referencial_timezone);
		return $date->format($format);
	}

	public static function set_default_timezone()
	{
		$default = @date_default_timezone_get();
        @date_default_timezone_set($default);
	}

	/**
	 * Calculates and return date formats to use many variables in the TPL.
	 * @param Date $date The concerned date
	 * @param string $date_label The purpose of the date
	 * @return string[] true if the date is correct and false otherwise.
	 */
	public static function get_array_tpl_vars($date, $date_label)
	{
		if ($date == null || !$date instanceof Date || empty($date_label))
			return array();

		$date_label = TextHelper::strtoupper($date_label);
		return array(
			$date_label                       => $date->format(Date::FORMAT_DAY_MONTH_YEAR),
			$date_label . '_TIMESTAMP'        => $date->get_timestamp(),
			$date_label . '_SHORT'            => $date->format(Date::FORMAT_DAY_MONTH_YEAR),
			$date_label . '_SHORT_TEXT'       => $date->format(Date::FORMAT_DAY_MONTH_YEAR_TEXT),
			$date_label . '_SHORT_MONTH_TEXT' => $date->format(Date::FORMAT_DAY_MONTH_YEAR_LONG),
			$date_label . '_FULL'             => $date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
			$date_label . '_DAY'              => $date->get_day(), // The number of day
			$date_label . '_DAY_TEXT'         => $date->get_day_text(3), // 3 first characters of day name
			$date_label . '_DAY_FULLTEXT'     => $date->get_day_text(), // All characters of day name
			$date_label . '_WEEK'             => $date->get_week_number(),
			$date_label . '_MONTH'            => $date->get_month(), // The number of month
			$date_label . '_MONTH_TEXT'       => $date->get_month_text(3), // 3 first characters of month name
			$date_label . '_MONTH_FULLTEXT'   => $date->get_month_text(), // All characters of month name
			$date_label . '_YEAR'             => $date->get_year(),
			$date_label . '_DAY_MONTH'        => $date->format(Date::FORMAT_DAY_MONTH),
			$date_label . '_HOUR'             => $date->get_hours(),
			$date_label . '_MINUTE'           => $date->get_minutes(),
			$date_label . '_SECONDS'          => $date->get_seconds(),
			$date_label . '_ISO8601'          => $date->format(Date::FORMAT_ISO8601),
			$date_label . '_DIFF_NOW'         => $date->format(Date::FORMAT_DIFF_NOW),
			$date_label . '_RELATIVE'         => $date->format(Date::FORMAT_RELATIVE)
		);
	}

	/**
	 * Computes the time difference between the server and the current user
	 * @return int The time difference (in hours)
	 */
	private function compute_server_user_difference($referencial_timezone = Timezone::SERVER_TIMEZONE)
	{
		$this->date_time->setTimezone(Timezone::get_timezone($referencial_timezone));
	}

	private static function transform_date($date)
	{
		$date_lang = LangLoader::get('date-common');

		$search = array(
			'january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december',
			'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'
		);
		$replace = array(
			$date_lang['january'], $date_lang['february'], $date_lang['march'], $date_lang['april'], $date_lang['may'], $date_lang['june'],
			$date_lang['july'], $date_lang['august'], $date_lang['september'], $date_lang['october'], $date_lang['november'], $date_lang['december'],
			$date_lang['monday'], $date_lang['tuesday'], $date_lang['wednesday'], $date_lang['thursday'], $date_lang['friday'], $date_lang['saturday'], $date_lang['sunday'],
		);
		return str_replace($search, $replace, TextHelper::strtolower($date));
	}
}
?>
