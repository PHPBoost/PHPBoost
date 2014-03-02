<?php
/*##################################################
 *                      CalendarDisplayCategoryController.class.php
 *                            -------------------
 *   begin                : August 21, 2013
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

class CalendarDisplayCategoryController extends ModuleController
{
	private $lang;
	private $tpl;
	
	private $category;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view($request);
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->tpl = new FileTemplate('calendar/CalendarDisplaySeveralEventsController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		$db_querier = PersistenceContext::get_querier();
		$authorized_categories = CalendarService::get_authorized_categories($this->get_category()->get_id());
		
		$date_lang = LangLoader::get('date-common');
		$events_list = $participants = array();
		
		$year = $request->get_getint('year', date('Y'));
		$month = $request->get_getint('month', date('n'));
		$day = $request->get_getint('day', date('j'));
		
		if (!checkdate($month,$day,$year))
		{
			$this->tpl->put('MSG', MessageHelper::display($this->lang['calendar.error.e_invalid_date'], E_USER_ERROR));
			
			$year = date('Y');
			$month = date('n');
			$day = date('j');
		}
		
		$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
		
		$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
		$array_l_month = array($date_lang['january'], $date_lang['february'], $date_lang['march'], $date_lang['april'], $date_lang['may'], $date_lang['june'],
		$date_lang['july'], $date_lang['august'], $date_lang['september'], $date_lang['october'], $date_lang['november'], $date_lang['december']);
		$month_day = $array_month[$month - 1];
		
		$result = $db_querier->select("SELECT *
		FROM " . CalendarSetup::$calendar_events_table . " event
		LEFT JOIN " . CalendarSetup::$calendar_events_content_table . " event_content ON event_content.id = event.content_id
		LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = event_content.author_id
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = event.id_event AND com.module_id = 'calendar'
		WHERE approved = 1 AND ((start_date BETWEEN :first_day_hour AND :last_day_hour)
		OR (end_date BETWEEN :first_day_hour AND :last_day_hour)
		OR (:first_day_hour BETWEEN start_date AND end_date)) AND id_category IN :authorized_categories
		ORDER BY start_date ASC", array(
			'first_day_hour' => mktime(0, 0, 0, $month, $day, $year),
			'last_day_hour' => mktime(23, 59, 59, $month, $day, $year),
			'authorized_categories' => $authorized_categories
		));
		
		while ($row = $result->fetch())
		{
			$event = new CalendarEvent();
			$event->set_properties($row);
			
			$events_list[$event->get_id()] = $event;
		}
		
		$events_number = $result->get_rows_count();
		
		$this->tpl->put_all(array(
			'C_COMMENTS_ENABLED' => CalendarConfig::load()->are_comments_enabled(),
			'C_EVENTS' => $events_number > 0,
			'CALENDAR' => CalendarAjaxCalendarController::get_view(false, $year, $month),
			'DATE' => $day . ' ' . $array_l_month[$month - 1] . ' ' . $year,
			'L_EVENTS_NUMBER' => $events_number > 1 ? StringVars::replace_vars($this->lang['calendar.labels.events_number'], array('events_number' => $events_number)) : $this->lang['calendar.labels.one_event'],
		));
		
		if (!empty($events_list))
		{
			$result = $db_querier->select('SELECT event_id, member.user_id, login, level, user_groups
			FROM ' . CalendarSetup::$calendar_users_relation_table . ' participants
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = participants.user_id
			WHERE event_id IN :events_list', array(
				'events_list' => array_keys($events_list)
			));
			
			while($row = $result->fetch())
			{
				$participant = new CalendarEventParticipant();
				$participant->set_properties($row);
				$participants[$row['event_id']][$participant->get_user_id()] = $participant;
			}
			
			foreach ($events_list as $event)
			{
				if (isset($participants[$event->get_id()]))
					$event->set_participants($participants[$event->get_id()]);
				
				$this->tpl->assign_block_vars('event', $event->get_array_tpl_vars());
				
				foreach ($event->get_participants() as $participant)
				{
					$this->tpl->assign_block_vars('event.participant', $participant->get_array_tpl_vars());
				}
			}
		}
		
		return $this->tpl;
	}
	
	private function check_authorizations()
	{
		$id_cat = $this->get_category()->get_id();
		if (!CalendarAuthorizationsService::check_authorizations($id_cat)->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = CalendarService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CalendarService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}
	
	private function generate_response()
	{
		$response = new CalendarDisplayResponse();
		$response->set_page_title($this->get_category()->get_name());
		
		$response->add_breadcrumb_link($this->lang['module_title'], CalendarUrlBuilder::home());
		
		$categories = array_reverse(CalendarService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$response->add_breadcrumb_link($category->get_name(), CalendarUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		
		return $response->display($this->tpl);
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->tpl;
	}
}
?>
