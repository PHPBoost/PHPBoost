<?php
/**
 * This class allows you to send mails without having to deal with the mail headers and parameters.
 * @package     IO
 * @subpackage  Mail
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 17
 * @since       PHPBoost 3.0 - 2010 03 08
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Mail
{
	const SENDER_ADMIN = 'admin';

	const SENDER_USER = 'user';

	/** object of the mail */
	public string $subject = '';
	/** content of the mail */
	public string $content = '';
	/** Address of the mail sender. */
	public string $sender_mail = '';
	/** The mail sender name. */
	public string $sender_name = '';
	/** Address of the mail to reply to. */
	public string $reply_to_mail = '';
	/** The reply to mail sender name. */
	public string $reply_to_name = '';
	/** mail headers. */
	public string $headers = '';
	/** Recipients of the mail. If they are more than one, a comma separates their addresses. */
	public array $recipients = [];
	/** Cc recipients of the mail. If they are more than one, a comma separates their addresses. */
	public array $cc_recipients = [];
	/** Bcc recipients of the mail. If they are more than one, a comma separates their addresses. */
	public array $bcc_recipients = [];
	/** Attachments of the mail. If they are more than one, a comma separates their addresses. */
	public array $attachments = [];
	/** Tells whether the content contains HTML code */
	public bool $is_html = false;

	/**
	 * Builds a Mail subject.
	 */
	public function __construct()
	{
	}

	/**
	 * Sets the mail sender.
	 * @param string $sender The mail sender address.
	 * @param string $sender_name SENDER_ADMIN constante if the mail is sent by the administrator, SENDER_USER constante for user or string for other name
	 */
	public function set_sender(string $sender, string $sender_name = self::SENDER_ADMIN): void
	{
		$site_name = GeneralConfig::load()->get_site_name();

		if ($sender_name == self::SENDER_ADMIN || $sender_name == self::SENDER_USER)
		{
			$sender_name = $sender_name == self::SENDER_ADMIN ? LangLoader::get_message('user.administrator', 'user-lang') : LangLoader::get_message('user.user', 'user-lang');
		}

		$this->sender_name = str_replace('"', '', $site_name . ' - ' . $sender_name);

		$this->sender_mail = $sender;
	}

	/**
	 * Sets the mail to reply to.
	 * @param string $reply_to The mail address to reply to.
	 * @param string $reply_to_name SENDER_ADMIN constante if the mail is sent by the administrator, SENDER_USER constante for user or string for other name
	 */
	public function set_reply_to(string $reply_to, string $reply_to_name = ''): void
	{
		if ($reply_to_name == self::SENDER_ADMIN || $reply_to_name == self::SENDER_USER)
		{
			$reply_to_name = $reply_to_name == self::SENDER_ADMIN ? LangLoader::get_message('user.administrator', 'user-lang') : LangLoader::get_message('user.user', 'user-lang');
		}

		$this->reply_to_name = $reply_to_name;

		$this->reply_to_mail = $reply_to;
	}

	/**
	 * Adds a recipient to the list
	 * @param string $address The address to which the mail must be sent
	 * @param string $name Name of the recipient (facultative)
	 */
	public function add_recipient(string $address, string $name = ''): void
	{
		if (self::check_mail($address))
		{
			$this->recipients[$address] = $name;
		}
	}

	public function clear_recipients(): void
	{
		$this->recipients = [];
	}

	/**
	 * Adds a cc recipient to the list
	 * @param string $address The address to which the mail must be sent
	 * @param string $name Name of the recipient (facultative)
	 */
	public function add_cc_recipient(string $address, string $name = ''): void
	{
		if (self::check_mail($address))
		{
			$this->cc_recipients[$address] = $name;
		}
	}

	public function clear_cc_recipients(): void
	{
		$this->cc_recipients = [];
	}

	/**
	 * Adds a bcc recipient to the list
	 * @param string $address The address to which the mail must be sent
	 * @param string $name Name of the recipient (facultative)
	 */
	public function add_bcc_recipient(string $address, string $name = ''): void
	{
		if (self::check_mail($address))
		{
			$this->bcc_recipients[$address] = $name;
		}
	}

	public function clear_bcc_recipients(): void
	{
		$this->bcc_recipients = [];
	}

	private static function check_mail(string $mail): bool
	{
		return AppContext::get_mail_service()->is_mail_valid($mail);
	}

	/**
	 * Returns a map associating email addresses to the corresponding names (can be empty).
	 * @return string[]
	 */
	public function get_recipients(): array
	{
		return $this->recipients;
	}

	/**
	 * Returns a map associating email addresses to the corresponding names (can be empty).
	 * @return string[]
	 */
	public function get_cc_recipients(): array
	{
		return $this->cc_recipients;
	}

	/**
	 * Returns a map associating email addresses to the corresponding names (can be empty).
	 * @return string[]
	 */
	public function get_bcc_recipients(): array
	{
		return $this->bcc_recipients;
	}

	/**
	 * Returns email attachments list.
	 * @return string[]
	 */
	public function get_attachments(): array
	{
		return $this->attachments;
	}

	/**
	 * Sets the mail attachments
	 * @param string[] $attachments Mail attachments
	 */
	public function set_attachments(array $attachments): void
	{
		$this->attachments = $attachments;
	}

	/**
	 * Sets the mail subject
	 * @param string $subject Mail subject
	 */
	public function set_subject(string $subject): void
	{
		$this->subject = $subject;
	}

	/**
	 * The mail content.
	 * @param string $content The mail content
	 */
	public function set_content(string $content): void
	{
		$this->content = $content;
		$this->set_is_html($content != strip_tags($content));
	}

	/**
	 * Sets the headers. Forces them, they won't be generated automatically.
	 * @param string $headers The mail headers.
	 */
	public function set_headers(string $headers): void
	{
		$this->headers = $headers;
	}

	/**
	 * Returns the mail address of the sender.
	 * @return string the sender's mail address
	 */
	public function get_sender_mail(): string
	{
		return $this->sender_mail;
	}

	/**
	 * Returns the mail sender's name.
	 * @return string The mail sender's name.
	 */
	public function get_sender_name(): string
	{
		return $this->sender_name;
	}

	/**
	 * Returns the mail address to reply to.
	 * @return string the reply to mail address
	 */
	public function get_reply_to_mail(): string
	{
		return $this->reply_to_mail;
	}

	/**
	 * Returns the reply to mail sender's name.
	 * @return string The reply to mail sender's name.
	 */
	public function get_reply_to_name(): string
	{
		return $this->reply_to_name;
	}

	/**
	 * Returns the mail subject.
	 * @return string The mail subject.
	 */
	public function get_subject(): string
	{
		return $this->subject;
	}

	/**
	 * Returns the mail content.
	 * @return string The mail content.
	 */
	public function get_content(): string
	{
		return $this->content;
	}

	public function set_is_html(bool $is): void
	{
		$this->is_html = $is;
	}

	public function is_html(): bool
	{
		return $this->is_html;
	}
}
?>
