<?php
/*##################################################
 *                          CalendarAjaxSubscribeUnsubscribeController.class.php
 *                            -------------------
 *   begin                : August 31, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class CalendarAjaxSubscribeUnsubscribeController extends AbstractController
{
	private $view;
	private $event;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);
		$this->build_view($request);
		return new SiteNodisplayResponse($this->view);
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_post_protect();
		
		$action = $request->get_value('action', '');
		$event_id = $request->get_int('event_id', 0);
		$user_id = $request->get_int('user_id', 0);
		
		$result = -1;
		if (!empty($action) && !empty($event_id) && !empty($user_id))
		{
			switch ($action)
			{
				case 'subscribe' :
					CalendarService::add_participant($event_id, $user_id);
					$result = 1;
					break;
				case 'unsubscribe' :
					CalendarService::delete_participant($event_id, $user_id);
					$result = 0;
					break;
				default :
					break;
		}
		
		$this->view->put('RESULT', $result);
	}
	
	private function init(HTTPRequestCustom $request)
	{
		$this->view = new StringTemplate('{RESULT}');
		$this->get_event($request);
	}
	
	private function get_event(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('event_id', 0);
		
		if (!empty($id))
		{
			try {
				$this->event = CalendarService::get_event('WHERE id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
	
	private function check_authorizations()
	{
		if (!$this->event->is_registration_authorized() || !$this->event->is_authorized_to_register() || ($this->event->is_registration_authorized() && $this->event->get_max_registred_members() > 0 && $this->event->get_registred_members_number() == $this->event->get_max_registred_members()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
	}
}
?>
