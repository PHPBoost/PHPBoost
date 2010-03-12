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
 * @package io
 * @subpackage mail
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
	 * @return bool True, if the mail sender address is correct, false otherwise.
	 */
	public function set_sender($sender, $sender_name = 'admin')
	{
		global $LANG, $CONFIG;
		$this->sender_name = str_replace('"', '', $CONFIG['site_name'] . ' - ' . ($sender_name == 'admin' ? $LANG['admin'] : $LANG['user']));

		if (self::check_validity($sender))
		{
			$this->sender_mail = $sender;
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * @desc Adds a recipient to the list
	 * @param string $address The address to which the mail must be sent
	 * @param string $name Name of the recipient (facultative)
	 */
	public function add_recipient($address, $name = '')
	{
		if (self::check_validity($address))
		{
			$this->recipients[$address] = $name;
		}
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

	/**
	 * @desc Sends the mail.
	 * @deprecated
	 * @param string $mail_to The mail recipients' address.
	 * @param string $mail_subject The mail subject.
	 * @param string $mail_content content of the mail
	 * @param string $mail_from The mail sender's address.
	 * @param string $mail_header The header you want to specify (it you don't specify it, it will be generated automatically).
	 * @param string $sender_name The mail sender's name. If you don't use this parameter, the name of the site administrator will be taken.
	 * @return bool True if the mail could be sent, false otherwise.
	 */
	public function send_from_properties($mail_to, $mail_subject, $mail_content, $mail_from, $mail_header = null, $sender_name = 'admin')
	{
		// Initialization of the mail properties
		$recipient = $this->add_recipient($mail_to);
		$sender = $this->set_sender($mail_from, $sender_name);
		if (!$recipient || !$sender)
		{
			return false;
		}
		 
		$this->set_subject($mail_subject);
		$this->set_content($mail_content);

		$this->set_headers($mail_header);

		// Let's send the mail
		return $this->send();
	}

	/**
	 * @desc Sends the mail.
	 * @return bool True if the mail could be sent, false otherwise.
	 */
	public function send()
	{
		if (empty($this->headers))
		{
			$this->generate_headers();
		}

		$recipients = trim(implode(', ', $this->recipients), ', ');
		return @mail($recipients, $this->subject, $this->content, $this->headers);
	}

	/**
	 * @desc Checks that an email address has a correct form.
	 * @return bool True if it's valid, false otherwise.
	 */
	public static function check_validity($mail_address)
	{
		return (bool)preg_match('`^(?:[a-z0-9_!#$%&\'*+/=?^|~-]\.?){0,63}[a-z0-9_!#$%&\'*+/=?^|~-]+@(?:[a-z0-9_-]{2,}\.)+([a-z0-9_-]{2,}\.)*[a-z]{2,4}$`i', $mail_address);
	}

	// TODO remove
	/**
	 * @access protected
	 * @desc Generates the mail headers.
	 */
	private function generate_headers()
	{
		global $LANG;

		$this->header = '';

		//Sender
		$this->add_header_field('From', '"' . $this->sender_name . ' ' . HOST . '" <' . $this->sender_mail . '>');

		//Recipients
		$recipients = '';
		$nb_recipients = count($this->recipients);
		for ($i = 0; $i < $nb_recipients; $i++)
		{
			$recipients .= '"' . $this->recipients[$i] . '" <' . $this->recipients[$i] . '>';
			if ($i < $nb_recipients - 1)
			{
				$recipients .= ', ';
			}
		}

		$this->add_header_field('MIME-Version',  '1.0');
		$this->add_header_field('Content-type', $this->format . '; charset=ISO-8859-1');
	}


	/**
	 * @desc add the current coupe <code>$field</code> and <code>$value</code> to the mail headers
	 * @param string $field the header field
	 * @param string $value the header value
	 */
	private function add_header_field($field, $value)
	{
		$this->headers .= wordwrap($field . ': ' . $value, 78, "\n ") . self::CRLF;
	}
}

?>
