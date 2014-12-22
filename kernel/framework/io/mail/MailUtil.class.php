<?php
/*##################################################
 *                              MailUtil.class.php
 *                            -------------------
 *   begin                : April 12, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
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
 * @package {@package}
 * @author Benoit Sautel <ben.popeye@phpboost.com>, Régis Viarre <crowkait@phpboost.com>
 * @desc This class provides you utilities for emails.
 */
class MailUtil
{
	private static $regex = '(?:[a-z0-9_!#$%&\'*+/=?^|~-]\.?){0,63}[a-z0-9_!#$%&\'*+/=?^|~-]+@(?:[a-z0-9_-]{2,}\.)+([a-z0-9_-]{2,}\.)*[a-z]{2,4}';

	/**
	 * @desc Check whether the mail address is valid, it respects the mail RFC
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
		return '`^' . self::$regex . '$`i';
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