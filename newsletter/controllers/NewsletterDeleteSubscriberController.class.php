<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 03 16
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

			$controller = new UserErrorController(LangLoader::get_message('success', 'status-messages-common'), LangLoader::get_message('process.success', 'status-messages-common'), UserErrorController::SUCCESS);
			DispatchManager::redirect($controller);
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error-subscriber-not-existed', 'common', 'newsletter'));
			DispatchManager::redirect($controller);
		}
	}

	private static function subscriber_exist($id)
	{
		return PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_subscribers, "WHERE id = '" . $id . "'") > 0;
	}
}
?>
