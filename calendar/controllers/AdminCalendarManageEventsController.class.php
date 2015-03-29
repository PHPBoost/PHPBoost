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
		
		$this->build_table();
		
		return new AdminCalendarDisplayResponse($this->view, $this->lang['calendar.config.events.management']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->view = new StringTemplate('# INCLUDE table #');
	}
	
	private function build_table()
	{
		$table_model = new SQLHTMLTableModel(CalendarSetup::$calendar_events_table, array(
			new HTMLTableColumn(''),
			new HTMLTableColumn(LangLoader::get_message('form.title', 'common'), 'title'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('date', 'date-common'), 'start_date'),
			new HTMLTableColumn($this->lang['calendar.titles.repetition']),
			new HTMLTableColumn(LangLoader::get_message('status.approved', 'common'), 'approved'),
		), new HTMLTableSortingRule('start_date', HTMLTableSortingRule::DESC));
		
		$table = new HTMLTable($table_model);
		
		$table_model->set_caption($this->lang['calendar.config.events.management']);
		$table_model->add_permanent_filter('parent_id = 0');
		
		$results = array();
		$result = $table_model->get_sql_results('event
			LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = event_content.author_id'
		);
		foreach ($result as $row)
		{
			$event = new CalendarEvent();
			$event->set_properties($row);
			$category = $event->get_content()->get_category();
			$user = $event->get_content()->get_author_user();

			$edit_link = new LinkHTMLElement(CalendarUrlBuilder::edit_event(!$event->get_parent_id() ? $event->get_id() : $event->get_parent_id(), SITE_REWRITED_SCRIPT), '', array('title' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			$delete_link = new LinkHTMLElement(CalendarUrlBuilder::delete_event($event->get_id(), SITE_REWRITED_SCRIPT), '', array('title' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => !$event->belongs_to_a_serie() ? 'delete-element' : ''), 'fa fa-delete');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('color' => $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();
			
			$br = new BrHTMLElement();
			
			$results[] = new HTMLTableRow(array(
				new HTMLTableRowCell($edit_link->display() . $delete_link->display()),
				new HTMLTableRowCell(new LinkHTMLElement(CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()), $event->get_content()->get_title()), 'left'),
				new HTMLTableRowCell(new SpanHTMLElement($category->get_name(), array('style' => $category->get_id() != Category::ROOT_CATEGORY && $category->get_color() ? 'color:' . $category->get_color() : ''))),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell(LangLoader::get_message('from_date', 'main') . ' ' . $event->get_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . $br->display() . LangLoader::get_message('to_date', 'main') . ' ' . $event->get_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($event->belongs_to_a_serie() ? $this->lang['calendar.labels.repeat.' . $event->get_content()->get_repeat_type()] . ' - ' . $event->get_content()->get_repeat_number() . ' ' . $this->lang['calendar.labels.repeat_times'] : LangLoader::get_message('no', 'common')),
				new HTMLTableRowCell($event->get_content()->is_approved() ? LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common')),
			));
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());
	}
}
?>
