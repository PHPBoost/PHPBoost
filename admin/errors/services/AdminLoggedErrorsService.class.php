<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 02
 * @since       PHPBoost 6.1 - 2026 10 01
*/

class AdminLoggedErrorsService
{
    /** This class is triggered as soon as an error is recorded  */
	public static function send_alert()
	{
        $config = ErrorsConfig::load();
        // get error.log location
        $file_path = PATH_TO_ROOT . '/cache/error.log';
        // If error.log doesn't exist, we stop
        if (!file_exists($file_path))
            return;

        // Get the config delay and the time/date of now
        $delay = $config->get_delay() * 60;
        $now = new Date();

        // Get all error classes between now and the delay
        $errclasses = [];
        foreach (self::get_errors_list() as $errors)
        {
            $errdate = new Date($errors['errdate']);
            if ($now->get_timestamp() - $errdate->get_timestamp() < $delay) {
                $errclasses[] = $errors['errclass'];
            }
        }

        // Get all alert levels from the config
        $levels = TextHelper::deserialize($config->get_alert_level());
        if (!is_array($levels))
        {
            $levels = [];
        }

        // Check if an error class belong to levels
        $flag = false;
        foreach ($errclasses as $errclass)
        {
            if (in_array($errclass, $levels)) {
                $flag = true;
                break;
            }
        }

        // Get list of recipents from the config or from the general config
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
            $recipients[] = MailServiceConfig::load()->get_administrators_mails();
        }

        if ($config->get_send_alerts() && $flag)
        {
            $sender  = MailServiceConfig::load()->get_default_mail_sender();
            $subject = LangLoader::get_message('configuration.email.subject', 'configuration-lang');
            $content = LangLoader::get_message('configuration.email.content', 'configuration-lang');
            self::send_mail($recipients, $sender, $subject, $content);
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

    public function send_mail(array $recipients, string $sender, string $subject, string $content)
	{
		foreach ($recipients as $recipient)
		{
            $mail = new Mail();
            $mail->set_sender($sender);
            $mail->set_subject($subject);
            $mail->set_content($content);

            $mail->add_recipient($recipient);

            AppContext::get_mail_service()->try_to_send($mail);
		}
	}
}
