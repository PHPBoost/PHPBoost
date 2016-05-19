<?php
/*##################################################
 *		    CalendarEventsListController.class.php
 *                            -------------------
 *   begin                : April 13, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
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

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class CalendarEventsListController extends ModuleController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_table();
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->view = new StringTemplate('# INCLUDE table #');
	}
	
	private function build_table()
	{
		$table_model = new SQLHTMLTableModel(CalendarSetup::$calendar_events_table, 'table', array(
			new HTMLTableColumn(LangLoader::get_message('form.title', 'common'), 'title'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('date', 'date-common'), 'start_date'),
			new HTMLTableColumn($this->lang['calendar.titles.repetition']),
			new HTMLTableColumn('')
		), new HTMLTableSortingRule('start_date', HTMLTableSortingRule::ASC));
		
		$table_model->set_caption($this->lang['calendar.events_list']);
		$table_model->add_permanent_filter('parent_id = 0');
		
		$table_model->add_filter(new HTMLTableDateTimeGreaterThanOrEqualsToSQLFilter('start_date', 'filter1', $this->lang['calendar.labels.start_date'] . ' ' . TextHelper::lowercase_first(LangLoader::get_message('minimum', 'common'))));
		$table_model->add_filter(new HTMLTableDateTimeLessThanOrEqualsToSQLFilter('start_date', 'filter2', $this->lang['calendar.labels.start_date'] . ' ' . TextHelper::lowercase_first(LangLoader::get_message('maximum', 'common'))));
		
		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();
		
		$results = array();
		$result = $table_model->get_sql_results('event
			LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = event_content.author_id'
		);
		
		$events = array();
		$moderation_link_number = 0;
		foreach ($result as $row)
		{
			$event = new CalendarEvent();
			$event->set_properties($row);
			$events[] = $event;
			if ($event->is_authorized_to_edit() || $event->is_authorized_to_delete())
				$moderation_link_number++;
		}
		
		if (empty($moderation_link_number))
			$table_model->delete_last_column();
		
		foreach ($events as $event)
		{
			$category = $event->get_content()->get_category();
			$user = $event->get_content()->get_author_user();

			$edit_link = new LinkHTMLElement(CalendarUrlBuilder::edit_event(!$event->get_parent_id() ? $event->get_id() : $event->get_parent_id()), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			$edit_link = $event->is_authorized_to_edit() ? $edit_link->display() : '';
			
			$delete_link = new LinkHTMLElement(CalendarUrlBuilder::delete_event($event->get_id()), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => !$event->belongs_to_a_serie() ? 'delete-element' : ''), 'fa fa-delete');
			$delete_link = $event->is_authorized_to_delete() ? $delete_link->display() : '';

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();
			
			$br = new BrHTMLElement();
			
			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell(new LinkHTMLElement(CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()), $event->get_content()->get_title()), 'left'),
				new HTMLTableRowCell(new SpanHTMLElement($category->get_name(), array('style' => $category->get_id() != Category::ROOT_CATEGORY && $category->get_color() ? 'color:' . $category->get_color() : ''))),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell(LangLoader::get_message('from_date', 'main') . ' ' . $event->get_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . $br->display() . LangLoader::get_message('to_date', 'main') . ' ' . $event->get_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($event->belongs_to_a_serie() ? $this->get_repeat_type_label($event) . ' - ' . $event->get_content()->get_repeat_number() . ' ' . $this->lang['calendar.labels.repeat_times'] : LangLoader::get_message('no', 'common')),
				$moderation_link_number ? new HTMLTableRowCell($edit_link . $delete_link) : null
			));
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());
	}
	
	private function check_authorizations()
	{
		if (!CalendarAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function get_repeat_type_label(CalendarEvent $event)
	{
		$label = CalendarEventContent::NEVER;
		
		switch ($event->get_content()->get_repeat_type())
		{
			case CalendarEventContent::DAILY :
				$label = LangLoader::get_message('every_day', 'date-common');
				break;
			
			case CalendarEventContent::WEEKLY :
				$label = LangLoader::get_message('every_week', 'date-common');
				break;
			
			case CalendarEventContent::MONTHLY :
				$label = LangLoader::get_message('every_month', 'date-common');
				break;
			
			case CalendarEventContent::YEARLY :
				$label = LangLoader::get_message('every_year', 'date-common');
				break;
		}
		
		return $label;
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['calendar.events_list'], $this->lang['module_title']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], CalendarUrlBuilder::home());
		$breadcrumb->add($this->lang['calendar.events_list'], CalendarUrlBuilder::events_list());
		
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::events_list());
		
		return $response;
	}
}
?>
