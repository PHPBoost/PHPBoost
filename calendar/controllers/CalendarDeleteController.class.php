<?php
/*##################################################
 *                      CalendarDeleteController.class.php
 *                            -------------------
 *   begin                : November 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

/**
 * @author Julien BRISWALTER <julien.briswalter@phpboost.com>
 */
class CalendarDeleteController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_post_protect();
		
		$event = $this->get_event($request);
		
		//Authorization check
		if (!(CalendarAuthorizationsService::check_authorizations($event->get_id_cat())->moderation() || (CalendarAuthorizationsService::check_authorizations($event->get_id_cat())->write() && $event->get_author()->get_id() == AppContext::get_current_user()->get_id())))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		//Delete event
		CalendarService::delete('WHERE id=:id', array('id' => $event->get_id()));
		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'calendar', 'id' => $event->get_id()));
		
		//Delete event comments
		CommentsService::delete_comments_topic_module('calendar', $event->get_id());
		
		Feed::clear_cache('calendar');
		
		$array_time = explode('-', $event->get_start_date()->to_date());
		
		AppContext::get_response()->redirect(CalendarUrlBuilder::home($array_time[0] . '/'. $array_time[1]));
	}
	
	private function get_event()
	{
		$id = $request->get_getint('id', 0);
		
		if (!empty($id))
		{
			try {
				return CalendarService::get_event('WHERE id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>
