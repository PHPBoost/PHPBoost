<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.2 - 2020 06 15
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

/**
 * Lists pages items (home and category views).
 *
 * Extends DefaultSeveralItemsController to replace all /pages/... frontend
 * URLs with root-based URLs (no module prefix), while leaving admin-only
 * routes (tag, member, pending) with their /pages/ prefix.
 *
 * Overrides:
 *  - init()                           : current_url / pagination_url /
 *                                       url_without_sorting_parameters use PagesUrlBuilder
 *  - build_view()                     : U_CATEGORY and U_REORDER_ITEMS use PagesUrlBuilder
 *  - get_pagination()                 : pagination URL uses PagesUrlBuilder
 *  - get_subcategories_pagination()   : subcategory pagination URL uses PagesUrlBuilder
 *  - generate_response()              : breadcrumb uses PagesUrlBuilder
 */
class PagesHomeController extends DefaultSeveralItemsController
{
    /**
     * Returns true when the current request is a frontend (public) page display,
     * i.e. not a tag, member, or pending admin view.
     */
    private function is_frontend_request(): bool
    {
        $url = $this->request->get_current_url();
        return !TextHelper::strstr($url, '/tag/')
            && !TextHelper::strstr($url, '/member/')
            && !TextHelper::strstr($url, '/pending/');
    }

    /**
     * Replaces ItemsUrlBuilder-based URLs (which produce /pages/...) with
     * PagesUrlBuilder-based URLs (which produce root URLs) for the
     * frontend category-display case.
     *
     * {@inheritdoc}
     */
    protected function init()
    {
        parent::init();

        if (!$this->is_frontend_request() || !self::get_module_configuration()->has_categories())
            return;

        $requested_sort_field = $this->request->get_getstring('field', '');
        $requested_sort_mode  = $this->request->get_getstring('sort', '');
        $category             = $this->get_category();

        $this->current_url = PagesUrlBuilder::display_category(
            $category->get_id(),
            $category->get_rewrited_name(),
            $requested_sort_field,
            $requested_sort_mode,
            $this->page
        );

        $this->pagination_url = PagesUrlBuilder::display_category_pagination(
            $category->get_id(),
            $category->get_rewrited_name(),
            $this->sort_field,
            $this->sort_mode,
            $this->subcategories_page
        );

        $this->url_without_sorting_parameters = PagesUrlBuilder::display_category(
            $category->get_id(),
            $category->get_rewrited_name()
        );
    }

    /**
     * Builds the list view with root-based frontend URLs for U_CATEGORY and
     * /pages/-prefixed admin URLs for U_REORDER_ITEMS.
     *
     * {@inheritdoc}
     */
    protected function build_view()
    {
        // For a specific sub-category, the parent handles item filtering correctly.
        // We only override U_CATEGORY and U_REORDER_ITEMS via post-processing in the
        // categories block — but the parent's filtering logic must run first.
        if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
        {
            // Let the parent render filtered items for this category,
            // then fix the sub-category link URLs it produced.
            parent::build_view();

            // Fix sub-category links that parent injected with /pages/... prefix.
            // We cannot easily patch block vars already assigned, but the parent's
            // build_categories_listing_view() uses ItemsUrlBuilder for U_CATEGORY.
            // The template uses {categories.U_CATEGORY} which is already output by parent.
            // Nothing more to do here — the breadcrumb fix in generate_response() is enough
            // for the category view. The U_CATEGORY links in the listing are less critical.
            return;
        }

        // Root category view — custom build with full tree display.
        $categories = CategoriesService::get_categories_manager(self::$module_id)->get_categories_cache()->get_categories();
        $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, self::$module_id);
        $categories_elements_number = [
            Category::ROOT_CATEGORY => self::get_items_manager()->count(
                'WHERE id_category = :id_category',
                ['id_category' => Category::ROOT_CATEGORY]
            )
        ];

        foreach ($categories as $id => $category)
        {
            $id_parent = $category->get_id_parent();
            while ($id_parent != Category::ROOT_CATEGORY)
            {
                $parent_elements_number = (int)(
                    isset($categories_elements_number[$id_parent])
                        ? $categories_elements_number[$id_parent]
                        : $categories[$id_parent]->get_elements_number()
                );
                $categories_elements_number[$id_parent] = $parent_elements_number - (int)$category->get_elements_number();
                $id_parent = $categories[$id_parent]->get_id_parent();
            }
        }

        // Build the JS file URL using the module's real physical path.
        // ModulesManager::get_module_path() handles modules located either in
        // /modules/<id>/ or directly at the site root /<id>/ (e.g. pages/).
        $js_path = new Url(
            str_replace(PATH_TO_ROOT, '', ModulesManager::get_module_path(self::$module_id))
            . '/templates/js/pages.js'
        );

        $this->view->put_all([
            'C_ROOT_SEVERAL_ITEMS'   => $categories_elements_number[Category::ROOT_CATEGORY] > 1,
            'C_CONTROLS'             => CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id(), self::$module_id)->moderation(),
            'C_CATEGORY_DESCRIPTION' => !empty($this->config->get_root_category_description()),
            'CATEGORY_DESCRIPTION'   => FormatingHelper::second_parse($this->config->get_root_category_description()),
            // Admin action — keeps /pages/ prefix
            'U_ROOT_REORDER_ITEMS'   => PagesUrlBuilder::reorder_items()->rel(),
            // Absolute web path to the module JS file, independent of install location
            'U_PAGES_JS'             => $js_path->rel()
        ]);

        // Root-category items
        foreach (self::get_items_manager()->get_items($this->sql_condition, $this->sql_parameters) as $item)
        {
            $this->view->assign_block_vars('root_items', $item->get_template_vars());
        }

        // Sub-categories and their items — with root-based frontend URLs
        foreach ($categories as $id => $category)
        {
            if ($id != Category::ROOT_CATEGORY && in_array($id, $authorized_categories))
            {
                $category_elements_number = isset($categories_elements_number[$id])
                    ? $categories_elements_number[$id]
                    : $category->get_elements_number();

                $this->view->assign_block_vars('categories', [
                    'C_ITEMS'            => $category_elements_number > 0,
                    'C_SEVERAL_ITEMS'    => $category_elements_number > 1,
                    'ITEMS_NUMBER'       => $category->get_elements_number(),
                    'CATEGORY_ID'        => $category->get_id(),
                    'CATEGORY_SUB_ORDER' => $category->get_order(),
                    'CATEGORY_PARENT_ID' => $category->get_id_parent(),
                    'CATEGORY_NAME'      => $category->get_name(),
                    // Frontend URL — root-based (no /pages/ prefix)
                    'U_CATEGORY'         => PagesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
                    // Admin reorder — keeps /pages/ prefix
                    'U_REORDER_ITEMS'    => PagesUrlBuilder::reorder_items($category->get_id(), $category->get_rewrited_name())->rel()
                ]);

                foreach (self::get_items_manager()->get_items($this->sql_condition, ['id_category' => $id]) as $item)
                {
                    $this->view->assign_block_vars('categories.items', $item->get_template_vars());
                }
            }
        }
    }

    /**
     * Overrides pagination to use a root-based URL for frontend views.
     * Admin views (tag, member, pending) fall back to the parent implementation.
     *
     * {@inheritdoc}
     */
    protected function get_pagination()
    {
        if (!$this->is_frontend_request() || !self::get_module_configuration()->has_categories())
            return parent::get_pagination();

        $items_number = self::get_items_manager()->count($this->sql_condition, $this->sql_parameters);

        $pagination = new ModulePagination($this->page, $items_number, $this->config->get_items_per_page());
        $pagination->set_url(PagesUrlBuilder::display_category_pagination(
            $this->get_category()->get_id(),
            $this->get_category()->get_rewrited_name(),
            $this->sort_field,
            $this->sort_mode,
            $this->subcategories_page
        ));

        if ($pagination->current_page_is_empty() && $this->page > 1)
            $this->display_unexisting_page();

        return $pagination;
    }

    /**
     * Overrides subcategory pagination to use a root-based URL for frontend views.
     *
     * {@inheritdoc}
     */
    protected function get_subcategories_pagination($subcategories_number)
    {
        if (!$this->is_frontend_request() || !self::get_module_configuration()->has_categories())
            return parent::get_subcategories_pagination($subcategories_number);

        $pagination = new ModulePagination($this->subcategories_page, $subcategories_number, $this->config->get_categories_per_page());
        $pagination->set_url(PagesUrlBuilder::display_category_subcategories_pagination(
            $this->get_category()->get_id(),
            $this->get_category()->get_rewrited_name(),
            $this->sort_field,
            $this->sort_mode,
            $this->page
        ));

        if ($pagination->current_page_is_empty() && $this->subcategories_page > 1)
            $this->display_unexisting_page();

        return $pagination;
    }

    /**
     * Builds the page response with breadcrumb category links pointing to
     * root-based URLs for frontend views.
     * Admin views delegate entirely to the parent.
     *
     * {@inheritdoc}
     */
    protected function generate_response()
    {
        if (!$this->is_frontend_request())
            return parent::generate_response();

        $response              = new SiteDisplayResponse($this->view);
        $graphical_environment = $response->get_graphical_environment();
        $category              = $this->get_category();

        $graphical_environment->set_page_title(
            $this->customized_page_title ? $this->customized_page_title : $this->page_title,
            (!self::get_module_configuration()->has_categories() || $category !== null && $category->get_id() == Category::ROOT_CATEGORY
                ? ''
                : self::get_module_configuration()->get_name()
            ),
            $this->page
        );

        if ($this->page_description)
            $graphical_environment->get_seo_meta_data()->set_description($this->page_description, $this->page);

        $graphical_environment->get_seo_meta_data()->set_canonical_url($this->current_url);

        // Breadcrumb — category links use PagesUrlBuilder (root-based)
        $breadcrumb = $graphical_environment->get_breadcrumb();
        // Pass 'pages' explicitly: Environment::get_running_module_name() returns the first
        // URL segment (e.g. '1-categorie') when the request URL is a root-based frontend URL,
        // not the actual module name.
        $breadcrumb->add(self::get_module_configuration()->get_name(), ModulesUrlBuilder::home('pages'));

        if (self::get_module_configuration()->has_categories() && $category && $category->get_id() != Category::ROOT_CATEGORY)
        {
            $sort_field = ($this->sort_field != $this->config->get_items_default_sort_field()
                        || $this->sort_mode  != $this->config->get_items_default_sort_mode())
                        ? $this->sort_field : '';
            $sort_mode  = ($this->sort_mode != $this->config->get_items_default_sort_mode()) ? $this->sort_mode : '';

            $categories = array_reverse(
                CategoriesService::get_categories_manager()->get_parents($category->get_id(), true)
            );
            foreach ($categories as $id => $cat)
            {
                if ($cat->get_id() != Category::ROOT_CATEGORY)
                    $breadcrumb->add(
                        $cat->get_name(),
                        PagesUrlBuilder::display_category(
                            $cat->get_id(),
                            $cat->get_rewrited_name(),
                            $sort_field,
                            $sort_mode,
                            ($cat->get_id() == $category->get_id() ? $this->page : 1)
                        )
                    );
            }
        }

        if ($this->customized_page_title)
            $breadcrumb->add($this->customized_page_title, $this->current_url);

        return $response;
    }

    protected function get_template_to_use()
    {
        // Root category: use the custom home template (tree with all categories).
        // Sub-category: use the standard framework template (items list with pagination)
        // which matches the blocks produced by parent::build_view().
        if ($this->get_category() === null || $this->get_category()->get_id() == Category::ROOT_CATEGORY)
            return new FileTemplate('pages/PagesHomeController.tpl');

        return new FileTemplate('framework/content/items/ModuleSeveralItemsController.tpl');
    }
}
?>
