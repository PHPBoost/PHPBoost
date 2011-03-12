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
	private static $lang;
	public static $errors;
	
	public static function __static()
	{
		self::$lang = LangLoader::get('newsletter_common', 'newsletter');
		self::$errors = '';
	}
	
	public static function subscribe($mail, $id_cat)
	{
		if (NewsletterDAO::verificate_valid_mail($mail))
		{
			if (NewsletterDAO::get_mail_exist($mail, $id_cat))
			{
				NewsletterDAO::update_mail($mail);
			}
			else
			{
				NewsletterDAO::subscribe_mail($mail);
			}
		}
	}
	
	public static function unsubscribe($mail)
	{
		if (NewsletterDAO::verificate_valid_mail($mail))
		{
			if (NewsletterDAO::get_mail_exist($mail, $id_cat))
			{
				NewsletterDAO::unsubscribe_mail($mail);
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
}

?>