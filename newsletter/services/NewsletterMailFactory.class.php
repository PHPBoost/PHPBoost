<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 02 01
*/

class NewsletterMailFactory
{
	public static function send_mail(array $subscribers, $language_type, $sender, $subject, $contents)
	{
		$name_class = self::determine_class($language_type);

		$instance_class = new $name_class();
		return $instance_class->send_mail($subscribers, $sender, $subject, $contents);
	}

	public static function display_mail($language_type, $subject, $contents)
	{
		$name_class = self::determine_class($language_type);

		$instance_class = new $name_class();
		return $instance_class->display_mail($subject, $contents);
	}

	public static function parse_contents($language_type, $contents)
	{
		$name_class = self::determine_class($language_type);

		$instance_class = new $name_class();
		return $instance_class->parse_contents($contents);
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
