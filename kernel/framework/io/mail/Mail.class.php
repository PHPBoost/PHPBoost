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
	const SENDER_ADMIN = 'admin';
	
	const SENDER_USER = 'user';

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
	 * @var string Address of the mail to reply to.
	 */
	var $reply_to_mail = '';

	/**
	 * @var string The reply to mail sender name.
	 */
	var $reply_to_name = '';

	/**
	 * @var The mail headers.
	 */
	var $headers = '';

	/**
	 * @var string[] Recipients of the mail. If they are more than one, a comma separates their addresses.
	 */
	var $recipients = array();

	/**
	 * @var string[] Cc recipients of the mail. If they are more than one, a comma separates their addresses.
	 */
	var $cc_recipients = array();

	/**
	 * @var string[] Bcc recipients of the mail. If they are more than one, a comma separates their addresses.
	 */
	var $bcc_recipients = array();

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
	 * @param string $sender_name SENDER_ADMIN constante if the mail is sent by the administrator, SENDER_USER constante for user or string for other name
	 */
	public function set_sender($sender, $sender_name = self::SENDER_ADMIN)
	{
		$site_name = GeneralConfig::load()->get_site_name();
		
		if ($sender_name == self::SENDER_ADMIN || $sender_name == self::SENDER_USER)
		{
			$sender_name = $sender_name == self::SENDER_ADMIN ? LangLoader::get_message('administrator', 'user-common') : LangLoader::get_message('user', 'user-common');
		}
		
		$this->sender_name = str_replace('"', '', $site_name . ' - ' . $sender_name);

		$this->sender_mail = $sender;
	}

	/**
	 * @desc Sets the mail to reply to.
	 * @param string $reply_to The mail address to reply to.
	 * @param string $reply_to_name SENDER_ADMIN constante if the mail is sent by the administrator, SENDER_USER constante for user or string for other name
	 */
	public function set_reply_to($reply_to, $reply_to_name = '')
	{
		if ($reply_to_name == self::SENDER_ADMIN || $reply_to_name == self::SENDER_USER)
		{
			$reply_to_name = $reply_to_name == self::SENDER_ADMIN ? LangLoader::get_message('administrator', 'user-common') : LangLoader::get_message('user', 'user-common');
		}
		
		$this->reply_to_name = $reply_to_name;

		$this->reply_to_mail = $reply_to;
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

	/**
	 * @desc Adds a cc recipient to the list
	 * @param string $address The address to which the mail must be sent
	 * @param string $name Name of the recipient (facultative)
	 */
	public function add_cc_recipient($address, $name = '')
	{
		if (self::check_mail($address))
		{
			$this->cc_recipients[$address] = $name;
		}
	}

	public function clear_cc_recipients()
	{
		$this->cc_recipients = array();
	}

	/**
	 * @desc Adds a bcc recipient to the list
	 * @param string $address The address to which the mail must be sent
	 * @param string $name Name of the recipient (facultative)
	 */
	public function add_bcc_recipient($address, $name = '')
	{
		if (self::check_mail($address))
		{
			$this->bcc_recipients[$address] = $name;
		}
	}

	public function clear_bcc_recipients()
	{
		$this->bcc_recipients = array();
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
	 * @desc Returns a map associating email addresses to the corresponding names (can be empty).
	 * @return string[string]
	 */
	public function get_cc_recipients()
	{
		return $this->cc_recipients;
	}

	/**
	 * @desc Returns a map associating email addresses to the corresponding names (can be empty).
	 * @return string[string]
	 */
	public function get_bcc_recipients()
	{
		return $this->bcc_recipients;
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
		$this->set_is_html($content != strip_tags($content));
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
	 * @desc Returns the mail address to reply to.
	 * @return string the reply to mail address
	 */
	public function get_reply_to_mail()
	{
		return $this->reply_to_mail;
	}

	/**
	 * @desc Returns the reply to mail sender's name.
	 * @return string The reply to mail sender's name.
	 */
	public function get_reply_to_name()
	{
		return $this->reply_to_name;
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