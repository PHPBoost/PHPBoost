<?php
/*##################################################
 *		    AdminCalendarManageEventsController.class.php
 *                            -------------------
 *   begin                : July 25, 2013
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
class AdminCalendarManageEventsController extends AdminModuleController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view($request);
		
		return new AdminCalendarDisplayResponse($this->view, $this->lang['calendar.config.events.management']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->view = new FileTemplate('calendar/AdminCalendarManageEventsController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		$categories = CalendarService::get_categories_manager()->get_categories_cache()->get_categories();
		
		$main_lang = LangLoader::get('main');
		
		$mode = $request->get_getvalue('sort', 'desc');
		$field = $request->get_getvalue('field', 'date');
		
		$sort_mode = ($mode == 'asc') ? 'ASC' : 'DESC';
		
		switch ($field)
		{
			case 'category':
				$sort_field = 'id_category';
				break;
			case 'author':
				$sort_field = 'login';
				break;
			case 'title':
				$sort_field = 'title';
				break;
			default:
				$sort_field = 'start_date';
				break;
		}
		
		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($page, $field, $mode);
		
		$result = PersistenceContext::get_querier()->select("SELECT *
		FROM " . CalendarSetup::$calendar_events_table . " event
		LEFT JOIN " . CalendarSetup::$calendar_events_content_table . " event_content ON event_content.id = event.content_id
		LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = event_content.author_id
		WHERE parent_id = 0
		ORDER BY " . $sort_field . " " . $sort_mode . "
		LIMIT :number_items_per_page OFFSET :display_from",
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);
		
		while($row = $result->fetch())
		{
			$event = new CalendarEvent();
			$event->set_properties($row);
			
			$this->view->assign_block_vars('event', $event->get_array_tpl_vars());
		}
		
		$this->view->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_EVENTS' => $result->get_rows_count() > 0,
			'PAGINATION' => $pagination->display(),
			'U_SORT_TITLE_ASC' => CalendarUrlBuilder::manage_events('title/asc/'. $page)->rel(),
			'U_SORT_TITLE_DESC' => CalendarUrlBuilder::manage_events('title/desc/'. $page)->rel(),
			'U_SORT_CATEGORY_ASC' => CalendarUrlBuilder::manage_events('category/asc/'. $page)->rel(),
			'U_SORT_CATEGORY_DESC' => CalendarUrlBuilder::manage_events('category/desc/'. $page)->rel(),
			'U_SORT_AUTHOR_ASC' => CalendarUrlBuilder::manage_events('author/asc/'. $page)->rel(),
			'U_SORT_AUTHOR_DESC' => CalendarUrlBuilder::manage_events('author/desc/'. $page)->rel(),
			'U_SORT_DATE_ASC' => CalendarUrlBuilder::manage_events('date/asc/'. $page)->rel(),
			'U_SORT_DATE_DESC' => CalendarUrlBuilder::manage_events('date/desc/'. $page)->rel()
		));
	}
	
	private function get_pagination($page, $sort_field, $sort_mode)
	{
		$events_number = PersistenceContext::get_querier()->count(CalendarSetup::$calendar_events_table);
		
		$pagination = new ModulePagination($page, $events_number, (int)CalendarConfig::load()->get_items_number_per_page());
		$pagination->set_url(CalendarUrlBuilder::manage_events($sort_field, $sort_mode, '%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
}
?>
