<?php
/*##################################################
 *                              Mail.class.php
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

/**
 * @package {@package}
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class allows you to send mails without having to deal with the mail headers and parameters.
 */
class Mail
{
	// TODO remove
	const CRLF = "\r\n";

	/**
	 * @var sting object of the mail
	 */
	var $subject = '';

	/**
	 * @var string content of the mail
	 */
	var $content = '';

	/**
	 * @var string Address of the mail sender.
	 */
	var $sender_mail = '';

	/**
	 * @var string The mail sender name.
	 */
	var $sender_name = '';

	/**
	 * @var The mail headers.
	 */
	var $headers = '';

	/**
	 * @var string[] Recipients of the mail. If they are more than one, a comma separates their addresses.
	 */
	var $recipients = array();

	/**
	 * @var string Tells whether the content contains HTML code
	 */
	var $is_html = false;


	/**
	 * @desc Builds a Mail subject.
	 */
	public function __construct()
	{
	}

	/**
	 * @desc Sets the mail sender.
	 * @param string $sender The mail sender address.
	 * @param string $sender_name 'admin' if the mail is sent by the administrator, 'user' otherwise.
	 */
	public function set_sender($sender, $sender_name = 'admin')
	{
		global $LANG;
		$site_name = GeneralConfig::load()->get_site_name();
		$this->sender_name = str_replace('"', '', $site_name . ' - ' . ($sender_name == 'admin' ? $LANG['admin'] : $LANG['user']));

		$this->sender_mail = $sender;
	}

	/**
	 * @desc Adds a recipient to the list
	 * @param string $address The address to which the mail must be sent
	 * @param string $name Name of the recipient (facultative)
	 */
	public function add_recipient($address, $name = '')
	{
		if (self::check_mail($address))
		{
			$this->recipients[$address] = $name;
		}
	}

	public function clear_recipients()
	{
		$this->recipients = array();
	}

	private static function check_mail($mail)
	{
		return AppContext::get_mail_service()->is_mail_valid($mail);
	}

	/**
	 * @desc Returns a map associating email addresses to the corresponding names (can be empty).
	 * @return string[string]
	 */
	public function get_recipients()
	{
		return $this->recipients;
	}

	/**
	 * @desc Sets the mail subject
	 * @param string $subject Mail subject
	 */
	public function set_subject($subject)
	{
		$this->subject = $subject;
	}

	/**
	 * @desc The mail content.
	 * @param string $content The mail content
	 */
	public function set_content($content)
	{
		$this->content = $content;
	}

	/**
	 * @desc Sets the headers. Forces them, they won't be generated automatically.
	 * @param string $headers The mail headers.
	 */
	public function set_headers($headers)
	{
		$this->headers = $headers;
	}

	/**
	 * @desc Returns the mail address of the sender.
	 * @return string the sender's mail address
	 */
	public function get_sender_mail()
	{
		return $this->sender_mail;
	}

	/**
	 * @desc Returns the mail sender's name.
	 * @return string The mail sender's name.
	 */
	public function get_sender_name()
	{
		return $this->sender_name;
	}

	/**
	 * @desc Returns the mail subject.
	 * @return string The mail subject.
	 */
	public function get_subject()
	{
		return $this->subject;
	}

	/**
	 * @desc Returns the mail content.
	 * @return string The mail content.
	 */
	public function get_content()
	{
		return $this->content;
	}

	public function set_is_html($is)
	{
		$this->is_html = $is;
	}

	public function is_html()
	{
		return $this->is_html;
	}
}

?>
