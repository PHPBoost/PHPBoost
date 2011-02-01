<?php
/*##################################################
 *                        NewsletterMailFactory.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class NewsletterMailFactory
{
	public static function send_mail(NewsletterMailService $newsletter_mail_config)
	{
		$name_class = self::determine_class($newsletter_mail_config);
		
		$instance_class = new $name_class();
		return $instance_class->send_mail($newsletter_mail_config);
	}
	
	public static function display_mail(NewsletterMailService $newsletter_mail_config)
	{
		$name_class = self::determine_class($newsletter_mail_config);
		
		$instance_class = new $name_class();
		return $instance_class->display_mail($newsletter_mail_config);
	}
	
	private static function determine_class(NewsletterMailService $newsletter_mail_config)
	{
		$language_type = $newsletter_mail_config->get_language_type();
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