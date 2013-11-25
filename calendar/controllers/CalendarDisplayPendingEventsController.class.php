<?php
/*##################################################
 *		                         CalendarDisplayPendingEventsController.class.php
 *                            -------------------
 *   begin                : September 29, 2013
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

/**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class CalendarDisplayPendingEventsController extends ModuleController
{
	private $tpl;
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view($request);
		
		return $this->generate_response();
	}
	
	public function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->tpl = new FileTemplate('calendar/CalendarDisplaySeveralEventsController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	public function build_view(HTTPRequestCustom $request)
	{
		$config = CalendarConfig::load();
		
		$year = $request->get_getint('year', date('Y'));
		$month = $request->get_getint('month', date('n'));
		$day = $request->get_getint('day', date('j'));
		$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
		
		$date_lang = LangLoader::get('date-common');
		
		$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
		$array_l_month = array($date_lang['january'], $date_lang['february'], $date_lang['march'], $date_lang['april'], $date_lang['may'], $date_lang['june'],
		$date_lang['july'], $date_lang['august'], $date_lang['september'], $date_lang['october'], $date_lang['november'], $date_lang['december']);
		$month_day = $array_month[$month - 1];
		
		$this->tpl->put_all(array(
			'C_COMMENTS_ENABLED' => $config->are_comments_enabled(),
			'C_ADD' => CalendarAuthorizationsService::check_authorizations()->write() || CalendarAuthorizationsService::check_authorizations()->contribution(),
			'C_PENDING_EVENTS' => CalendarAuthorizationsService::check_authorizations()->write() || CalendarAuthorizationsService::check_authorizations()->moderation(),
			'CALENDAR' => CalendarAjaxCalendarController::get_view(),
			'DATE' => $day . ' ' . $array_l_month[$month - 1] . ' ' . $year,
			'L_EVENTS' => $this->lang['calendar.pending'],
			'U_ADD' => CalendarUrlBuilder::add_event($year . '/' . $month . '/' . $day)->rel()
		));
		
		$result = PersistenceContext::get_querier()->select("SELECT calendar.*, member.*, com.number_comments
		FROM " . CalendarSetup::$calendar_table . " calendar
		LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id=calendar.author_id
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = calendar.id AND com.module_id = 'calendar'
		WHERE calendar.approved = 0 AND ((calendar.start_date BETWEEN :first_day_hour AND :last_day_hour)
		OR (calendar.end_date BETWEEN :first_day_hour AND :last_day_hour)
		OR (:first_day_hour BETWEEN calendar.start_date AND calendar.end_date))
		ORDER BY start_date ASC", array(
			'first_day_hour' => mktime(0, 0, 0, $month, $day, $year),
			'last_day_hour' => mktime(23, 59, 59, $month, $day, $year)
		));
		
		$events = false;
		
		while ($row = $result->fetch())
		{
			$event = new CalendarEvent();
			$event->set_properties($row);
			
			$this->tpl->assign_block_vars('event', $event->get_array_tpl_vars());
			
			$events = true;
		}
		
		$this->tpl->put('C_EVENT', $events);
		
		return $this->tpl;
	}
	
	private function check_authorizations()
	{
		if (!CalendarAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		$response = new CalendarDisplayResponse();
		$response->set_page_title($this->lang['calendar.pending']);
		$response->set_page_description($this->lang['calendar.seo.description.pending']);
		
		$response->add_breadcrumb_link($this->lang['module_title'], CalendarUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['calendar.pending'], CalendarUrlBuilder::display_pending_events());
		
		return $response->display($this->tpl);
	}
}
?>