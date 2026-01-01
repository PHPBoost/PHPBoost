<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 6.0 - 2023 10 11
 */

class WikiIndexController extends DefaultModuleController
{
    private $category;

    protected function get_template_to_use()
    {
        return new FileTemplate('wiki/WikiIndexController.tpl');
    }

    public function execute(HTTPRequestCustom $request)
    {
        $this->check_authorizations();

        $this->build_view();

        return $this->generate_response();
    }

    private function build_view()
    {
        $now = new Date();
        $categories = CategoriesService::get_categories_manager(self::$module_id)->get_categories_cache()->get_categories();
        $authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, self::$module_id);

        $this->view->put('MODULE_NAME', $this->config->get_module_name());

        foreach ($categories as $id => $category)
        {
            if ($id != Category::ROOT_CATEGORY && in_array($id, $authorized_categories))
            {
                $category_elements_number = isset($categories_elements_number[$id]) ? $categories_elements_number[$id] : $category->get_elements_number();
                $this->view->assign_block_vars('categories', [
                    'C_CONTROLS'             => WikiAuthorizationsService::check_authorizations()->moderation(),
                    'C_ITEMS'                => $category_elements_number > 0,
                    'C_SEVERAL_ITEMS'        => $category_elements_number > 1,
                    'C_DISPLAY_DESCRIPTION'  => !empty($category->get_description()) && $this->config->get_display_description(),
                    'C_CATEGORY_DESCRIPTION' => !empty($category->get_description()),

                    'ITEMS_NUMBER'          => $category->get_elements_number(),
                    'CATEGORY_ID'           => $category->get_id(),
                    'CATEGORY_SUB_ORDER'    => $category->get_order(),
                    'CATEGORY_PARENT_ID'    => $category->get_id_parent(),
                    'CATEGORY_NAME'         => $category->get_name(),
                    'CATEGORY_DESCRIPTION'  => $category->get_description(),

                    'U_CATEGORY_THUMBNAIL' => $category->get_thumbnail()->rel(),
                    'U_CATEGORY'           => WikiUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), self::$module_id)->rel(),
                    'U_REORDER_ITEMS'      => WikiUrlBuilder::reorder_items($category->get_id(), $category->get_rewrited_name())->rel()
                ]);
            }
        }
    }

    private function get_category()
    {
        if ($this->category === null)
        {
            $id = AppContext::get_request()->get_getint('id_category', 0);
            if (!empty($id))
            {
                try {
                    $this->category = CategoriesService::get_categories_manager('wiki')->get_categories_cache()->get_category($id);
                } catch (CategoryNotFoundException $e) {
                    $error_controller = PHPBoostErrors::unexisting_page();
                    DispatchManager::redirect($error_controller);
                }
            }
            else
            {
                $this->category = CategoriesService::get_categories_manager('wiki')->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
            }
        }
        return $this->category;
    }

    private function check_authorizations()
    {
        if (!WikiAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
        {
            $error_controller = PHPBoostErrors::user_not_authorized();
            DispatchManager::redirect($error_controller);
        }
    }

    private function generate_response()
    {
        $response = new SiteDisplayResponse($this->view);

        $graphical_environment = $response->get_graphical_environment();
        $graphical_environment->set_page_title($this->lang['wiki.overview'], $this->config->get_module_name());
        $description = StringVars::replace_vars($this->lang['wiki.seo.description.root'], ['site' => GeneralConfig::load()->get_site_name()]);
        $graphical_environment->get_seo_meta_data()->set_description($description);
        $graphical_environment->get_seo_meta_data()->set_canonical_url(WikiUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name()));

        $breadcrumb = $graphical_environment->get_breadcrumb();
        $breadcrumb->add($this->config->get_module_name(), WikiUrlBuilder::home());
        $breadcrumb->add($this->lang['wiki.overview'], WikiUrlBuilder::overview());

        return $response;
    }

    public static function get_view()
    {
        $object = new self('wiki');
        $object->check_authorizations();
        $object->build_view();
        return $object->view;
    }
}
?>
