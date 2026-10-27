<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 27
 * @since       PHPBoost 6.1 - 2026 10 01
*/

class AdminLoggedErrorsService
{
    /** This class is triggered as soon as an error is recorded  */
	public static function send_alert()
	{
        $config = ErrorsConfig::load();
        if (!$config->get_send_alerts())
            return;

        $delay = $config->get_delay() * 60;
        $now = time();
        if ($delay > 0 && $now - (int)$config->get_last_alert_timestamp() < $delay)
            return;

        $errors = self::get_errors_list();
        if (empty($errors))
            return;

        $levels = TextHelper::deserialize($config->get_alert_level());
        if (!is_array($levels))
        {
            $levels = [];
        }

        $level_list = [];
        foreach ($levels as $level)
        {
            $level_list[] = $level->get_id();
        }

        if (!in_array($errors[0]['errclass'], $level_list))
            return;

        $recipients = [];
        if (!empty($config->get_email_list()))
        {
            $emails = explode(',', $config->get_email_list());
            foreach ($emails as $email)
            {
                $recipients[] = $email;
            }
        }
        else
        {
            $recipients = MailServiceConfig::load()->get_administrators_mails();
        }

        $sender  = MailServiceConfig::load()->get_default_mail_sender();
        $subject = LangLoader::get_message('configuration.email.subject', 'configuration-lang');
        $content = LangLoader::get_message('configuration.email.content', 'configuration-lang');
        if (self::send_mail($recipients, $sender, $subject, $content))
        {
            $config->set_last_alert_timestamp($now);
            ErrorsConfig::save();
        }
    }

	public static function get_errors_list()
	{
		$array_errinfo = [];
		$file_path = PATH_TO_ROOT . '/cache/error.log';

		if (is_file($file_path) && is_readable($file_path)) // Readable file
		{
			$handle = @fopen($file_path, 'r');
			if ($handle)
			{
				$i = 1;
				while (!feof($handle))
				{
					$buffer = fgets($handle);
					switch ($i)
					{
						case 1:
						$errinfo['errdate'] = $buffer;
						break;
						case 2:
						$errinfo['errno'] = $buffer;
						break;
						case 3:
						$errinfo['errmsg'] = $buffer;
						break;
						case 4:
						$errinfo['errstacktrace'] = $buffer;
						$i = 0;
						$array_errinfo[] = [
							'errclass' => ErrorHandler::get_errno_class($errinfo['errno']),
							'errmsg' => $errinfo['errmsg'],
							'errstacktrace'=> $errinfo['errstacktrace'],
							'errdate' => $errinfo['errdate']
						];
						break;
					}
					$i++;
				}
				@fclose($handle);
			}
		}

		return array_reverse($array_errinfo); // Sorting in reverse order because recording in the log file
	}

    public static function send_mail(array $recipients, string $sender, string $subject, string $content)
	{
        $sent = false;
		foreach ($recipients as $recipient)
		{
            $mail = new Mail();
            $mail->set_sender($sender);
            $mail->set_subject($subject);
            $mail->set_content($content);

            $mail->add_recipient($recipient);

            if (AppContext::get_mail_service()->try_to_send($mail))
                $sent = true;
		}

        return $sent;
	}
}
