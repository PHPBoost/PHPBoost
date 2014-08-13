<?php
/*##################################################
 *                      NewsletterDeleteSubscriberController.class.php
 *                            -------------------
 *   begin                : March 16, 2011
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