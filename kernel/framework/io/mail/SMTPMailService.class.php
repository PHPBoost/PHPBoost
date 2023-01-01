<?php
/**
 * @package     IO
 * @subpackage  Mail
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 06 19
 * @since       PHPBoost 3.0 - 2010 03 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

include_once PATH_TO_ROOT . '/kernel/lib/php/phpmailer/src/PHPMailer.php';

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

	protected function set_send_settings(PHPMailer\PHPMailer\PHPMailer $mailer)
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
