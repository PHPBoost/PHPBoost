<?php
/**
 * This class provides you utilities for emails.
 * @package     IO
 * @subpackage  Mail
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 15
 * @since       PHPBoost 3.0 - 2010 04 12
 * @contributor Regis VIARRE <crowkait@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class MailUtil
{
	private static $regex = '(?:[a-z0-9_!#$%&\'*+/=?^|~-]\.?){0,63}[a-z0-9_!#$%&\'*+/=?^|~-]+@(?:[a-z0-9_-]{2,}\.)+([a-z0-9_-]{2,}\.)*[a-z]{2,4}';

	/**
	 * Check whether the mail address is valid, it respects the mail RFC
	 * @param string $mail_address
	 * @return boolean true if the mail is valid, false otherwise
	 */
	public static function is_mail_valid($mail_address)
	{
		return (bool)preg_match(self::get_mail_checking_regex(), $mail_address);
	}

	/**
	 * Return the RFC mail regex.
	 * @return string the mail regex
	 */
	public static function get_mail_checking_regex()
	{
		return '`^' . self::$regex . '$`iu';
	}

	/**
     * Return the RFC mail regex without delimiters, it's commonly used for compatibility with javascript regex.
     * @return string the mail regex without delimiters
     */
	public static function get_mail_checking_raw_regex()
	{
		return self::$regex;
	}
}
?>
