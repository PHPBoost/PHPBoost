<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2023 03 27
 * @since       PHPBoost 6.0 - 2022 11 18
 */

class WikiItemHistoryController extends DefaultModuleController
{
    protected function get_template_to_use()
    {
        return new FileTemplate('wiki/WikiItemHistoryController.tpl');
    }

    public function execute(HTTPRequestCustom $request)
    {
        $this->check_authorizations();

        $this->build_view($request);

        return $this->generate_response($request);
    }

    public function build_view(HTTPRequestCustom $request)
    {
        $now = new Date();
        $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->is_summary_displayed_to_guests());

        $condition = 'WHERE id_category IN :authorized_categories';
        $parameters = [
            'authorized_categories' => $authorized_categories,
            'timestamp_now' => $now->get_timestamp()
        ];

        $result = PersistenceContext::get_querier()->select('SELECT i.*, c.*, member.*, com.comments_number, notes.average_notes, notes.notes_number, note.note
            FROM ' . WikiSetup::$wiki_articles_table . ' i
            LEFT JOIN ' . WikiSetup::$wiki_contents_table . ' c ON c.item_id = i.id
            LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = c.author_user_id
            LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = i.id AND com.module_id = \'wiki\'
            LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = i.id AND notes.module_name = \'wiki\'
            LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = i.id AND note.module_name = \'wiki\' AND note.user_id = :user_id
            ' . $condition . '
            AND c.item_id = :id
            ORDER BY c.update_date DESC', array_merge($parameters, [
                'user_id' => AppContext::get_current_user()->get_id(),
                'id' => $this->get_item()->get_id()
            ])
        );

        $this->view->put_all([
            'MODULE_NAME'       => $this->config->get_module_name(),
            'C_ITEMS'           => $result->get_rows_count() > 0,
            'C_SEVERAL_ITEMS'   => $result->get_rows_count() > 1,
            'C_CONTROLS'        => WikiAuthorizationsService::check_authorizations($this->get_item()->get_category()->get_id())->moderation() || WikiAuthorizationsService::check_authorizations()->manage_archives(),
            'C_RESTORE' 	    => WikiAuthorizationsService::check_authorizations()->manage_archives(),

            'ITEM_TITLE' => $this->item->get_title(),

            'CATEGORIES_PER_ROW' => $this->config->get_categories_per_row(),
            'ITEMS_PER_ROW'      => $this->config->get_items_per_row(),
            'TABLE_COLSPAN'      => 4,
        ]);

        while ($row = $result->fetch())
        {
            $this->item = new WikiItem();
            $this->item->set_properties($row);

            $this->view->assign_block_vars('items', array_merge($this->item->get_template_vars(), [
                'C_ACTIVE_CONTENT' => $this->item->get_item_content()->get_active_content() == 1,
                'U_ARCHIVE' 	   => WikiUrlBuilder::archive($this->item->get_id(), $this->item->get_item_content()->get_content_id())->rel(),
                'U_DELETE_CONTENT' => WikiUrlBuilder::delete($this->item->get_id(), $this->item->get_item_content()->get_content_id())->rel(),
                'U_RESTORE' 	   => WikiUrlBuilder::restore($this->item->get_id(), $this->item->get_item_content()->get_content_id())->rel()
            ]));
        }
        $result->dispose();
    }

    private function check_authorizations()
    {
        if (!(WikiAuthorizationsService::check_authorizations()->write() || WikiAuthorizationsService::check_authorizations()->contribution() || WikiAuthorizationsService::check_authorizations()->moderation()))
        {
            $error_controller = PHPBoostErrors::user_not_authorized();
            DispatchManager::redirect($error_controller);
        }
    }

    private function get_item()
    {
        $id = AppContext::get_request()->get_getint('id', 0);
        try {
            $this->item = WikiService::get_item($id);
        } catch (RowNotFoundException $e) {
            $error_controller = PHPBoostErrors::unexisting_page();
            DispatchManager::redirect($error_controller);
        }
        return $this->item;
    }

    private function generate_response(HTTPRequestCustom $request)
    {
        $page_title = $this->lang['wiki.item.history'];
        $response = new SiteDisplayResponse($this->view);

        $graphical_environment = $response->get_graphical_environment();
        $graphical_environment->set_page_title($page_title, $this->config->get_module_name());
        $graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['wiki.seo.description.history'], ['item' => $this->item->get_title()]));
        $graphical_environment->get_seo_meta_data()->set_canonical_url(WikiUrlBuilder::history($this->item->get_id()));

        $breadcrumb = $graphical_environment->get_breadcrumb();
        $breadcrumb->add($this->config->get_module_name(), WikiUrlBuilder::home());
        $breadcrumb->add($this->item->get_title(), WikiUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));
        $breadcrumb->add($page_title, WikiUrlBuilder::history($this->item->get_id()));

        return $response;
    }
}
?>
