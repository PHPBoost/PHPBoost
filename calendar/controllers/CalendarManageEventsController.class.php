<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 20
 * @since       PHPBoost 4.0 - 2013 07 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarManageEventsController extends AdminModuleController
{
	private $lang;
	private $view;

	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return $this->generate_response($current_page);
	}

	private function init()
	{
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
			new HTMLTableColumn($this->lang['calendar.titles.repetition']),
			new HTMLTableColumn(LangLoader::get_message('status.approved', 'common'), 'approved'),
			new HTMLTableColumn('')
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(CalendarSetup::$calendar_events_table, 'table', $columns, new HTMLTableSortingRule('start_date', HTMLTableSortingRule::DESC));

		$table_model->set_caption($this->lang['calendar.config.events.management']);
		$table_model->add_permanent_filter('parent_id = 0');

		$table = new HTMLTable($table_model);

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

			$this->elements_number++;
			$this->ids[$this->elements_number] = $event->get_id();

			$edit_link = new LinkHTMLElement(CalendarUrlBuilder::edit_event(!$event->get_parent_id() ? $event->get_id() : $event->get_parent_id()), '<i class="far fa-fw fa-edit"></i>', array('aria-label' => LangLoader::get_message('edit', 'common')), '');
			$delete_link = new LinkHTMLElement(CalendarUrlBuilder::delete_event($event->get_id()), '<i class="far fa-fw fa-trash-alt"></i>', array('aria-label' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => !$event->belongs_to_a_serie() ? 'delete-element' : ''), '');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$br = new BrHTMLElement();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()), $event->get_content()->get_title()), 'left'),
				new HTMLTableRowCell(new SpanHTMLElement($category->get_name(), array('style' => $category->get_id() != Category::ROOT_CATEGORY && $category->get_color() ? 'color:' . $category->get_color() : ''))),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell(LangLoader::get_message('from_date', 'main') . ' ' . $event->get_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . $br->display() . LangLoader::get_message('to_date', 'main') . ' ' . $event->get_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($event->belongs_to_a_serie() ? $this->lang['calendar.labels.repeat.' . $event->get_content()->get_repeat_type()] . ' - ' . $event->get_content()->get_repeat_number() . ' ' . $this->lang['calendar.labels.repeat_times'] : LangLoader::get_message('no', 'common')),
				new HTMLTableRowCell($event->get_content()->is_approved() ? LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common')),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display())
			);

			if (!$display_categories)
				unset($row[1]);

			$results[] = new HTMLTableRow($row);
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
					if (isset($this->ids[$i]))
					{
						try {
							$event = CalendarService::get_event('WHERE id_event = :id', array('id' => $this->ids[$i]));
						} catch (RowNotFoundException $e) {}

						if ($event)
						{
							$events_list = CalendarService::get_serie_events($event->get_content()->get_id());

							//Delete event
							CalendarService::delete_event($event->get_id(), $event->get_parent_id());

							if (!$event->belongs_to_a_serie() || count($events_list) == 1)
							{
								CalendarService::delete_event_content('WHERE id = :id', array('id' => $event->get_id()));
							}
						}
					}
				}
			}

			CalendarService::clear_cache();

			AppContext::get_response()->redirect(CalendarUrlBuilder::manage_events(), LangLoader::get_message('process.success', 'status-messages-common'));
		}
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response($page = 1)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['calendar.config.events.management'], $this->lang['module_title'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::manage_events());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], CalendarUrlBuilder::home());

		$breadcrumb->add($this->lang['calendar.config.events.management'], CalendarUrlBuilder::manage_events());

		return $response;
	}
}
?>
