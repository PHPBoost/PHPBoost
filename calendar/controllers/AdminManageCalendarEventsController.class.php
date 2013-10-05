<?php
/*##################################################
 *		    AdminManageCalendarEventsController.class.php
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
class AdminManageCalendarEventsController extends AdminModuleController
{
	private $lang;
	private $view;
	private $form;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view($request);
		
		return new AdminCalendarDisplayResponse($this->view, $this->lang['calendar.config.manage_events']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->view = new FileTemplate('calendar/AdminManageCalendarEventsController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function build_form($field, $mode)
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHorizontal('filters');
		$form->add_fieldset($fieldset);
		
		$sort_fields = $this->list_sort_fields();
		
		$fieldset->add_field(new FormFieldLabel($this->lang['calendar.sort_filter.title']));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_fields,
			array('events' => array('change' => 'document.location = "'. CalendarUrlBuilder::manage_events()->absolute() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($this->lang['calendar.sort_mode.asc'], 'asc'),
				new FormFieldSelectChoiceOption($this->lang['calendar.sort_mode.desc'], 'desc')
			), 
			array('events' => array('change' => 'document.location = "' . CalendarUrlBuilder::manage_events()->absolute() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$this->form = $form;
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		$categories = CalendarService::get_categories_manager()->get_categories_cache()->get_categories();
		
		$main_lang = LangLoader::get('main');
		
		$mode = $request->get_getvalue('sort', 'desc');
		$field = $request->get_getvalue('field', 'start_date');
		
		$sort_mode = ($mode == 'asc') ? 'ASC' : 'DESC';
		
		switch ($field)
		{
			case 'category':
				$sort_field = 'id_category';
				break;
			case 'author':
				$sort_field = 'author_id';
				break;
			case 'title':
				$sort_field = 'title';
				break;
			default:
				$sort_field = 'start_date';
				break;
		}
		
		$events_number = CalendarService::count();
		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($events_number, $page, $field, $mode);
		
		$result = PersistenceContext::get_querier()->select('SELECT calendar.*, calendar_cats.name, member.*
		FROM '. CalendarSetup::$calendar_table . ' calendar
		LEFT JOIN ' . CalendarSetup::$calendar_cats_table . ' calendar_cats ON calendar_cats.id = calendar.id_category 
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = calendar.author_id
		ORDER BY ' . $sort_field . ' ' . $sort_mode . '
		LIMIT :number_items_per_page OFFSET :display_from',
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);
		
		$this->build_form($field, $mode);
		
		if($events_number > 0)
		{
			$this->view->put_all(array(
				'C_PAGINATION' => $events_number > $pagination->get_number_items_per_page(),
				'L_EDIT' => $main_lang['edit'],
				'L_DELETE' => $main_lang['delete'],
				'L_YES' => $main_lang['yes'],
				'L_NO' => $main_lang['no'],
				'PAGINATION' => $pagination->display()
			));
			
			while($row = $result->fetch())
			{
				$event = new CalendarEvent();
				$event->set_properties($row);
				
				$start_date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['start_date']);
				$end_date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['end_date']);
				
				if (!empty($row['start_date']) && empty($row['end_date']))
					$date = $main_lang['on'] . ' ' . $start_date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
				else if (!empty($row['start_date']) && !empty($row['end_date']))
					$date = $main_lang['from_date'] . ' ' . $start_date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . '<br />' . $main_lang['to_date'] . ' ' . $end_date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
				
				$this->view->assign_block_vars('events', array_merge($event->get_array_tpl_vars(), array(
					'DATE' => $date,
					'U_DELETE' => CalendarUrlBuilder::delete_event($row['id'] . '/admin/' . $field . '/' . $mode . '/' . $page)->absolute()
				)));
			}
		}
		
		$this->view->put_all(array(
			'C_EVENTS' => $events_number,
			'FORM' => $this->form->display(),
			'U_ADD' => CalendarUrlBuilder::add_event()->absolute()
		));
	}
	
	private function list_sort_fields()
	{
		$options = array();
		
		$options[] = new FormFieldSelectChoiceOption($this->lang['calendar.config.sort_field.category'], 'category');
		$options[] = new FormFieldSelectChoiceOption($this->lang['calendar.config.sort_field.title'], 'title');
		$options[] = new FormFieldSelectChoiceOption($this->lang['calendar.config.sort_field.author'], 'author');
		$options[] = new FormFieldSelectChoiceOption($this->lang['calendar.config.sort_field.start_date'], 'start_date');
		
		return $options;
	}
	
	private function get_pagination($events_number, $page, $sort_field, $sort_mode)
	{
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
