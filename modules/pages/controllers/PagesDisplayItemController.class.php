<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 04 05
*/

/**
 * Displays a single pages item.
 *
 * Extends DefaultDisplayItemController to replace all /pages/... URLs with
 * root-based URLs (no module prefix) for the public-facing frontend.
 *
 * Overrides:
 *  - init()              : current_url uses PagesUrlBuilder::display()
 *  - generate_response() : breadcrumb category links use PagesUrlBuilder::display_category()
 */
class PagesDisplayItemController extends DefaultDisplayItemController
{
    /**
     * Sets current_url to the root-based frontend URL of the item.
     * This URL is used for the canonical tag, view-counter referrer check,
     * and is passed to the comments component.
     *
     * {@inheritdoc}
     */
    protected function init()
    {
        $category = $this->get_item()->get_category();
        $this->current_url = PagesUrlBuilder::display(
            $category->get_id(),
            $category->get_rewrited_name(),
            $this->get_item()->get_id(),
            $this->get_item()->get_rewrited_title()
        );
    }

    /**
     * Builds the page response with a breadcrumb whose category links point to
     * root-based frontend URLs instead of /pages/...
     *
     * {@inheritdoc}
     */
    protected function generate_response()
    {
        $response = new SiteDisplayResponse($this->view);

        $graphical_environment = $response->get_graphical_environment();
        $graphical_environment->set_page_title(
            $this->get_item()->get_title(),
            (self::get_module_configuration()->has_categories() && $this->get_item()->get_category()->get_id() != Category::ROOT_CATEGORY
                ? $this->get_item()->get_category()->get_name() . ' - '
                : ''
            ) . self::get_module_configuration()->get_name()
        );
        $graphical_environment->get_seo_meta_data()->set_canonical_url($this->current_url);

        if (self::get_module_configuration()->has_rich_items() && $this->module_item->content_field_enabled())
            $graphical_environment->get_seo_meta_data()->set_description($this->get_item()->get_real_summary());

        if (self::get_module_configuration()->has_rich_items() && $this->get_item()->has_thumbnail())
            $graphical_environment->get_seo_meta_data()->set_picture_url($this->get_item()->get_thumbnail());

        // Breadcrumb: module home -> parent categories -> item title
        // Category links use PagesUrlBuilder (root-based, no /pages/ prefix).
        $breadcrumb = $graphical_environment->get_breadcrumb();
        // Pass 'pages' explicitly: Environment::get_running_module_name() returns the first
        // URL segment (e.g. '1-test') when the request URL is a root-based frontend URL,
        // not the actual module name.
        $breadcrumb->add(self::get_module_configuration()->get_name(), ModulesUrlBuilder::home('pages'));

        if (self::get_module_configuration()->has_categories())
        {
            $categories = array_reverse(
                CategoriesService::get_categories_manager()->get_parents($this->get_item()->get_category()->get_id(), true)
            );
            foreach ($categories as $id => $category)
            {
                if ($category->get_id() != Category::ROOT_CATEGORY)
                    $breadcrumb->add(
                        $category->get_name(),
                        PagesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())
                    );
            }
        }

        $breadcrumb->add($this->get_item()->get_title(), $this->current_url);

        return $response;
    }
}
?>
