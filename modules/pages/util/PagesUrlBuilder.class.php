<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 04 05
*/

/**
 * URL builder for the pages module.
 *
 * Frontend pages (public) are served from the site root — no /pages/ prefix.
 * Administration pages keep the /pages/ prefix so they are reachable through
 * the standard module dispatcher and are never confused with frontend content.
 *
 * Frontend dispatcher : '' (root)
 * Admin dispatcher    : '/pages' (standard module path)
 */
class PagesUrlBuilder
{
    /** Dispatcher used for frontend (public) URLs — resolves at site root. */
    private static string $frontend_dispatcher = '';

    /** Dispatcher used for administration URLs — keeps the /pages/ prefix. */
    private static string $admin_dispatcher = '/pages';

    // -------------------------------------------------------------------------
    // Frontend URLs (no /pages/ prefix)
    // -------------------------------------------------------------------------

    /**
     * Home / root-category page.
     *   site.ext/
     *
     * @return Url
     */
    public static function home(): Url
    {
        return DispatchManager::get_url(self::$frontend_dispatcher, '/');
    }

    /**
     * Category or root page.
     *   site.ext/{id}-{rewrited_name}/           (sub-category)
     *   site.ext/                                 (root category, id == 0)
     *
     * @param int    $id            Category id (0 for root).
     * @param string $rewrited_name URL-safe category name.
     * @param string $sort_field    Optional sort field.
     * @param string $sort_mode     Optional sort mode.
     * @param int    $page          Page number (1 = omitted from URL).
     * @param int    $subcategories_page Subcategory page (1 = omitted).
     * @return Url
     */
    public static function display_category(int $id, string $rewrited_name, string $sort_field = '', string $sort_mode = '', int $page = 1, int $subcategories_page = 1): Url
    {
        $category           = ($id > 0 || $sort_field !== '') ? $id . '-' . $rewrited_name . '/' : '';
        $sort_field_part    = ($sort_field !== '') ? $sort_field . '/' : '';
        $sort_mode_part     = ($sort_mode !== '') ? $sort_mode . '/' : '';
        $page_part          = ($page !== 1 || $subcategories_page !== 1) ? $page . '/' : '';
        $subcategories_part = ($subcategories_page !== 1) ? $subcategories_page . '/' : '';

        return DispatchManager::get_url(
            self::$frontend_dispatcher,
            '/' . $category . $sort_field_part . $sort_mode_part . $page_part . $subcategories_part
        );
    }

    /**
     * Pagination URL for a category — contains the literal string '%d' in
     * place of the page number so that ModulePagination can sprintf it.
     *
     * @param int    $id
     * @param string $rewrited_name
     * @param string $sort_field
     * @param string $sort_mode
     * @param int    $subcategories_page
     * @return Url
     */
    public static function display_category_pagination(int $id, string $rewrited_name, string $sort_field = '', string $sort_mode = '', int $subcategories_page = 1): Url
    {
        $category           = ($id > 0 || $sort_field !== '') ? $id . '-' . $rewrited_name . '/' : '';
        $sort_field_part    = ($sort_field !== '') ? $sort_field . '/' : '';
        $sort_mode_part     = ($sort_mode !== '') ? $sort_mode . '/' : '';
        $subcategories_part = ($subcategories_page !== 1) ? $subcategories_page . '/' : '';

        // Build base URL string with general config path prefix (same logic as DispatchManager)
        $base = GeneralConfig::load()->get_site_path() . '/' . $category . $sort_field_part . $sort_mode_part . '%d/' . $subcategories_part;
        return new Url($base);
    }

    /**
     * Pagination URL for subcategories of a given category page.
     *
     * @param int    $id
     * @param string $rewrited_name
     * @param string $sort_field
     * @param string $sort_mode
     * @param int    $page Current items page.
     * @return Url
     */
    public static function display_category_subcategories_pagination(int $id, string $rewrited_name, string $sort_field = '', string $sort_mode = '', int $page = 1): Url
    {
        $category        = ($id > 0 || $sort_field !== '') ? $id . '-' . $rewrited_name . '/' : '';
        $sort_field_part = ($sort_field !== '') ? $sort_field . '/' : '';
        $sort_mode_part  = ($sort_mode !== '') ? $sort_mode . '/' : '';
        $page_part       = ($page !== 1) ? $page . '/' : '';

        $base = GeneralConfig::load()->get_site_path() . '/' . $category . $sort_field_part . $sort_mode_part . $page_part . '%d/';
        return new Url($base);
    }

    /**
     * Item display page.
     *   site.ext/{id_category}-{rewrited_name_category}/{id}-{rewrited_title}/
     *
     * @param int    $id_category
     * @param string $rewrited_name_category
     * @param int    $id
     * @param string $rewrited_title
     * @param string $anchor Optional URL anchor (e.g. '#comments-list').
     * @return Url
     */
    public static function display(int $id_category, string $rewrited_name_category, int $id, string $rewrited_title, string $anchor = ''): Url
    {
        return DispatchManager::get_url(
            self::$frontend_dispatcher,
            '/' . $id_category . '-' . $rewrited_name_category . '/' . $id . '-' . $rewrited_title . '/' . $anchor
        );
    }

    /**
     * Item display page with anchor on the comments section.
     *
     * @return Url
     */
    public static function display_comments(int $id_category, string $rewrited_name_category, int $id, string $rewrited_title): Url
    {
        return self::display($id_category, $rewrited_name_category, $id, $rewrited_title, '#comments-list');
    }

    // -------------------------------------------------------------------------
    // Administration URLs (keep /pages/ prefix)
    // -------------------------------------------------------------------------

    /**
     * Module configuration page.
     *   site.ext/pages/admin/config
     *
     * @return Url
     */
    public static function configuration(): Url
    {
        return DispatchManager::get_url(self::$admin_dispatcher, '/admin/config');
    }

    /**
     * Categories management page.
     *   site.ext/pages/categories
     *
     * @return Url
     */
    public static function manage_categories(): Url
    {
        return DispatchManager::get_url(self::$admin_dispatcher, '/categories/');
    }

    /**
     * Items management page.
     *   site.ext/pages/manage
     *
     * @return Url
     */
    public static function manage(): Url
    {
        return DispatchManager::get_url(self::$admin_dispatcher, '/manage/');
    }

    /**
     * Add item page.
     *   site.ext/pages/add  or  site.ext/pages/add/{id_category}/
     *
     * @return Url
     */
    public static function add(?int $id_category = null): Url
    {
        $cat = !empty($id_category) ? $id_category . '/' : '';
        return DispatchManager::get_url(self::$admin_dispatcher, '/add/' . $cat);
    }

    /**
     * Pending items page.
     *   site.ext/pages/pending
     *
     * @return Url
     */
    public static function display_pending(string $sort_field = '', string $sort_mode = '', int $page = 1): Url
    {
        $sort_field_part = ($sort_field !== '') ? $sort_field . '/' : '';
        $sort_mode_part  = ($sort_mode !== '') ? $sort_mode . '/' : '';
        $page_part       = ($page !== 1) ? $page . '/' : '';
        return DispatchManager::get_url(self::$admin_dispatcher, '/pending/' . $sort_field_part . $sort_mode_part . $page_part);
    }

    /**
     * Member items page.
     *   site.ext/pages/member/{id}
     *
     * @return Url
     */
    public static function display_member_items(?int $user_id = null, string $sort_field = '', string $sort_mode = '', int $page = 1): Url
    {
        $sort_field_part = ($sort_field !== '') ? $sort_field . '/' : '';
        $sort_mode_part  = ($sort_mode !== '') ? $sort_mode . '/' : '';
        $page_part       = ($page !== 1) ? $page . '/' : '';
        return DispatchManager::get_url(self::$admin_dispatcher, '/member/' . $user_id . '/' . $sort_field_part . $sort_mode_part . $page_part);
    }

    /**
     * Reorder items page.
     *   site.ext/pages/reorder/          (root category)
     *   site.ext/pages/reorder/{id}-{name}/  (sub-category)
     *
     * @return Url
     */
    public static function reorder_items(int $id_category = 0, string $rewrited_name = ''): Url
    {
        $cat = ($id_category > 0) ? $id_category . '-' . $rewrited_name . '/' : '';
        return DispatchManager::get_url(self::$admin_dispatcher, '/reorder/' . $cat);
    }
}
?>
