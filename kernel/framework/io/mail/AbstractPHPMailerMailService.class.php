<?php
/**
 * @package     IO
 * @subpackage  Mail
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 06 19
 * @since       PHPBoost 3.0 - 2010 03 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

include_once PATH_TO_ROOT . '/kernel/lib/php/phpmailer/src/PHPMailer.php';

abstract class AbstractPHPMailerMailService implements MailService
{
	private static $regex = '(?:[a-z0-9_!#$%&\'*+/=?^|~-]\.?){0,63}[a-z0-9_!#$%&\'*+/=?^|~-]+@(?:[a-z0-9_-]{2,}\.)+([a-z0-9_-]{2,}\.)*[a-z]{2,}';

	/**
	 * @var PHPMailer
	 */
	private $mailer;

	public function send(Mail $mail)
	{
		$converter = new MailToPHPMailerConverter();
		$this->mailer = $converter->convert($mail);
		$this->set_send_settings($this->mailer);
		try
		{
		$this->mailer->Send();
		}
		catch(Exception $ex)
		{
			throw new IOException('Mail couldn\'t be sent: ' . $ex->getMessage());
		}
	}

	public function try_to_send(Mail $mail)
	{
		try
		{
			$this->send($mail);
			return true;
		}
		catch (IOException $ex)
		{
			return false;
		}
	}

	public function send_from_properties($mail_to, $mail_subject, $mail_content, $mail_from = '', $sender_name = Mail::SENDER_ADMIN)
	{
		// Initialization of the mail properties
		$mail = new Mail();

		$mail->add_recipient($mail_to);
		if ($mail_from == '')
		{
			$mail_from = MailServiceConfig::load()->get_default_mail_sender();
		}
		$mail->set_sender($mail_from, $sender_name);
		$mail->set_subject($mail_subject);
		$mail->set_content($mail_content);

		// Let's send the mail
		return $this->try_to_send($mail);
	}

	abstract protected function set_send_settings(PHPMailer\PHPMailer\PHPMailer $mailer);

	public function is_mail_valid($mail_address)
	{
		return preg_match($this->get_mail_checking_regex(), $mail_address);
	}

	public function get_mail_checking_regex()
	{
		return '`^' . self::$regex . '$`iu';
	}

	public function get_mail_checking_raw_regex()
	{
		return self::$regex;
	}
}
?>
