<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 26
 * @since       PHPBoost 3.0 - 2011 03 16
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsletterDeleteSubscriberController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);
		$id_stream = $request->get_int('id_stream', 0);

		$db_querier = PersistenceContext::get_querier();

		if ($this->subscriber_exist($id) || $id_stream !== 0 && $id !== 0)
		{
			if (!NewsletterAuthorizationsService::id_stream($id_stream)->moderation_subscribers())
			{
				NewsletterAuthorizationsService::get_errors()->moderation_subscribers();
			}

			$condition = "WHERE subscriber_id = :id AND stream_id = :id_stream";
			$parameters = array(
				'id' => $id,
				'id_stream' => $id_stream
			);
			$db_querier->delete(NewsletterSetup::$newsletter_table_subscriptions, $condition, $parameters);

			$condition = "WHERE subscriber_id = :id";
			$parameters = array(
				'id' => $id,
			);

			$is_last = PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_subscriptions, $condition, $parameters) == 0;
			if ($is_last)
			{
				$condition = "WHERE id = :id";
				$parameters = array(
					'id' => $id,
				);
				$db_querier->delete(NewsletterSetup::$newsletter_table_subscribers, $condition, $parameters);
			}

			NewsletterStreamsCache::invalidate();

			$controller = new UserErrorController(LangLoader::get_message('warning.success', 'warning-lang'), LangLoader::get_message('warning.process.success', 'warning-lang'), UserErrorController::SUCCESS);
			DispatchManager::redirect($controller);
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), LangLoader::get_message('newsletter.subscriber.not.exists', 'common', 'newsletter'));
			DispatchManager::redirect($controller);
		}
	}

	private static function subscriber_exist($id)
	{
		return PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE id = '" . $id . "'") > 0;
	}
}
?>
