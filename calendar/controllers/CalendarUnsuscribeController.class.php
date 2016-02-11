<?php
/*##################################################
 *                      CalendarUnsuscribeController.class.php
 *                            -------------------
 *   begin                : November 08, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class CalendarUnsuscribeController extends ModuleController
{
	private $event;
	
	public function execute(HTTPRequestCustom $request)
	{
		$event_id = $request->get_getint('event_id', 0);
		$current_user_id = AppContext::get_current_user()->get_id();
		
		if (!empty($event_id))
		{
			$this->get_event($event_id);
			
			$this->check_authorizations();
			
			if (in_array($current_user_id, array_keys($this->event->get_participants())))
			{
				CalendarService::delete_participant($event_id, $current_user_id);
				CalendarCurrentMonthEventsCache::invalidate();
			}
			
			$category = $this->event->get_content()->get_category();
			
			AppContext::get_response()->redirect($request->get_url_referrer() ? $request->get_url_referrer() : CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event_id, $this->event->get_content()->get_rewrited_title()));
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function get_event($event_id)
	{
		try {
			$this->event = CalendarService::get_event('WHERE id_event = :id', array('id' => $event_id));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function check_authorizations()
	{
		if (!$this->event->get_content()->is_registration_authorized())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		if (time() > $this->event->get_start_date()->get_timestamp())
		{
			$error_controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('calendar.notice.unsuscribe.event_date_expired', 'common', 'calendar'));
			DispatchManager::redirect($error_controller);
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$error_controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
