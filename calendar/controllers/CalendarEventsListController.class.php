<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 06
 * @since       PHPBoost 4.1 - 2015 04 13
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarEventsListController extends ModuleController
{
	private $lang;
	private $view;

	private $elements_number = 0;
	private $ids = array();
	private $hide_delete_input = array();
	private $display_multiple_delete = true;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init($request);

		$current_page = $this->build_table();

		if ($this->display_multiple_delete)
			$this->execute_multiple_delete_if_needed($request);

		return $this->generate_response($current_page);
	}

	private function init(HTTPRequestCustom $request)
	{
		if ($request->get_value('display_current_day_events', 0))
			AppContext::get_response()->redirect(CalendarUrlBuilder::events_list());
		
		$this->lang = LangLoader::get('common', 'calendar');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$display_categories = CategoriesService::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn(LangLoader::get_message('form.title', 'common'), 'title'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('date', 'date-common'), 'start_date'),
			new HTMLTableColumn($this->lang['calendar.repetition']),
			new HTMLTableColumn('')
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(CalendarSetup::$calendar_events_table, 'table', $columns, new HTMLTableSortingRule('start_date', HTMLTableSortingRule::ASC));

		$table_model->set_caption($this->lang['calendar.events.list']);
		$table_model->add_permanent_filter('parent_id = 0');

		$table_model->add_filter(new HTMLTableDateTimeGreaterThanOrEqualsToSQLFilter('start_date', 'filter1', $this->lang['calendar.labels.start.date'] . ' ' . TextHelper::lcfirst(LangLoader::get_message('minimum', 'common'))));
		$table_model->add_filter(new HTMLTableDateTimeLessThanOrEqualsToSQLFilter('start_date', 'filter2', $this->lang['calendar.labels.start.date'] . ' ' . TextHelper::lcfirst(LangLoader::get_message('maximum', 'common'))));

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
			{
				$moderation_link_number++;
				$this->elements_number++;
				$this->ids[$this->elements_number] = $event->get_id();
			}
			else
				$this->hide_delete_input[] = $event->get_id();
		}

		if (empty($moderation_link_number))
		{
			$table_model->delete_last_column();
			$table->hide_multiple_delete();
			$this->display_multiple_delete = false;
		}

		foreach ($events as $event)
		{
			$category = $event->get_content()->get_category();
			$user = $event->get_content()->get_author_user();

			$edit_link = new EditLinkHTMLElement(CalendarUrlBuilder::edit_event(!$event->get_parent_id() ? $event->get_id() : $event->get_parent_id()));
			$edit_link = $event->is_authorized_to_edit() ? $edit_link->display() : '';

			$delete_link = new DeleteLinkHTMLElement(CalendarUrlBuilder::delete_event($event->get_id()), '', array('data-confirmation' => !$event->belongs_to_a_serie() ? 'delete-element' : ''));
			$delete_link = $event->is_authorized_to_delete() ? $delete_link->display() : '';

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$br = new BrHTMLElement();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()), $event->get_content()->get_title()), 'left'),
				new HTMLTableRowCell(new SpanHTMLElement($category->get_name(), array('data-color' => $category->get_id() != Category::ROOT_CATEGORY && $category->get_color() ? $category->get_color() : ''), 'pinned')),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell(LangLoader::get_message('from_date', 'main') . ' ' . $event->get_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . $br->display() . LangLoader::get_message('to_date', 'main') . ' ' . $event->get_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($event->belongs_to_a_serie() ? $this->get_repeat_type_label($event) . ' - ' . $event->get_content()->get_repeat_number() . ' ' . $this->lang['calendar.labels.repeat_times'] : LangLoader::get_message('no', 'common')),
				$moderation_link_number ? new HTMLTableRowCell($edit_link . $delete_link) : null
			);

			if (!$display_categories)
				unset($row[1]);

			$table_row = new HTMLTableRow($row);
			if (in_array($event->get_id(), $this->hide_delete_input))
				$table_row->hide_delete_input();

			$results[] = $table_row;
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());

		return $table->get_page_number();
	}

	private function execute_multiple_delete_if_needed(HTTPRequestCustom $request)
	{
		if ($request->get_string('delete-selected-elements', false))
		{
			for ($i = 1 ; $i <= $this->elements_number ; $i++)
			{
				if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on')
				{
					if (isset($this->ids[$i]) && !in_array($this->ids[$i], $this->hide_delete_input))
					{
						try {
							$event = CalendarService::get_event('WHERE id_event = :id', array('id' => $this->ids[$i]));
						} catch (RowNotFoundException $e) {}

						if ($event)
						{
							$events_list = CalendarService::get_serie_events($event->get_content()->get_id());

							if (!$event->belongs_to_a_serie() || count($events_list) == 1)
							{
								CalendarService::delete_event_content('WHERE id = :id', array('id' => $event->get_id()));
							}

							//Delete event
							CalendarService::delete_event('WHERE id_event = :id', array('id' => $event->get_id()));

							if (!$this->event->get_parent_id())
								PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'calendar', 'id' => $event->get_id()));

							//Delete event comments
							CommentsService::delete_comments_topic_module('calendar', $event->get_id());

							//Delete participants
							CalendarService::delete_all_participants($event->get_id());
						}
					}
				}
			}

			CalendarService::clear_cache();
			AppContext::get_response()->redirect(CalendarUrlBuilder::events_list(), LangLoader::get_message('process.success', 'status-messages-common'));
		}
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->read())
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

	private function generate_response($page = 1)
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['calendar.events.list'], $this->lang['calendar.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['calendar.seo.description.events_list'], array('site' => GeneralConfig::load()->get_site_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::events_list());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['calendar.module.title'], CalendarUrlBuilder::home());
		$breadcrumb->add($this->lang['calendar.events.list'], CalendarUrlBuilder::events_list());

		return $response;
	}
}
?>
