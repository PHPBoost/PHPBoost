<?php
/*##################################################
 *                           SMTPMailService.class.php
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

class SMTPMailService extends AbstractPHPMailerMailService
{
	/**
	 * @var SMTPConfiguration
	 */
	private $configuration;

	public function __construct(SMTPConfiguration $configuration)
	{
		$this->configuration = $configuration;
	}

	protected function set_send_settings(PHPMailer $mailer)
	{
		$mailer->IsSMTP();
		$mailer->SMTPDebug = 0;
		$mailer->SMTPAuth = true;
		$mailer->Timeout = 1;
		$auth_mode = $this->configuration->get_auth_mode();
		
		if (!empty($auth_mode))
		{
			$mailer->SMTPSecure = $this->configuration->get_auth_mode();
		}
		
		$mailer->Host = $this->configuration->get_host();
		$mailer->Port = $this->configuration->get_port();
		$mailer->Username = $this->configuration->get_login();
		$mailer->Password = $this->configuration->get_password();
	}
}
?>