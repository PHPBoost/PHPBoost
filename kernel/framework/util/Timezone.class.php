<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2015 01 01
 * @since       PHPBoost 3.0 - 2010 10 30
 * @contributor Kevin MASSY <reidlos@phpboost.com>
*/

class Timezone
{
    public const SERVER_TIMEZONE = 1;
    public const SITE_TIMEZONE = 2;
    public const USER_TIMEZONE = 3;

    /**
     * @var DateTimeZone|null The server timezone
     */
    private static ?DateTimeZone $server_timezone = null;

    /**
     * @var DateTimeZone|null The site timezone
     */
    private static ?DateTimeZone $site_timezone = null;

    /**
     * @var DateTimeZone|null The user timezone
     */
    private static ?DateTimeZone $user_timezone = null;

    /**
     * Returns the list of supported timezones.
     *
     * @return array The list of supported timezones
     */
    public static function get_supported_timezones(): array
    {
        return DateTimeZone::listIdentifiers();
    }

    /**
     * Returns the PHP timezone corresponding to the timezone code.
     *
     * @param int $timezone_code SERVER_TIMEZONE, SITE_TIMEZONE, or USER_TIMEZONE
     * @return DateTimeZone The PHP timezone
     */
    public static function get_timezone(int $timezone_code): DateTimeZone
    {
        switch ($timezone_code)
        {
            case self::SERVER_TIMEZONE:
                return self::get_server_timezone();
            case self::SITE_TIMEZONE:
                return self::get_site_timezone();
            default:
                return self::get_user_timezone();
        }
    }

    /**
     * Returns the server timezone.
     *
     * @return DateTimeZone The server timezone
     */
    public static function get_server_timezone(): DateTimeZone
    {
        if (self::$server_timezone === null)
        {
            self::$server_timezone = new DateTimeZone(date_default_timezone_get());
        }
        return self::$server_timezone;
    }

    /**
     * Returns the site timezone.
     *
     * @return DateTimeZone The site timezone
     */
    public static function get_site_timezone(): DateTimeZone
    {
        if (self::$site_timezone === null)
        {
            self::$site_timezone = new DateTimeZone(GeneralConfig::load()->get_site_timezone());
        }
        return self::$site_timezone;
    }

    /**
     * Returns the user timezone.
     *
     * @return DateTimeZone The user timezone
     */
    public static function get_user_timezone(): DateTimeZone
    {
        if (self::$user_timezone === null)
        {
            self::$user_timezone = new DateTimeZone(AppContext::get_current_user()->get_timezone());
        }
        return self::$user_timezone;
    }
}
?>
