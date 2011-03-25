<?php
/*##################################################
 *                        NewsletterDAO.class.php
 *                            -------------------
 *   begin                : February 21, 2011
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

class NewsletterDAO
{
	public static $db_querier;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	public static function add_archive($stream_id, $subject, $contents, $language_type)
	{
		$stream_cache = NewsletterStreamsCache::load()->get_stream($stream_id);
		$columns = array(
			'stream_id' => $stream_id,
			'subject' => $subject,
			'contents' => $contents,
			'timestamp' => time(),
			'language_type' => $language_type,
			'nbr_subscribers' => count($stream_cache['subscribers'])
		);
		self::$db_querier->insert(NewsletterSetup::$newsletter_table_archives, $columns);
		
		NewsletterStreamsCache::invalidate();
	}
	
	public static function insert_subscribtions_member_registered($user_id, Array $streams)
	{
		//Inject user in subscribers table
		$request = "INSERT INTO " . NewsletterSetup::$newsletter_table_subscribers . " (user_id) VALUES (:user_id)";
		$option = array(
			'user_id' => $user_id
		);
		self::$db_querier->inject($request, $option);

		$subscriber_id = PersistenceContext::get_sql()->query("SELECT MAX(id) FROM " . NewsletterSetup::$newsletter_table_subscribers);
		
		//Delete all entries in subscriber id
		$condition = "WHERE subscriber_id = :subscriber_id";
		$parameters = array('subscriber_id' => $subscriber_id);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscribtions, $condition, $parameters);

		foreach ($streams as $value)
		{
			//Insert user and stream_id in the subscribtions table
			$request = "INSERT INTO " . NewsletterSetup::$newsletter_table_subscribtions . " (stream_id, subscriber_id)	VALUES (:stream_id, :subscriber_id)";
			$option = array(
                'stream_id' => $value,
				'subscriber_id' => $subscriber_id
			);
			self::$db_querier->inject($request, $option);
		}
		NewsletterStreamsCache::invalidate();
	}
	
	public static function update_subscribtions_member_registered($user_id, Array $streams)
	{
		$subscriber_id = self::$db_querier->get_column_value(NewsletterSetup::$newsletter_table_subscribers, 'id', "WHERE user_id = '". $user_id ."'");
		
		//Delete all entries in subscriber id
		$condition = "WHERE subscriber_id = :subscriber_id";
		$parameters = array('subscriber_id' => $subscriber_id);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscribtions, $condition, $parameters);
		
		foreach ($streams as $value)
		{
			$request = "INSERT INTO " . NewsletterSetup::$newsletter_table_subscribtions . " (stream_id, subscriber_id)	VALUES (:stream_id, :subscriber_id)";
			
			$option = array(
                'stream_id' => $value,
				'subscriber_id' => $subscriber_id
			);
			
			self::$db_querier->inject($request, $option);
		}
		NewsletterStreamsCache::invalidate();
	}
	
	public static function insert_subscribtions_visitor($mail, Array $streams)
	{
		//Inject user in subscribers table
		$columns = array(
			'mail' => $mail
		);
		self::$db_querier->insert(NewsletterSetup::$newsletter_table_subscribers, $columns);

		$subscriber_id = PersistenceContext::get_sql()->query("SELECT MAX(id) FROM " . NewsletterSetup::$newsletter_table_subscribers);
		
		//Delete all entries in subscriber id
		$condition = "WHERE subscriber_id = :subscriber_id";
		$parameters = array('subscriber_id' => $subscriber_id);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscribtions, $condition, $parameters);

		foreach ($streams as $value)
		{
			//Insert user and stream_id in the subscribtions table
			$columns = array(
                'stream_id' => $value,
				'subscriber_id' => $subscriber_id
			);
			self::$db_querier->insert(NewsletterSetup::$newsletter_table_subscribtions, $columns);
		}
		NewsletterStreamsCache::invalidate();
	}
	
	public static function update_subscribtions_visitor($mail, Array $streams)
	{
		$subscriber_id = self::$db_querier->get_column_value(NewsletterSetup::$newsletter_table_subscribers, 'id', "WHERE mail = '". $mail ."'");
		
		//Delete all entries in subscriber id
		$condition = "WHERE subscriber_id = :subscriber_id";
		$parameters = array('subscriber_id' => $subscriber_id);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscribtions, $condition, $parameters);
		
		foreach ($streams as $value)
		{
			//Insert user and stream_id in the subscribtions table
			$columns = array(
                'stream_id' => $value,
				'subscriber_id' => $subscriber_id
			);
			self::$db_querier->insert(NewsletterSetup::$newsletter_table_subscribtions, $columns);
		}
		NewsletterStreamsCache::invalidate();
	}

	public static function user_id_existed($user_id)
	{
		return self::$db_querier->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE user_id = '" . $user_id . "'") > 0 ? true : false;
	}
	
	public static function mail_existed($mail)
	{
		return self::$db_querier->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE mail = '" . $mail . "'") > 0 ? true : false;
	}
	
	public static function unsubscriber_all_streams_member($user_id)
	{
		$subscriber_id = self::$db_querier->get_column_value(NewsletterSetup::$newsletter_table_subscribers, 'id', "WHERE user_id = '". $user_id ."'");
		$condition = "WHERE subscriber_id = :subscriber_id";
		$parameters = array(
			'subscriber_id' => $subscriber_id
		);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscribtions, $condition, $parameters);
		
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
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscribtions, $condition, $parameters);
		
		$condition = "WHERE mail = :mail";
		$parameters = array(
			'mail' => $mail
		);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscribers, $condition, $parameters);
		
	}
	
	public static function get_mail_for_member($user_id)
	{
		self::$db_querier->get_column_value(DB_TABLE_MEMBER, 'user_mail', "WHERE user_id = '". $user_id ."'");
	}
}
?>