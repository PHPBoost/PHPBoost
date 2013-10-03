<?php
/*##################################################
 *                           MailService.class.php
 *                            -------------------
 *   begin                : March 8, 2010
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

interface MailService
{
	/**
	 * @desc Sends the mail. Throws an exception if the mail cannot be sent.
	 * @param Mail $mail The mail to send
	 * @throws IOException if the mail couldn't be sent
	 */
	function send(Mail $mail);

	/**
	 * @desc Tries to send the mail but doesn't throw any error whether the mail cannot be sent,
	 * it only returns false.
	 * @param Mail $mail The mail to send
	 * @return boolean True if the mail could be sent, false otherwise.
	 */
	function try_to_send(Mail $mail);
	
	/**
	 * @deprecated
	 * @desc Sends the mail.
	 * @param string $mail_to The mail recipients' address.
	 * @param string $mail_subject The mail subject.
	 * @param string $mail_content content of the mail
	 * @param string $mail_from The mail sender's address.
	 * @param string $sender_name The mail sender's name. If you don't use this parameter, the name of the site administrator will be taken.
	 * @return bool True if the mail could be sent, false otherwise.
	 */
	function send_from_properties($mail_to, $mail_subject, $mail_content, $mail_from = '', $sender_name = 'admin');

	/**
	 * @desc Check whether the mail address is valid, it respects the mail RFC
	 * @param string $mail_address
	 * @return boolean true if the mail is valid, false otherwise
	 */
	function is_mail_valid($mail_address);

	/**
	 * Return the RFC mail regex.
	 * @return string the mail regex
	 */
	function get_mail_checking_regex();

	/**
	 * Return the RFC mail regex without delimiters, it's commonly used for compatibility with javascript regex.
	 * @return string the mail regex without delimiters
	 */
	function get_mail_checking_raw_regex();
}
?>