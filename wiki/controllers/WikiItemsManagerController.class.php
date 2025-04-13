<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 04 13
 * @since       PHPBoost 6.0 - 2022 11 18
 */

class WikiItemsManagerController extends DefaultModuleController
{
    private $elements_number = 0;
    private $ids = [];

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

        $columns = [
            new HTMLTableColumn($this->lang['common.title'], 'title'),
            new HTMLTableColumn($this->lang['category.category'], 'id_category'),
            new HTMLTableColumn($this->lang['common.author'], 'display_name'),
            new HTMLTableColumn($this->lang['common.creation.date'], 'creation_date'),
            new HTMLTableColumn($this->lang['common.status'], 'published'),
            new HTMLTableColumn($this->lang['common.actions'], '', ['sr-only' => true])
        ];

        if (!$display_categories)
            unset($columns[1]);

        $table_model = new SQLHTMLTableModel(WikiSetup::$wiki_articles_table, 'items-manager', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

        $table_model->set_layout_title($this->lang['wiki.items.management']);
        $table_model->add_permanent_filter('c.active_content = 1');

        $table_model->set_filters_menu_title($this->lang['wiki.filter.items']);
        $table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('creation_date', 'filter1', $this->lang['common.creation.date'] . ' ' . TextHelper::lcfirst($this->lang['common.minimum'])));
        $table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('creation_date', 'filter2', $this->lang['common.creation.date'] . ' ' . TextHelper::lcfirst($this->lang['common.maximum'])));
        $table_model->add_filter(new HTMLTableAjaxUserAutoCompleteSQLFilter('display_name', 'filter3', $this->lang['common.author']));
        if ($display_categories)
            $table_model->add_filter(new HTMLTableCategorySQLFilter('filter4'));

        $status_list = [Item::PUBLISHED => $this->lang['common.status.published'], Item::NOT_PUBLISHED => $this->lang['common.status.draft'], Item::DEFERRED_PUBLICATION => $this->lang['common.status.deffered.date']];
        $table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('published', 'filter5', $this->lang['common.status.publication'], $status_list));

        $table = new HTMLTable($table_model);
        $table->set_filters_fieldset_class_HTML();

        $results = [];
        $result = $table_model->get_sql_results('i
            LEFT JOIN ' . WikiSetup::$wiki_contents_table . ' c ON c.item_id = i.id
            LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = i.id AND com.module_id = \'wiki\'
            LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = i.id AND notes.module_name = \'wiki\'
            LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = i.id AND note.module_name = \'wiki\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
            LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = c.author_user_id',
            ['*', 'i.id']
        );
        foreach ($result as $row)
        {
            $item = new WikiItem();
            $item->set_properties($row);
            $category = $item->get_category();
            $user = $item->get_item_content()->get_author_user();

            $this->elements_number++;
            $this->ids[$this->elements_number] = $item->get_id();

            $edit_link = new EditLinkHTMLElement(WikiUrlBuilder::edit($item->get_id()));
            $delete_link = new DeleteLinkHTMLElement(WikiUrlBuilder::delete($item->get_id(), 0));

            $user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
            $author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? ['style' => 'color: ' . $user_group_color] : []), UserService::get_level_class($user->get_level())) : $user->get_display_name();

            $row = [
                new HTMLTableRowCell(new LinkHTMLElement(WikiUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()), $item->get_title()), 'left'),
                new HTMLTableRowCell(new LinkHTMLElement(WikiUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), ($category->get_id() == Category::ROOT_CATEGORY ? $this->lang['common.none.alt'] : $category->get_name()))),
                new HTMLTableRowCell($author),
                new HTMLTableRowCell($item->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
                new HTMLTableRowCell($item->get_status()),
                new HTMLTableRowCell($edit_link->display() . $delete_link->display(), 'controls')
            ];

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
                        $item = WikiService::get_item($this->ids[$i]);
                        WikiService::delete($this->ids[$i], 0);
                        HooksService::execute_hook_action('delete', self::$module_id, $item->get_properties());
                    }
                }
            }
            WikiService::clear_cache();

            AppContext::get_response()->redirect(WikiUrlBuilder::manage(), $this->lang['warning.process.success']);
        }
    }

    private function check_authorizations()
    {
        if (!WikiAuthorizationsService::check_authorizations()->moderation())
        {
            $error_controller = PHPBoostErrors::user_not_authorized();
            DispatchManager::redirect($error_controller);
        }
    }

    private function generate_response($page = 1)
    {
        $response = new SiteDisplayResponse($this->view);

        $graphical_environment = $response->get_graphical_environment();
        $graphical_environment->set_page_title($this->lang['wiki.items.management'], $this->config->get_module_name(), $page);
        $graphical_environment->get_seo_meta_data()->set_canonical_url(WikiUrlBuilder::manage());

        $breadcrumb = $graphical_environment->get_breadcrumb();
        $breadcrumb->add($this->config->get_module_name(), WikiUrlBuilder::home());

        $breadcrumb->add($this->lang['wiki.items.management'], WikiUrlBuilder::manage());

        return $response;
    }
}
?>
