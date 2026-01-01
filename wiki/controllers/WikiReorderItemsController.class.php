<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 6.0 - 2022 11 18
 */

class WikiReorderItemsController extends DefaultModuleController
{
    private $category;

    protected function get_template_to_use()
    {
        return new FileTemplate('wiki/WikiReorderItemsController.tpl');
    }

    public function execute(HTTPRequestCustom $request)
    {
        $this->check_authorizations();

        if ($request->get_value('submit', false))
        {
            $this->update_position($request);
            WikiService::clear_cache();
            AppContext::get_response()->redirect(WikiUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()), $this->lang['warning.success.position.update']);
        }

        $this->build_view($request);

        return $this->generate_response();
    }

    private function build_view(HTTPRequestCustom $request)
    {
        $now = new Date();
        $result = PersistenceContext::get_querier()->select('SELECT i.*, c.*, member.*, com.comments_number, notes.average_notes, notes.notes_number, note.note
            FROM ' . WikiSetup::$wiki_articles_table . ' i
            LEFT JOIN ' . WikiSetup::$wiki_contents_table . ' c ON c.item_id = i.id
            LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = c.author_user_id
            LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = i.id AND com.module_id = \'wiki\'
            LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = i.id AND notes.module_name = \'wiki\'
            LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = i.id AND note.module_name = \'wiki\' AND note.user_id = :user_id
            WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
            AND i.id_category = :id_category
            AND c.active_content = 1
            ORDER BY i.i_order', [
                'id_category' => $this->get_category()->get_id(),
                'user_id' => AppContext::get_current_user()->get_id(),
                'timestamp_now' => $now->get_timestamp()
            ]
        );

        $category_description = FormatingHelper::second_parse($this->get_category()->get_description());

        $this->view->put_all([
            'C_ROOT_CATEGORY'        => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
            'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && !empty($category_description),
            'C_CATEGORY_DESCRIPTION' => !empty($category_description),
            'C_ITEMS'                => $result->get_rows_count() > 0,
            'C_SEVERAL_ITEMS'        => $result->get_rows_count() > 1,

            'ID_CAT'               => $this->get_category()->get_id(),
            'CATEGORY_NAME'        => $this->get_category()->get_name(),
            'CATEGORY_DESCRIPTION' => $category_description,
            'ITEMS_NUMBER'         => $result->get_rows_count(),

            'U_CATEGORY_THUMBNAIL' => $this->get_category()->get_thumbnail()->rel(),
            'U_EDIT_CATEGORY'      => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? WikiUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($this->get_category()->get_id(), 'wiki')->rel()
        ]);

        while ($row = $result->fetch())
        {
            $item = new WikiItem();
            $item->set_properties($row);

            $this->view->assign_block_vars('items', [
                'ID' => $item->get_id(),
                'TITLE' => $item->get_title(),
                'U_EDIT' => WikiUrlBuilder::edit($item->get_id())->rel(),
                'U_DELETE' => WikiUrlBuilder::delete($item->get_id(), 0)->rel(),
            ]);
        }
        $result->dispose();
    }

    private function get_category()
    {
        if ($this->category === null)
        {
            $id = AppContext::get_request()->get_getint('id_category', 0);
            if (!empty($id))
            {
                try {
                    $this->category = CategoriesService::get_categories_manager()->get_categories_cache()->get_category($id);
                } catch (CategoryNotFoundException $e) {
                    $error_controller = PHPBoostErrors::unexisting_page();
                    DispatchManager::redirect($error_controller);
                }
            }
            else
            {
                $this->category = CategoriesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
            }
        }
        return $this->category;
    }

    private function check_authorizations()
    {
        $id_category = $this->get_category()->get_id();
        if (!CategoriesAuthorizationsService::check_authorizations($id_category)->moderation())
        {
            $error_controller = PHPBoostErrors::user_not_authorized();
            DispatchManager::redirect($error_controller);
        }
    }

    private function update_position(HTTPRequestCustom $request)
    {
        $questions_list = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));
        foreach($questions_list as $position => $tree)
        {
            WikiService::update_position($tree->id, $position);
        }
    }

    private function generate_response()
    {
        $response = new SiteDisplayResponse($this->view);

        $graphical_environment = $response->get_graphical_environment();

        if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
            $graphical_environment->set_page_title($this->get_category()->get_name(), $this->config->get_module_name());
        else
            $graphical_environment->set_page_title($this->config->get_module_name());

        $description = $this->get_category()->get_description() . ' ' . $this->lang['items.reorder'];
        if (empty($description))
            $description = StringVars::replace_vars($this->lang['wiki.seo.description.root'], ['site' => GeneralConfig::load()->get_site_name()]) . ($this->get_category()->get_id() != Category::ROOT_CATEGORY ? ' ' . $this->lang['category.category'] . ' ' . $this->get_category()->get_name() : '') . ' ' . $this->lang['wiki.questions.reorder'];
        $graphical_environment->get_seo_meta_data()->set_description($description);
        $graphical_environment->get_seo_meta_data()->set_canonical_url(WikiUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));

        $breadcrumb = $graphical_environment->get_breadcrumb();
        $breadcrumb->add($this->config->get_module_name(), WikiUrlBuilder::home());

        $categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
        foreach ($categories as $id => $category)
        {
            if ($category->get_id() != Category::ROOT_CATEGORY)
                $breadcrumb->add($category->get_name(), WikiUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
        }

        $breadcrumb->add($this->lang['items.reorder'], WikiUrlBuilder::reorder_items($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));

        return $response;
    }
}
