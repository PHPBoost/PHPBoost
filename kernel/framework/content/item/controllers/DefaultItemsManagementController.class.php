<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 31
 * @since       PHPBoost 6.0 - 2020 01 16
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultItemsManagementController extends AbstractItemController
{
	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		if (!$this->check_authorizations())
			$this->display_user_not_authorized_page();

		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed();

		return $this->generate_response($current_page);
	}

	private function build_table()
	{
		$display_categories = self::get_module_configuration()->has_categories() && CategoriesService::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn($this->lang['form.title'], 'title'),
			new HTMLTableColumn($this->lang['author'], 'display_name'),
			new HTMLTableColumn($this->lang['form.date.creation'], 'creation_date'),
			new HTMLTableColumn($this->lang['status'], 'published'),
			new HTMLTableColumn(LangLoader::get_message('actions', 'admin-common'), '', array('sr-only' => true))
		);

		if ($display_categories)
			array_splice($columns, 1, 0, array(new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category')));

		$table_model = new SQLHTMLTableModel(self::get_module_configuration()->get_items_table_name(), 'items-manager', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

		$table_model->set_layout_title($this->lang['items.management']);

		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('creation_date', 'filter1', $this->lang['form.date.creation'] . ' ' . TextHelper::lcfirst($this->lang['minimum'])));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('creation_date', 'filter2', $this->lang['form.date.creation'] . ' ' . TextHelper::lcfirst($this->lang['maximum'])));
		$table_model->add_filter(new HTMLTableAjaxUserAutoCompleteSQLFilter('display_name', 'filter3', $this->lang['author']));
		if ($display_categories)
			$table_model->add_filter(new HTMLTableCategorySQLFilter('filter4'));

		$status_list = array(Item::PUBLISHED => $this->lang['status.approved.now'], Item::NOT_PUBLISHED => $this->lang['status.approved.not']);
		if (self::get_module_configuration()->feature_is_enabled('deferred_publication'))
			$status_list[Item::DEFERRED_PUBLICATION] = $this->lang['status.approved.date'];
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('published', 'filter5', $this->lang['status'], $status_list));
		
		$table = new HTMLTable($table_model, $this->lang);
		$table->set_filters_fieldset_class_HTML();

		$results = array();
		$result = $table_model->get_sql_results(self::get_module()->get_id() . '
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = ' . self::get_module()->get_id() . '.author_user_id
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' average_notes ON average_notes.module_name = \'' . self::get_module()->get_id() . '\' AND average_notes.id_in_module = ' . self::get_module()->get_id() . '.id
			LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.module_name = \'' . self::get_module()->get_id() . '\' AND note.id_in_module = ' . self::get_module()->get_id() . '.id AND note.user_id = ' . AppContext::get_current_user()->get_id() . '',
			array('*', self::get_module()->get_id() . '.id')
		);

		foreach ($result as $row)
		{
			$item = self::get_items_manager()->get_item_class();
			$item->set_properties($row);
			$category = self::get_module_configuration()->has_categories() ? $item->get_category() : null;
			$user = $item->get_author_user();

			$this->elements_number++;
			$this->ids[$this->elements_number] = $item->get_id();

			$edit_link = new EditLinkHTMLElement(ItemsUrlBuilder::edit($item->get_id()));
			$delete_link = new DeleteLinkHTMLElement(ItemsUrlBuilder::delete($item->get_id()));

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$br = new BrHTMLElement();

			$dates = '';
			if ($item->get_publishing_start_date() != null && $item->get_publishing_end_date() != null)
			{
				$dates = $this->lang['form.date.start'] . ' ' . $item->get_publishing_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . $br->display() . $this->lang['form.date.end'] . ' ' . $item->get_publishing_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
			}
			else
			{
				if ($item->get_publishing_start_date() != null)
					$dates = $item->get_publishing_start_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
				else
				{
					if ($item->get_publishing_end_date() != null)
						$dates = LangLoader::get_message('until', 'main') . ' ' . $item->get_publishing_end_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
				}
			}

			$start_and_end_dates = new SpanHTMLElement($dates, array(), 'smaller');
			$status = new SpanHTMLElement($item->get_status(), array(), 'publication-status ' . $item->get_status_class());

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(self::get_module_configuration()->has_categories() ? ItemsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()) : ItemsUrlBuilder::display_item($item->get_id(), $item->get_rewrited_title()), $item->get_title()), 'left'),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($item->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($status->display() . $br->display() . ($dates ? $start_and_end_dates->display() : '')),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display(), 'controls')
			);

			if ($display_categories)
				array_splice($row, 1, 0, array(new HTMLTableRowCell(new LinkHTMLElement(CategoriesUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), self::$module_id), ($category->get_id() == Category::ROOT_CATEGORY ? $this->lang['none_e'] : $category->get_name())))));

			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('TABLE', $table->display());

		return $table->get_page_number();
	}

	private function execute_multiple_delete_if_needed()
	{
		if ($this->request->get_string('delete-selected-elements', false))
		{
			for ($i = 1 ; $i <= $this->elements_number ; $i++)
			{
				if ($this->request->get_value('delete-checkbox-' . $i, 'off') == 'on')
				{
					if (isset($this->ids[$i]))
					{
						self::get_items_manager()->delete($this->ids[$i]);
					}
				}
			}

			self::get_items_manager()->clear_cache();

			AppContext::get_response()->redirect(ItemsUrlBuilder::manage(), LangLoader::get_message('process.success', 'status-messages-common'));
		}
	}

	protected function get_template_string_content()
	{
		return '# INCLUDE TABLE #';
	}

	protected function check_authorizations()
	{
		return self::get_module_configuration()->has_categories() ? CategoriesAuthorizationsService::check_authorizations()->moderation() : ItemsAuthorizationsService::check_authorizations()->moderation();
	}

	private function generate_response($page = 1)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['items.management'], self::get_module_configuration()->get_name(), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ItemsUrlBuilder::manage());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add(self::get_module_configuration()->get_name(), ModulesUrlBuilder::home());
		$breadcrumb->add($this->lang['items.management'], ItemsUrlBuilder::manage());

		return $response;
	}
}
?>
