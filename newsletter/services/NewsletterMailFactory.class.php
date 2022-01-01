<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 24
 * @since       PHPBoost 3.0 - 2011 02 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsletterMailFactory
{
	public static function send_mail(array $subscribers, $language_type, $sender, $subject, $content)
	{
		$name_class = self::determine_class($language_type);

		$instance_class = new $name_class();
		return $instance_class->send_mail($subscribers, $sender, $subject, $content);
	}

	public static function display_mail($language_type, $subject, $content)
	{
		$name_class = self::determine_class($language_type);

		$instance_class = new $name_class();
		return $instance_class->display_mail($subject, $content);
	}

	public static function parse_content($language_type, $content)
	{
		$name_class = self::determine_class($language_type);

		$instance_class = new $name_class();
		return $instance_class->parse_content($content);
	}

	private static function determine_class($language_type)
	{
		$language_type = $language_type;
		switch ($language_type)
		{
			case NewsletterMailService::TEXT_LANGUAGE:
				return 'TextNewsletterMail';
				break;
			case NewsletterMailService::BBCODE_LANGUAGE:
				return 'BBCodeNewsletterMail';
				break;
			case NewsletterMailService::HTML_LANGUAGE:
				return 'HTMLNewsletterMail';
				break;
			default:
				return 'TextNewsletterMail';
		}
	}
}
?>
