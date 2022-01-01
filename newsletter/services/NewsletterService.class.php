<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2011 02 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

class NewsletterService
{
	private static $lang;
	public static $errors;

	private static $streams_manager;

	public static function __static()
	{
		self::$lang = LangLoader::get_all_langs('newsletter');
		self::$errors = '';
	}

	public static function add_newsletter(array $streams, $subject, $content, $language_type)
	{
		Environment::try_to_increase_max_execution_time();

		$newsletter_streams = NewsletterStreamsCache::load()->get_streams();
		foreach ($newsletter_streams as $id => $stream)
		{
			if (in_array($id, $streams))
			{
				//Add archive
				$archive_id = NewsletterDAO::add_archive($id, $subject, $content, $language_type);
				//Send mail
				NewsletterMailFactory::send_mail(self::list_subscribers_by_stream($id), $language_type, NewsletterConfig::load()->get_mail_sender(), $subject, $content);

				$properties = array(
					'id'            => $archive_id,
					'title'         => $subject,
					'content'       => $content,
					'language_type' => $language_type,
					'url'           => NewsletterUrlBuilder::archive($archive_id),
				);

				HooksService::execute_hook_action('add', 'newsletter', $properties);
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

		return NewsletterMailFactory::display_mail($row['language_type'], $row['subject'], $row['content']);
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

	public static function get_visitor_id_streams($mail)
	{
		$id_streams = array();

		$result = PersistenceContext::get_querier()->select("SELECT stream_id
		FROM " . NewsletterSetup::$newsletter_table_subscriptions . " subscriptions
		LEFT JOIN " . NewsletterSetup::$newsletter_table_subscribers . " subscribers ON subscribers.id = subscriptions.subscriber_id
		WHERE mail = :mail", array(
			'mail' => $mail
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

		$result = PersistenceContext::get_querier()->select("SELECT subscription.stream_id, subscription.subscriber_id, subscriber.id, subscriber.user_id, subscriber.mail, member.display_name
		FROM " . NewsletterSetup::$newsletter_table_subscriptions . " subscription
		LEFT JOIN " . NewsletterSetup::$newsletter_table_subscribers . " subscriber ON subscription.subscriber_id = subscriber.id
		LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = subscriber.user_id
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
				'mail' => $row['mail'],
				'display_name' => isset($row['display_name']) ? $row['display_name'] : TextHelper::lcfirst(LangLoader::get_message('user.guest', 'user-lang')),
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
