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
	
	public static function add_newsletter(array $streams, $subject, $contents, $language_type)
	{
		$streams_cache = NewsletterStreamsCache::load()->get_streams();
		foreach ($streams_cache as $id => $values)
		{
			if (in_array($id, $streams))
			{
				//Send mail
				NewsletterMailFactory::send_mail($values['subscribers'], $language_type, NewsletterConfig::load()->get_mail_sender(), $subject, $contents);
				//Add archive
				NewsletterDAO::add_archive($id, $subject, $contents, $language_type);
			}
		}
	}
	
	public static function display_newsletter($id_archive)
	{
		$row = PersistenceContext::get_querier()->select_single_row(NewsletterSetup::$newsletter_table_archives, array('*'), "WHERE id = '". $id_archive ."'");
		
		return NewsletterMailFactory::display_mail($row['language_type'], $row['subject'], $row['contents']);
	}
	
	public static function update_subscribtions_member_registered(Array $streams, $user_id)
	{
		if (NewsletterDAO::user_id_existed($user_id))
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
		if (NewsletterDAO::mail_existed($mail))
		{
			NewsletterDAO::update_subscribtions_visitor($mail, $streams);	
		}
		else
		{
			NewsletterDAO::insert_subscribtions_visitor($mail, $streams);
		}
	}
	
	public static function unsubscriber_all_streams_member($user_id)
	{
		if (NewsletterDAO::user_id_existed($user_id))
		{
			NewsletterDAO::unsubscriber_all_streams_member($user_id);
		}
	}

	public static function unsubscriber_all_streams_visitor($mail)
	{
		if (NewsletterDAO::mail_existed($mail))
		{
			NewsletterDAO::unsubscriber_all_streams_visitor($mail);
		}
	}
	
	public static function get_id_streams_member($user_id)
	{
		$streams = array();
		$newsletter_streams_cache = NewsletterStreamsCache::load()->get_streams();
		foreach ($newsletter_streams_cache as $id => $value)
		{
			if (array_key_exists($user_id, $value['subscribers']))
			{
				$streams[] = (string)$id;
			}
		}
		return $streams;
	}
	
	public static function get_name_streams_member($user_id)
	{
		$streams = array();
		$newsletter_streams_cache = NewsletterStreamsCache::load()->get_streams();
		foreach ($newsletter_streams_cache as $id => $value)
		{
			if (array_key_exists($user_id, $value['subscribers']))
			{
				$streams[] = $value['name'];
			}
		}
		return $streams;
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