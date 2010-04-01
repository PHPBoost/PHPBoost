<?php
/*##################################################
 *                    AbstractPHPMailerMailSender.class.php
 *                            -------------------
 *   begin                : March 9, 2010
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

import('/kernel/lib/phpmailer/class.phpmailer', PHP_IMPORT);

abstract class AbstractPHPMailerMailSender implements MailSender
{
	/**
	 * @var PHPMailer
	 */
	private $mailer;

	public function send(Mail $mail)
	{
		$converter = new MailToPHPMailerConverter();
		$this->mailer = $converter->convert($mail);
		$this->set_send_settings($this->mailer);
		$this->mailer->Send();
	}

	abstract protected function set_send_settings(PHPMailer $mailer);
}

?>