<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 14
 * @since       PHPBoost 3.0 - 2011 02 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsletterDAO
{
	public static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	public static function add_archive($stream_id, $subject, $content, $language_type)
	{
		$columns = array(
			'stream_id' => $stream_id,
			'subject' => $subject,
			'content' => $content,
			'timestamp' => time(),
			'language_type' => $language_type,
			'nbr_subscribers' => count(NewsletterService::list_subscribers_by_stream($stream_id))
		);
		$result = self::$db_querier->insert(NewsletterSetup::$newsletter_table_archives, $columns);

		NewsletterStreamsCache::invalidate();
		
		return $result->get_last_inserted_id();
	}

	public static function delete_archive($id)
	{
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_archives, 'WHERE id = :id', array('id' => $id));
	}

	public static function insert_subscriptions_member_registered($user_id, Array $streams)
	{
		$streams_list = NewsletterStreamsCache::load()->get_streams();
		$subscribed_streams_list = array();
		
		//Inject user in subscribers table
		$now = new Date();
		$columns = array(
			'user_id' => $user_id,
			'subscription_date' => $now->get_timestamp()
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
			if (isset($streams_list[$value]))
				$subscribed_streams_list[] = $streams_list[$value]->get_name();
		}
		NewsletterStreamsCache::invalidate();

		if ($subscribed_streams_list)
		{
			$user = UserService::get_user($user_id);
			$lang = LangLoader::get_module_langs('newsletter');
			$hook_description = StringVars::replace_vars($lang['newsletter.specific_hook.newsletter_subscribe.description' . (count($subscribed_streams_list) == 1 ? '.single' : '')], array('user_display_name' => $user->get_display_name(), 'user_profile_url' => UserUrlBuilder::profile($user->get_id())->rel(), 'streams_list' => implode(', ', $subscribed_streams_list)));
			HooksService::execute_hook_action('newsletter_subscribe', 'newsletter', array('title' => $lang['newsletter.module.title'], 'url' => NewsletterUrlBuilder::home()->rel()), $hook_description);
		}
	}

	public static function update_subscriptions_member_registered($user_id, Array $streams)
	{
		$previous_streams_ids_list = NewsletterService::get_member_id_streams($user_id);
		$new_streams_ids_list = $added_streams_list = $removed_streams_list = array();
		$streams_list = NewsletterStreamsCache::load()->get_streams();
		$subscribed_streams_list = array();
		
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
			$new_streams_ids_list[] = $value;
			if (isset($streams_list[$value]) && !in_array($value, $previous_streams_ids_list))
				$added_streams_list[] = $streams_list[$value]->get_name();
		}
		foreach ($streams_list as $id => $stream)
		{
			if (in_array($id, $previous_streams_ids_list) && !in_array($id, $new_streams_ids_list))
				$removed_streams_list[] = $stream->get_name();
		}
		NewsletterStreamsCache::invalidate();

		$user = UserService::get_user($user_id);
		$lang = LangLoader::get_module_langs('newsletter');
		if ($added_streams_list)
		{
			$hook_description = StringVars::replace_vars($lang['newsletter.specific_hook.newsletter_subscribe.description' . (count($added_streams_list) == 1 ? '.single' : '')], array('user_display_name' => $user->get_display_name(), 'user_profile_url' => UserUrlBuilder::profile($user->get_id())->rel(), 'streams_list' => implode(', ', $added_streams_list)));
			HooksService::execute_hook_action('newsletter_subscribe', 'newsletter', array('title' => $lang['newsletter.module.title'], 'url' => NewsletterUrlBuilder::home()->rel()), $hook_description);
		}
		if ($removed_streams_list)
		{
			$hook_description = StringVars::replace_vars($lang['newsletter.specific_hook.newsletter_unsubscribe.description' . (count($removed_streams_list) == 1 ? '.single' : '')], array('user_display_name' => $user->get_display_name(), 'user_profile_url' => UserUrlBuilder::profile($user->get_id())->rel(), 'streams_list' => implode(', ', $removed_streams_list)));
			HooksService::execute_hook_action('newsletter_unsubscribe', 'newsletter', array('title' => $lang['newsletter.module.title'], 'url' => NewsletterUrlBuilder::home()->rel()), $hook_description);
		}
	}

	public static function insert_subscriptions_visitor($mail, Array $streams)
	{
		//Inject user in subscribers table
		$now = new Date();
		$columns = array(
			'mail' => $mail,
			'subscription_date' => $now->get_timestamp()
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
		return self::$db_querier->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE mail = '" . $mail . "'") + self::$db_querier->count(DB_TABLE_MEMBER, "WHERE email = '" . $mail . "'") > 0;
	}

	public static function unsubscriber_all_streams_member($user_id)
	{
		$subscriber_id = self::$db_querier->get_column_value(NewsletterSetup::$newsletter_table_subscribers, 'id', "WHERE user_id = '". $user_id ."'");
		$condition = "WHERE subscriber_id = :subscriber_id";
		$parameters = array(
			'subscriber_id' => $subscriber_id
		);
		$subscriptions_number = self::$db_querier->count(NewsletterSetup::$newsletter_table_subscriptions, $condition, $parameters);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscriptions, $condition, $parameters);

		$condition = "WHERE user_id = :user_id";
		$parameters = array(
			'user_id' => $user_id
		);
		self::$db_querier->delete(NewsletterSetup::$newsletter_table_subscribers, $condition, $parameters);

		if ($subscriptions_number)
		{
			$user = UserService::get_user($user_id);
			$lang = LangLoader::get_module_langs('newsletter');
			$hook_description = StringVars::replace_vars($lang['newsletter.specific_hook.newsletter_unsubscribe.all'], array('user_display_name' => $user->get_display_name(), 'user_profile_url' => UserUrlBuilder::profile($user->get_id())->rel()));
			HooksService::execute_hook_action('newsletter_unsubscribe', 'newsletter', array('title' => $lang['newsletter.module.title'], 'url' => NewsletterUrlBuilder::home()->rel()), $hook_description);
		}
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
