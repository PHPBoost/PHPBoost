<?php
/*##################################################
 *                        NewsletterService.class.php
 *                            -------------------
 *   begin                : February 8, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
	
	private static $streams_manager;
	
	public static function __static()
	{
		self::$lang = LangLoader::get('common', 'newsletter');
		self::$errors = '';
	}
	
	public static function add_newsletter(array $streams, $subject, $contents, $language_type)
	{
		Environment::try_to_increase_max_execution_time();
		
		$newsletter_streams = NewsletterStreamsCache::load()->get_streams();
		foreach ($newsletter_streams as $id => $stream)
		{
			if (in_array($id, $streams))
			{
				//Add archive
				NewsletterDAO::add_archive($id, $subject, $contents, $language_type);
				//Send mail
				NewsletterMailFactory::send_mail(self::list_subscribers_by_stream($id), $language_type, NewsletterConfig::load()->get_mail_sender(), $subject, $contents);
			}
		}
	}
	
	public static function delete_archive($id)
	{
		NewsletterDAO::delete_archive($id);
	}
	
	public static function display_newsletter($id_archive)
	{
		$row = PersistenceContext::get_querier()->select_single_row(NewsletterSetup::$newsletter_table_archives, array('*'), "WHERE id = '". $id_archive ."'");
		
		return NewsletterMailFactory::display_mail($row['language_type'], $row['subject'], $row['contents']);
	}
	
	public static function update_subscriptions_member_registered(Array $streams, $user_id)
	{
		if (NewsletterDAO::user_id_existed($user_id))
		{
			NewsletterDAO::update_subscriptions_member_registered($user_id, $streams);
		}
		else
		{
			NewsletterDAO::insert_subscriptions_member_registered($user_id, $streams);
		}
	}
	
	public static function update_subscriptions_visitor(Array $streams, $mail)
	{
		if (NewsletterDAO::mail_existed($mail))
		{
			NewsletterDAO::update_subscriptions_visitor($mail, $streams);	
		}
		else
		{
			NewsletterDAO::insert_subscriptions_visitor($mail, $streams);
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
	
	public static function get_member_id_streams($user_id)
	{
		$id_streams = array();
		
		$result = PersistenceContext::get_querier()->select("SELECT stream_id
		FROM " . NewsletterSetup::$newsletter_table_subscriptions . " subscriptions
		LEFT JOIN " . NewsletterSetup::$newsletter_table_subscribers . " subscribers ON subscribers.id = subscriptions.subscriber_id
		WHERE user_id = :user_id", array(
			'user_id' => $user_id
		));
		
		while ($row = $result->fetch())
		{
			$id_streams[] = $row['stream_id'];
		}
		$result->dispose();
		
		return $id_streams;
	}
	
	public static function list_subscribers_by_stream($stream_id)
	{
		$list_subscribers = array();
		
		$result = PersistenceContext::get_querier()->select("SELECT subscription.stream_id, subscription.subscriber_id, subscriber.id, subscriber.user_id, subscriber.mail
		FROM " . NewsletterSetup::$newsletter_table_subscriptions . " subscription
		LEFT JOIN " . NewsletterSetup::$newsletter_table_subscribers . " subscriber ON subscription.subscriber_id = subscriber.id
		WHERE subscription.stream_id = :stream_id
		",
			array(
				'stream_id' => $stream_id
		));
		
		while ($row = $result->fetch())
		{
			$list_subscribers[$row['id']] = array(
				'id' => $row['id'],
				'user_id' => $row['user_id'],
				'mail' => $row['mail']
			);
		}
		$result->dispose();
		
		return $list_subscribers;
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
	
	public static function get_streams_manager()
	{
		if (self::$streams_manager === null)
		{
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items(NewsletterSetup::$newsletter_table_archives);
			self::$streams_manager = new CategoriesManager(NewsletterStreamsCache::load(), $categories_items_parameters);
			self::$streams_manager->get_categories_items_parameters()->set_field_name_id_category('stream_id');
		}
		return self::$streams_manager;
	}
}
?>