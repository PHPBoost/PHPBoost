<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 09 12
 * @since       PHPBoost 3.0 - 2011 02 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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
