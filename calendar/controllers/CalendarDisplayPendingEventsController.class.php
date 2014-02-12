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
		$authorized_categories = CalendarService::get_authorized_categories(Category::ROOT_CATEGORY);
		$pagination = $this->get_pagination($authorized_categories);
		
		$result = PersistenceContext::get_querier()->select("SELECT *
		FROM " . CalendarSetup::$calendar_events_table . " event
		LEFT JOIN " . CalendarSetup::$calendar_events_content_table . " event_content ON event_content.id = event.content_id
		LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = event_content.author_id
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = event.id_event AND com.module_id = 'calendar'
		WHERE approved = 0 AND parent_id = 0 AND id_category IN :authorized_categories
		ORDER BY start_date DESC
		LIMIT :number_items_per_page OFFSET :display_from", array(
			'authorized_categories' => $authorized_categories,
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		));
		
		while ($row = $result->fetch())
		{
			$event = new CalendarEvent();
			$event->set_properties($row);
			
			$this->tpl->assign_block_vars('event', $event->get_array_tpl_vars());
		}
		
		$this->tpl->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_EVENTS' => $result->get_rows_count() > 0,
			'C_PENDING_PAGE' => true,
			'PAGINATION' => $pagination->display()
		));
		
		return $this->tpl;
	}
	
	private function check_authorizations()
	{
		if (!(CalendarAuthorizationsService::check_authorizations()->write() || CalendarAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function get_pagination($authorized_categories)
	{
		$row = PersistenceContext::get_querier()->select_single_row_query('SELECT COUNT(*) AS events_number
		FROM ' . CalendarSetup::$calendar_events_table . ' event
		LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
		WHERE approved = 0 AND parent_id = 0 AND id_category IN :authorized_categories', array(
			'authorized_categories' => $authorized_categories
		));
		
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = new ModulePagination($page, $row['events_number'], (int)CalendarConfig::load()->get_items_number_per_page());
		$pagination->set_url(CalendarUrlBuilder::display_pending_events('%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
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
