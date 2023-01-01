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

class DefaultMailService extends AbstractPHPMailerMailService
{
	protected function set_send_settings(PHPMailer\PHPMailer\PHPMailer $mailer)
	{
		$mailer->IsMail();
	}
}
?>
