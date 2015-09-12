<?php
/*##################################################
 *                        NewsletterDAO.class.php
 *                            -------------------
 *   begin                : February 21, 2011
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

class NewsletterDAO
{
	public static $db_querier;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	public static function add_archive($stream_id, $subject, $contents, $language_type)
	{
		$columns = array(
			'stream_id' => $stream_id,
			'subject' => $subject,
			'contents' => $contents,
			'timestamp' => time(),
			'language_type' => $language_type,
			'nbr_subscribers' => count(NewsletterService::list_subscribers_by_stream($stream_id))
		);
		self::$db_querier->insert(NewsletterSetup::$newsletter_table_archives, $columns);
		
		NewsletterStreamsCache::invalidate();
	}

	public static function delete_archive($id)
	{
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_archives, 'WHERE id = :id', array('id' => $id));
	}
	
	public static function insert_subscriptions_member_registered($user_id, Array $streams)
	{
		//Inject user in subscribers table
		$columns = array(
			'user_id' => $user_id
		);
		self::$db_querier->insert(NewsletterSetup::$newsletter_table_subscribers, $columns);

		$subscriber_id = self::$db_querier->get_column_value(NewsletterSetup::$newsletter_table_subscribers, 'MAX(id)', '');
		
		//Delete all entries in subscriber id
		$condition = "WHERE subscriber_id = :subscriber_id";
		$parameters = array('subscriber_id' => $subscriber_id);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscriptions, $condition, $parameters);

		foreach ($streams as $value)
		{
			//Insert user and stream_id in the subscriptions table
			$columns = array(
				'stream_id' => $value,
				'subscriber_id' => $subscriber_id
			);
			self::$db_querier->insert(NewsletterSetup::$newsletter_table_subscriptions, $columns);
		}
		NewsletterStreamsCache::invalidate();
	}
	
	public static function update_subscriptions_member_registered($user_id, Array $streams)
	{
		$subscriber_id = self::$db_querier->get_column_value(NewsletterSetup::$newsletter_table_subscribers, 'id', "WHERE user_id = '". $user_id ."'");
		
		//Delete all entries in subscriber id
		$condition = "WHERE subscriber_id = :subscriber_id";
		$parameters = array('subscriber_id' => $subscriber_id);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscriptions, $condition, $parameters);
		
		foreach ($streams as $value)
		{
			$columns = array(
				'stream_id' => $value,
				'subscriber_id' => $subscriber_id
			);
			self::$db_querier->insert(NewsletterSetup::$newsletter_table_subscriptions, $columns);
		}
		NewsletterStreamsCache::invalidate();
	}
	
	public static function insert_subscriptions_visitor($mail, Array $streams)
	{
		//Inject user in subscribers table
		$columns = array(
			'mail' => $mail
		);
		self::$db_querier->insert(NewsletterSetup::$newsletter_table_subscribers, $columns);

		$subscriber_id = self::$db_querier->get_column_value(NewsletterSetup::$newsletter_table_subscribers, 'MAX(id)', '');
		
		//Delete all entries in subscriber id
		$condition = "WHERE subscriber_id = :subscriber_id";
		$parameters = array('subscriber_id' => $subscriber_id);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscriptions, $condition, $parameters);

		foreach ($streams as $value)
		{
			//Insert user and stream_id in the subscriptions table
			$columns = array(
                'stream_id' => $value,
				'subscriber_id' => $subscriber_id
			);
			self::$db_querier->insert(NewsletterSetup::$newsletter_table_subscriptions, $columns);
		}
		NewsletterStreamsCache::invalidate();
	}
	
	public static function update_subscriptions_visitor($mail, Array $streams)
	{
		$subscriber_id = self::$db_querier->get_column_value(NewsletterSetup::$newsletter_table_subscribers, 'id', "WHERE mail = '". $mail ."'");
		
		//Delete all entries in subscriber id
		$condition = "WHERE subscriber_id = :subscriber_id";
		$parameters = array('subscriber_id' => $subscriber_id);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscriptions, $condition, $parameters);
		
		foreach ($streams as $value)
		{
			//Insert user and stream_id in the subscriptions table
			$columns = array(
                'stream_id' => $value,
				'subscriber_id' => $subscriber_id
			);
			self::$db_querier->insert(NewsletterSetup::$newsletter_table_subscriptions, $columns);
		}
		NewsletterStreamsCache::invalidate();
	}

	public static function user_id_existed($user_id)
	{
		return self::$db_querier->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE user_id = '" . $user_id . "'") > 0;
	}
	
	public static function mail_existed($mail)
	{
		return self::$db_querier->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE mail = '" . $mail . "'") > 0;
	}
	
	public static function unsubscriber_all_streams_member($user_id)
	{
		$subscriber_id = self::$db_querier->get_column_value(NewsletterSetup::$newsletter_table_subscribers, 'id', "WHERE user_id = '". $user_id ."'");
		$condition = "WHERE subscriber_id = :subscriber_id";
		$parameters = array(
			'subscriber_id' => $subscriber_id
		);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscriptions, $condition, $parameters);
		
		$condition = "WHERE user_id = :user_id";
		$parameters = array(
			'user_id' => $user_id
		);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscribers, $condition, $parameters);
	} 
	
	public static function unsubscriber_all_streams_visitor($mail)
	{
		$subscriber_id = self::$db_querier->get_column_value(NewsletterSetup::$newsletter_table_subscribers, 'id', "WHERE mail = '". $mail ."'");
		$condition = "WHERE subscriber_id = :subscriber_id";
		$parameters = array(
			'subscriber_id' => $subscriber_id
		);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscriptions, $condition, $parameters);
		
		$condition = "WHERE mail = :mail";
		$parameters = array(
			'mail' => $mail
		);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscribers, $condition, $parameters);
		
	}
	
	public static function get_mail_for_member($user_id)
	{
		$mail = '';
		
		try {
			$mail = self::$db_querier->get_column_value(DB_TABLE_MEMBER, 'email', "WHERE user_id = '". $user_id ."'");
		} catch (Exception $e) { }
		
		return $mail;
	}
}
?>