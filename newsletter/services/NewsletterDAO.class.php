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
		$data = self::$db_querier->select_single_row(NewsletterSetup::$newsletter_table_subscribers, array('id'), "WHERE user_id = '". $user_id ."'");
		$subscriber_id = $data['id'];
		
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
	
	public static function insert_subscribtions_member_visitor($mail, Array $streams)
	{
		//Inject user in subscribers table
		$request = "INSERT INTO " . NewsletterSetup::$newsletter_table_subscribers . " (mail) VALUES (:mail)";
		$option = array(
			'mail' => $mail
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
	
	public static function update_subscribtions_member_visitor($mail, Array $streams)
	{
		$data = self::$db_querier->select_single_row(NewsletterSetup::$newsletter_table_subscribers, array('id'), "WHERE mail = '". $mail ."'");
		$subscriber_id = $data['id'];
		
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

	//TODO replace for a new fonction
	public static function verificate_exist_user_id($user_id)
	{
		return self::$db_querier->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE user_id = '" . $user_id . "'") > 0 ? true : false;
	}
	
	//TODO replace for a new fonction
	public static function verificate_exist_mail($mail)
	{
		return self::$db_querier->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE mail = '" . $mail . "'") > 0 ? true : false;
	}
	
	//TODO replace for a new fonction
	public static function unsubscriber_all_by_user_id($user_id)
	{
		self::$db_querier->inject(
			"DELETE	FROM " . NewsletterSetup::$newsletter_table_subscribers . "
			WHERE user_id = :user_id"
			, array(
				'user_id' => $user_id
		));
	}
	
	//TODO replace for a new fonction
	public static function unsubscriber_all_by_mail($user_id)
	{
		self::$db_querier->inject(
			"DELETE	FROM " . NewsletterSetup::$newsletter_table_subscribers . "
			WHERE mail = :mail"
			, array(
				'mail' => $mail
		));
	}
	
}
?>