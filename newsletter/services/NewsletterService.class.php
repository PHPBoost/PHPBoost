<?php
/*##################################################
 *                        NewsletterService.class.php
 *                            -------------------
 *   begin                : February 8, 2011
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

class NewsletterService
{
	private static $db_querier;
	private static $lang;
	public static $errors;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
		self::$lang = LangLoader::get('newsletter_common', 'newsletter');
		self::$errors = '';
	}
	
	
	public static function subscribe($mail)
	{
		if (self::verificate_valid_mail($mail))
		{
			if (self::get_mail_exist($mail))
			{
				//Il est déjà enregistré
				self::$errors = self::$lang['newsletter.already-subscribe'];
			}
			else
			{
				self::subscribe_mail($mail);
			}
		}
	}
	
	public static function unsubscribe($mail)
	{
		if (self::verificate_valid_mail($mail))
		{
			if (self::get_mail_exist($mail))
			{
				self::unsubscribe_mail($mail);
			}
			else
			{
				//Il n'est pas enregistré
				self::$errors = self::$lang['newsletter.not-subscribe'];
			}
		}
	}
	
	public static function get_errors()
	{
		if (!empty(self::$errors))
		{
			return self::$errors;
		}
		else
		{
			return false;
		}
	}
	
	private static function verificate_valid_mail($mail)
	{
		AppContext::get_mail_service()->is_mail_valid($mail);
	}
	
	private static function get_mail_exist($mail)
	{
		return self::$db_querier->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE mail = '" . $mail_newsletter . "'") > 0 ? true : false;
	}
	
	private static function subscribe_mail($mail)
	{
		self::$db_querier->inject(
			"INSERT INTO " . NewsletterSetup::$newsletter_table_archive . " (mail)
			VALUES (:mail)", array(
                'mail' => $mail,
		));
	}
	
	private static function unsubscribe_mail($mail)
	{
		self::$db_querier->inject(
			"DELETE FROM " . NewsletterSetup::$newsletter_table_archive . "
			WHERE mail = :mail", array(
				'mail' => $mail,
		));
	}
}

?>