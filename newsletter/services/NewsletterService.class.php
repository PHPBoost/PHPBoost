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
	
	public static function update_subscribtions_member_registered(Array $streams, $user_id)
	{
		if (NewsletterDAO::verificate_exist_user_id($user_id))
		{
			NewsletterDAO::update_subscribtions_member_registered($user_id, $streams);
		}
		else
		{
			NewsletterDAO::insert_subscribtions_member_registered($user_id, $streams);
		}
	}
	
	public static function update_subscribtions_visitor(Array $streams, $mail)
	{
		if (NewsletterDAO::verificate_exist_mail($mail))
		{
			NewsletterDAO::update_subscribtions_visitor($mail, $streams);
		}
		else
		{
			NewsletterDAO::insert_subscribtions_visitor($mail, $streams);
		}
	}
	
	//TODO change for a new fonction
	public static function unsubscribe_member_all($user_id)
	{
		if (NewsletterDAO::verificate_exist_user_id($user_id))
		{
			NewsletterDAO::unsubscriber_all_by_user_id($user_id);
		}
	}
	
	//TODO change for a new fonction
	public static function unsubscribe_visitor_all($mail)
	{
		if (NewsletterDAO::verificate_exist_mail($mail))
		{
			NewsletterDAO::unsubscriber_all_by_mail($mail);
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