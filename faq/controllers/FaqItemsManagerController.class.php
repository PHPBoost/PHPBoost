<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 23
 * @since       PHPBoost 4.0 - 2014 09 02
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class FaqItemsManagerController extends DefaultModuleController
{
	private $elements_number = 0;
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
			new HTMLTableColumn($this->lang['faq.form.question'], 'title'),
			new HTMLTableColumn($this->lang['common.category'], 'id_category'),
			new HTMLTableColumn($this->lang['common.author'], 'display_name'),
			new HTMLTableColumn($this->lang['common.creation.date'], 'creation_date'),
			new HTMLTableColumn($this->lang['common.status'], 'approved'),
			new HTMLTableColumn($this->lang['common.actions'], '', array('sr-only' => true))
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(FaqSetup::$faq_table, 'items-manager', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

		$table_model->set_layout_title($this->lang['faq.items.management']);

		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('creation_date', 'filter1', $this->lang['common.sort.by.date'] . ' ' . TextHelper::lcfirst($this->lang['common.minimum'])));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('creation_date', 'filter2', $this->lang['common.sort.by.date'] . ' ' . TextHelper::lcfirst($this->lang['common.maximum'])));
		$table_model->add_filter(new HTMLTableAjaxUserAutoCompleteSQLFilter('display_name', 'filter3', $this->lang['common.sort.by.author']));
		if ($display_categories)
			$table_model->add_filter(new HTMLTableCategorySQLFilter('filter4'));

		$status_list = array(Item::PUBLISHED => $this->lang['common.status.published'], Item::NOT_PUBLISHED => $this->lang['common.status.draft']);
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('published', 'filter5', $this->lang['common.status.publication'], $status_list));

		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();

		$results = array();
		$result = $table_model->get_sql_results('faq LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = faq.author_user_id');
		foreach ($result as $row)
		{
			$faq_question = new FaqItem();
            $faq_question->set_properties($row);
            $category = $faq_question->get_category();
            $user = $faq_question->get_author_user();

            $this->elements_number++;
            $this->ids[$this->elements_number] = $faq_question->get_id();

            $edit_link = new EditLinkHTMLElement(FaqUrlBuilder::edit($faq_question->get_id()));
            $delete_link = new DeleteLinkHTMLElement(FaqUrlBuilder::delete($faq_question->get_id()));

            $user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
            $author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(FaqUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $faq_question->get_id()), $faq_question->get_title()), 'align-left'),
				new HTMLTableRowCell(new LinkHTMLElement(FaqUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), ($category->get_id() == Category::ROOT_CATEGORY ? $this->lang['common.none.alt'] : $category->get_name()))),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($faq_question->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR)),
				new HTMLTableRowCell($faq_question->is_approved() ? $this->lang['common.status.published.alt'] : $this->lang['common.status.draft']),
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
            for ($i = 1; $i <= $this->elements_number; $i++)
            {
                if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on')
                {
                    if (isset($this->ids[$i]))
                    {
						$item = FaqService::get_item($this->ids[$i]);
                        FaqService::delete($this->ids[$i]);
						HooksService::execute_hook_action('delete', self::$module_id, $item->get_properties());
                    }
                }
            }

            FaqService::clear_cache();

            AppContext::get_response()->redirect(FaqUrlBuilder::manage(), $this->lang['warning.process.success']);
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
		$graphical_environment->set_page_title($this->lang['faq.items.management'], $this->lang['faq.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(FaqUrlBuilder::manage());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['faq.module.title'], FaqUrlBuilder::home());

		$breadcrumb->add($this->lang['faq.items.management'], FaqUrlBuilder::manage());

		return $response;
	}
}
?>
