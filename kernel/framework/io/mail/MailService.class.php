<?php
/**
 * @package     IO
 * @subpackage  Mail
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 17
 * @since       PHPBoost 3.0 - 2010 03 08
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface MailService
{
	/**
	 * Sends the mail. Throws an exception if the mail cannot be sent.
	 * @param Mail $mail The mail to send
	 * @throws IOException if the mail couldn't be sent
	 */
	function send(Mail $mail): void;

	/**
	 * Tries to send the mail but doesn't throw any error whether the mail cannot be sent,
	 * it only returns false.
	 * @param Mail $mail The mail to send
	 * @return boolean True if the mail could be sent, false otherwise.
	 */
	function try_to_send(Mail $mail): bool;

	/**
	 * @deprecated
	 * Sends the mail.
	 * @param string $mail_to The mail recipients' address.
	 * @param string $mail_subject The mail subject.
	 * @param string $mail_content content of the mail
	 * @param string $mail_from The mail sender's address.
	 * @param string $sender_name The mail sender's name. If you don't use this parameter, the name of the site administrator will be taken.
	 * @return bool True if the mail could be sent, false otherwise.
	 */
	function send_from_properties(string $mail_to, string $mail_subject, string $mail_content, string $mail_from = '', string $sender_name = 'admin'): bool;

	/**
	 * Check whether the mail address is valid, it respects the mail RFC
	 * @param string $mail_address
	 * @return boolean true if the mail is valid, false otherwise
	 */
	function is_mail_valid($mail_address): bool;

	/**
	 * Return the RFC mail regex.
	 * @return string the mail regex
	 */
	function get_mail_checking_regex(): string;

	/**
	 * Return the RFC mail regex without delimiters, it's commonly used for compatibility with javascript regex.
	 * @return string the mail regex without delimiters
	 */
	function get_mail_checking_raw_regex(): string;
}
?>
