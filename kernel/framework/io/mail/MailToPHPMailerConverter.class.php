<?php
/*##################################################
 *                      MailToPHPMailerConverter.class.php
 *                            -------------------
 *   begin                : March 10, 2010
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

include_once PATH_TO_ROOT . '/kernel/lib/php/phpmailer/class.phpmailer.php';

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
		$this->mailer = new PHPMailer(true);
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