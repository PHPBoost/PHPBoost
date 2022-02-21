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
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 21
 * @since       PHPBoost 2.0 - 2008 06 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Date
{
	const DATE_NOW = 'now';

	const FORMAT_TIMESTAMP                       = 0;
	const FORMAT_DAY_MONTH                       = 1;
	const FORMAT_DAY_MONTH_YEAR                  = 2;
	const FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE      = 3;
	const FORMAT_RFC2822                         = 4;
	const FORMAT_ISO8601                         = 5;
	const FORMAT_DAY_MONTH_YEAR_LONG             = 6;
	const FORMAT_DAY_MONTH_YEAR_TEXT             = 7;
	const FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE_TEXT = 8;
	const FORMAT_RELATIVE                        = 9;
	const FORMAT_ISO_DAY_MONTH_YEAR              = 10;
	const FORMAT_AGO                             = 11;
	const FORMAT_SINCE                           = 12;
	const FORMAT_DELAY                           = 13;
	const FORMAT_HOUR_MINUTE                     = 14;
	const FORMAT_DAY_MONTH_TEXT                  = 15;

	/**
	 * @var DateTime Representation of date and time.
	 */
	private $date_time;
	
	protected static $lang;

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
		self::$lang = LangLoader::get('date-lang');
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
	 * 	<li>Date::FORMAT_AGO</li>
	 *  <li>Date::FORMAT_SINCE</li>
	 *  <li>Date::FORMAT_HOUR_MINUTE</li>
	 *  <li>Date::FORMAT_DAY_MONTH_TEXT</li>
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
			case self::FORMAT_TIMESTAMP:
				return $this->date_time->getTimestamp();
				break;

			case self::FORMAT_DAY_MONTH:
				return $this->date_time->format(self::$lang['date.format.day.month']);
				break;

			case self::FORMAT_DAY_MONTH_YEAR:
				return $this->date_time->format(self::$lang['date.format.day.month.year']);
				break;

			case self::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE:
				return $this->date_time->format(self::$lang['date.format.day.month.year.hour.minute']);
				break;

			case self::FORMAT_RFC2822:
				return $this->date_time->format('r');
				break;

			case self::FORMAT_ISO8601:
				return $this->date_time->format('c');
				break;

			case self::FORMAT_DAY_MONTH_YEAR_LONG:
				return self::transform_date($this->date_time->format(self::$lang['date.format.day.month.year.long']));
				break;

			case self::FORMAT_DAY_MONTH_YEAR_TEXT:
				return self::transform_date($this->date_time->format(self::$lang['date.format.day.month.year.text']));
				break;

			case self::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE_TEXT:
				return self::transform_date($this->date_time->format(self::$lang['date.format.day.month.year.hour.minute.text']));
				break;

			case self::FORMAT_RELATIVE:
				return self::get_date_relative($this->get_timestamp(), $referencial_timezone);
				break;

			case self::FORMAT_ISO_DAY_MONTH_YEAR:
				return $this->date_time->format('Y-m-d');
				break;

			case self::FORMAT_AGO:
				$time = self::get_date_relative($this->get_timestamp(), $referencial_timezone);
				if ($time !== self::$lang['date.instantly'])
					$time = StringVars::replace_vars(self::$lang['date.ago'], array('time' => $time));
				return $time;
				break;

			case self::FORMAT_SINCE:
				$time = self::get_date_relative($this->get_timestamp(), $referencial_timezone);
				if ($time !== self::$lang['date.instantly'])
					$time = StringVars::replace_vars(self::$lang['date.since'], array('time' => $time));
				return $time;
				break;

			case self::FORMAT_DELAY:
				return self::get_date_delay($this, $referencial_timezone);
				break;

			case self::FORMAT_HOUR_MINUTE:
				return self::transform_date($this->date_time->format(self::$lang['date.format.hour.minute']));
				break;

			case self::FORMAT_DAY_MONTH_TEXT:
				return self::transform_date($this->date_time->format(self::$lang['date.format.day.month.text']));
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
			return self::$lang['date.instantly'];
		elseif ($secondes < 60)
			return $secondes . ' ' . self::$lang['date.seconds'];
		elseif ($minutes < 60)
			return $minutes . ' ' . ($minutes > 1 ? self::$lang['date.minutes'] : self::$lang['date.minute']);
		elseif ($hours < 24)
			return $hours . ' ' . ($hours > 1 ? self::$lang['date.hours'] : self::$lang['date.hour']);
		elseif ($days < 7)
			return $days . ' ' . ($days > 1 ? self::$lang['date.days'] : self::$lang['date.day']);
		elseif ($weeks < 4)
			return $weeks . ' ' . ($weeks > 1 ? self::$lang['date.weeks'] : self::$lang['date.week']);
		elseif ($months < 12)
			return $months . ' ' . ($months > 1 ? self::$lang['date.months'] : self::$lang['date.month']);
		else
			return $years . ' ' . ($years > 1 ? self::$lang['date.years'] : self::$lang['date.year']);
	}

	/**
	 * Returns the delay between now and the date
	 * @param Date $date
	 * @param int $referencial_timezone
	 * @return string The relative time
	 */
	public function get_date_delay($date, $referencial_timezone)
	{
		$now = new Date(Date::DATE_NOW, $referencial_timezone);

		if ($now->get_timestamp() > $date->get_timestamp())
			$time_diff = $now->get_timestamp() - $date->get_timestamp();
		else
			$time_diff = $date->get_timestamp() - $now->get_timestamp();

		$hours    = round($time_diff/3600);
		$years    = round($time_diff/29030400);

		if ($time_diff < 30) // Check if less than 30 seconds
			return self::$lang['date.instantly'];
		elseif ($hours < 24) // Check if it was today
			return self::transform_date($date->date_time->format(self::$lang['date.format.hour.minute']));
		elseif ( ($hours > 24 ) && ( $hours < 48 ) ) // Check if it was yesterday
			return self::$lang['date.yesterday'];
		elseif ($years < 1) // Check if it was this year
			return self::transform_date($date->date_time->format(self::$lang['date.format.day.month.text']));
		else
			return self::transform_date($date->date_time->format(self::$lang['date.format.day.month.year']));
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
			$date_label . '_SHORT_TEXT'       => $date->format(Date::FORMAT_DAY_MONTH_YEAR_TEXT), //The month in text format
			$date_label . '_SHORT_MONTH_TEXT' => $date->format(Date::FORMAT_DAY_MONTH_YEAR_LONG), // The Year in 4 digits
			$date_label . '_FULL'             => $date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
			$date_label . '_DAY'              => $date->get_day(), // The number of day
			$date_label . '_DAY_TEXT'         => $date->get_day_text(3), // 3 first characters of day name
			$date_label . '_DAY_FULLTEXT'     => $date->get_day_text(), // All characters of day name
			$date_label . '_DAY_MONTH'        => $date->format(Date::FORMAT_DAY_MONTH),
            $date_label . '_DAY_MONTH_TEXT'   => $date->format(Date::FORMAT_DAY_MONTH_TEXT), //The day with suffixe and the month in text format
			$date_label . '_WEEK'             => $date->get_week_number(),
			$date_label . '_MONTH'            => $date->get_month(), // The number of month
			$date_label . '_MONTH_TEXT'       => $date->get_month_text(3), // 3 first characters of month name
			$date_label . '_MONTH_FULLTEXT'   => $date->get_month_text(), // All characters of month name
			$date_label . '_YEAR'             => $date->get_year(),
			$date_label . '_HOUR_MINUTE'      => $date->format(Date::FORMAT_HOUR_MINUTE),
			$date_label . '_HOUR'             => $date->get_hours(),
			$date_label . '_MINUTE'           => $date->get_minutes(),
			$date_label . '_SECONDS'          => $date->get_seconds(),
			$date_label . '_ISO8601'          => $date->format(Date::FORMAT_ISO8601),
			$date_label . '_AGO'              => $date->format(Date::FORMAT_AGO),
			$date_label . '_SINCE'            => $date->format(Date::FORMAT_SINCE),
			$date_label . '_DELAY'            => $date->format(Date::FORMAT_DELAY),
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
		$search = array(
			'january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december',
			'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec',
			'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
			'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun',
		);
		$replace = array(
			self::$lang['date.january'], self::$lang['date.february'], self::$lang['date.march'], self::$lang['date.april'], self::$lang['date.may'], self::$lang['date.june'],
			self::$lang['date.july'], self::$lang['date.august'], self::$lang['date.september'], self::$lang['date.october'], self::$lang['date.november'], self::$lang['date.december'],
			self::$lang['date.january.short'], self::$lang['date.february.short'], self::$lang['date.march.short'], self::$lang['date.april.short'], self::$lang['date.may.short'], self::$lang['date.june.short'],
			self::$lang['date.july.short'], self::$lang['date.august.short'], self::$lang['date.september.short'], self::$lang['date.october.short'], self::$lang['date.november.short'], self::$lang['date.december.short'],
			self::$lang['date.monday'], self::$lang['date.tuesday'], self::$lang['date.wednesday'], self::$lang['date.thursday'], self::$lang['date.friday'], self::$lang['date.saturday'], self::$lang['date.sunday'],
			self::$lang['date.monday.short'], self::$lang['date.tuesday.short'], self::$lang['date.wednesday.short'], self::$lang['date.thursday.short'], self::$lang['date.friday.short'], self::$lang['date.saturday.short'], self::$lang['date.sunday.short']
		);
		return str_replace($search, $replace, TextHelper::strtolower($date));
	}
}
?>
