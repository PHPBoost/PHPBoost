<?php
/*##################################################
 *                    AbstractPHPMailerMailService.class.php
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

abstract class AbstractPHPMailerMailService implements MailService
{
	private static $regex = '(?:[a-z0-9_!#$%&\'*+/=?^|~-]\.?){0,63}[a-z0-9_!#$%&\'*+/=?^|~-]+@(?:[a-z0-9_-]{2,}\.)+([a-z0-9_-]{2,}\.)*[a-z]{2,4}';

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

	abstract protected function set_send_settings(PHPMailer $mailer);

	public function is_mail_valid($mail_address)
	{
		return preg_match($this->get_mail_checking_regex(), $mail_address);
	}

	public function get_mail_checking_regex()
	{
		return '`^' . self::$regex . '$`i';
	}

	public function get_mail_checking_raw_regex()
	{
		return self::$regex;
	}
}

?>