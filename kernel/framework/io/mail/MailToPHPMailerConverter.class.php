<?php
/**
 * @package     IO
 * @subpackage  Mail
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 10 28
 * @since       PHPBoost 3.0 - 2010 03 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

include_once PATH_TO_ROOT . '/kernel/lib/php/phpmailer/src/Exception.php';
include_once PATH_TO_ROOT . '/kernel/lib/php/phpmailer/src/PHPMailer.php';
include_once PATH_TO_ROOT . '/kernel/lib/php/phpmailer/src/SMTP.php';
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailToPHPMailerConverter
{
	/**
	 * @var PHPMailer
	 */
	private $mailer;
	/**
	 * @var Mail
	 */
	private $mail_to_send;

	/**
	 * @param Mail $mail
	 * @return PHPMailer
	 */
	public function convert(Mail $mail)
	{
		$this->mail_to_send = $mail;
		$this->mailer = new PHPMailer();
		$this->mailer->CharSet = 'utf-8';
		$this->mailer->SMTPAutoTLS = false;
		$this->convert_mail();
		return $this->mailer;
	}

	private function convert_mail()
	{
		foreach ($this->mail_to_send->get_recipients() as $recipient => $name)
		{
			$this->mailer->AddAddress($recipient, $name);
		}

		// cc
		foreach ($this->mail_to_send->get_cc_recipients() as $recipient => $name)
		{
			$this->mailer->AddCC($recipient, $name);
		}

		// bcc
		foreach ($this->mail_to_send->get_bcc_recipients() as $recipient => $name)
		{
			$this->mailer->AddBCC($recipient, $name);
		}

		// from
		$this->mailer->SetFrom($this->mail_to_send->get_sender_mail(), $this->mail_to_send->get_sender_name());
		$this->mailer->AddReplyTo($this->mail_to_send->get_reply_to_mail() ? $this->mail_to_send->get_reply_to_mail() : $this->mail_to_send->get_sender_mail(), $this->mail_to_send->get_reply_to_name() ? $this->mail_to_send->get_reply_to_name() : $this->mail_to_send->get_sender_name());

		$this->mailer->Subject = $this->mail_to_send->get_subject();

		// content
		$this->convert_content();
	}

	private function convert_content()
	{
		if ($this->mail_to_send->is_html())
		{
			$this->mailer->MsgHTML($this->mail_to_send->get_content());
		}
		else
		{
			$this->mailer->Body = $this->mail_to_send->get_content();
		}
	}
}
?>
