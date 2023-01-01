<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 23
 * @since       PHPBoost 4.0 - 2013 07 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarItemsManagerController extends DefaultModuleController
{
	private $items_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return $this->generate_response($current_page);
	}

	private function build_table()
	{
		$display_categories = CategoriesService::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn($this->lang['common.title'], 'title'),
			new HTMLTableColumn($this->lang['category.category'], 'id_category'),
			new HTMLTableColumn($this->lang['common.author'], 'display_name'),
			new HTMLTableColumn($this->lang['date.date'], 'start_date'),
			new HTMLTableColumn($this->lang['calendar.repetition']),
			new HTMLTableColumn($this->lang['common.status'], 'approved'),
			new HTMLTableColumn($this->lang['common.actions'], '', array('sr-only' => true))
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(CalendarSetup::$calendar_events_table, 'items-manager', $columns, new HTMLTableSortingRule('start_date', HTMLTableSortingRule::DESC));

		$table_model->set_layout_title($this->lang['calendar.items.management']);
		$table_model->add_permanent_filter('parent_id = 0');

		$table_model->set_filters_menu_title($this->lang['calendar.filter.items']);
		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('start_date', 'filter1', $this->lang['calendar.start.date'] . ' ' . TextHelper::lcfirst($this->lang['common.minimum'])));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('start_date', 'filter2', $this->lang['calendar.start.date'] . ' ' . TextHelper::lcfirst($this->lang['common.maximum'])));
		$table_model->add_filter(new HTMLTableAjaxUserAutoCompleteSQLFilter('display_name', 'filter3', $this->lang['common.author']));
		if ($display_categories)
			$table_model->add_filter(new HTMLTableCategorySQLFilter('filter4'));
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('approved', 'filter5', $this->lang['common.status.publication'], array(1 => $this->lang['common.status.published'], 0 => $this->lang['common.status.draft'])));

		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();

		$results = array();
		$result = $table_model->get_sql_results('event
			LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = event_content.author_user_id'
		);
		foreach ($result as $row)
		{
			$item = new CalendarItem();
			$item->set_properties($row);
			$category = $item->get_content()->get_category();
			$user = $item->get_content()->get_author_user();

			$this->items_number++;
			$this->ids[$this->items_number] = $item->get_id();

			$edit_link = new EditLinkHTMLElement(CalendarUrlBuilder::edit_item(!$item->get_parent_id() ? $item->get_id() : $item->get_parent_id()));
			$delete_link = new DeleteLinkHTMLElement(CalendarUrlBuilder::delete_item($item->get_id()), '', array('data-confirmation' => !$item->belongs_to_a_serie() ? 'delete-element' : ''));

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$br = new BrHTMLElement();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(CalendarUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_content()->get_rewrited_title()), $item->get_content()->get_title()), 'align-left'),
				new HTMLTableRowCell(new LinkHTMLElement(CalendarUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), ($category->get_id() == Category::ROOT_CATEGORY ? $this->lang['common.none.alt'] : $category->get_name()), array('data-color-surround' => $category->get_id() != Category::ROOT_CATEGORY && $category->get_color() ? $category->get_color() : ($category->get_id() == Category::ROOT_CATEGORY ? $this->config->get_event_color() : '')), 'pinned')),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($this->lang['date.from.date'] . ' ' . $item->get_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR) . $br->display() . $this->lang['date.to.date'] . ' ' . $item->get_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR)),
				new HTMLTableRowCell($item->belongs_to_a_serie() ? $this->get_repeat_type_label($item) . ' - ' . $item->get_content()->get_repeat_number() . ' ' . $this->lang['calendar.repeat.times'] : $this->lang['common.no']),
				new HTMLTableRowCell($item->get_content()->is_approved() ? $this->lang['common.status.published'] : $this->lang['common.status.draft']),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display(), 'controls')
			);

			if (!$display_categories)
				unset($row[1]);

			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('CONTENT', $table->display());

		return $table->get_page_number();
	}

	private function execute_multiple_delete_if_needed(HTTPRequestCustom $request)
	{
		if ($request->get_string('delete-selected-elements', false))
		{
			for ($i = 1 ; $i <= $this->items_number ; $i++)
			{
				if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on')
				{
					if (isset($this->ids[$i]))
					{
						$item = '';
						try {
							$item = CalendarService::get_item($this->ids[$i]);
						} catch (RowNotFoundException $e) {}

						if ($item)
						{
							$items_list = CalendarService::get_serie_items($item->get_content()->get_id());

							//Delete item
							CalendarService::delete_item($item->get_id(), $item->get_parent_id());

							if (!$item->belongs_to_a_serie() || count($items_list) == 1)
							{
								CalendarService::delete_item_content($item->get_id());
							}
							
							HooksService::execute_hook_action('delete', self::$module_id, array_merge($item->get_content()->get_properties(), $item->get_properties()));
						}
					}
				}
			}

			CalendarService::clear_cache();

			AppContext::get_response()->redirect(CalendarUrlBuilder::manage_items(), $this->lang['warning.process.success']);
		}
	}

	private function get_repeat_type_label(CalendarItem $item)
	{
		$label = CalendarItemContent::NEVER;

		switch ($item->get_content()->get_repeat_type())
		{
			case CalendarItemContent::DAILY :
				$label = $this->lang['date.every.day'];
				break;

			case CalendarItemContent::WEEKLY :
				$label = $this->lang['date.every.week'];
				break;

			case CalendarItemContent::MONTHLY :
				$label = $this->lang['date.every.month'];
				break;

			case CalendarItemContent::YEARLY :
				$label = $this->lang['date.every.year'];
				break;
		}

		return $label;
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
		$graphical_environment->set_page_title($this->lang['calendar.items.management'], $this->lang['calendar.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::manage_items());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['calendar.module.title'], CalendarUrlBuilder::home());

		$breadcrumb->add($this->lang['calendar.items.management'], CalendarUrlBuilder::manage_items());

		return $response;
	}
}
?>
