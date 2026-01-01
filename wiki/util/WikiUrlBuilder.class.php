<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 4.0 - 2014 02 02
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class WikiUrlBuilder
{
    private static $dispatcher = '/wiki';

    public static function configuration() : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/admin/config');
    }

    public static function manage() : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/manage/');
    }

    public static function display_category($id, $rewrited_name, $page = 1, $subcategories_page = 1) : Url
    {
        $category = $id > 0 ? $id . '-' . $rewrited_name . '/' : '';
        $page = $page !== 1 || $subcategories_page !== 1 ? $page . '/' : '';
        $subcategories_page = $subcategories_page !== 1 ? $subcategories_page . '/' : '';
        return DispatchManager::get_url(self::$dispatcher, '/' . $category . $page . $subcategories_page);
    }

    public static function display_tag($rewrited_name, $page = 1) : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/tag/' . $rewrited_name . '/' . $page);
    }

    public static function display_pending($page = 1) : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/pending/' . $page);
    }

    public static function display_member_items($user_id = null, $page = 1) : Url
    {
        $page = $page !== 1 ? $page . '/' : '';
        return DispatchManager::get_url(self::$dispatcher, '/member/' . $user_id . '/' . $page);
    }

    public static function add($id_category = null) : Url
    {
        $id_category = !empty($id_category) ? $id_category . '/' : '';
        return DispatchManager::get_url(self::$dispatcher, '/add/' . $id_category);
    }

    public static function history($id) : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/history/');
    }

    public static function archive($id,$content_id) : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/archive/' . $content_id . '/');
    }

    public static function edit($id) : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
    }

    public static function duplicate($id) : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/duplicate/');
    }

    public static function delete($id, $content_id) : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/' . $content_id . '/?' . 'token=' . AppContext::get_session()->get_token());
    }

    public static function delete_content($id, $content_id) : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/' . $content_id . '/?' . 'token=' . AppContext::get_session()->get_token());
    }

    public static function restore($id, $content_id) : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/restore/' . $content_id . '/?' . 'token=' . AppContext::get_session()->get_token());
    }

    public static function display($id_category, $rewrited_name_category, $id, $rewrited_title) : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id . '-' . $rewrited_title . '/');
    }

    public static function display_comments($id_category, $rewrited_name_category, $id, $rewrited_title) : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/' . $id_category . '-' . $rewrited_name_category . '/' . $id . '-' . $rewrited_title . '/#comments-list');
    }

    public static function reorder_items($id_category, $rewrited_name) : Url
    {
        $category = $id_category > 0 ? $id_category . '-' . $rewrited_name . '/' : '';
        return DispatchManager::get_url(self::$dispatcher, '/reorder/' . $category);
    }

    public static function explorer() : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/explorer/');
    }

    public static function overview() : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/overview/');
    }

    public static function track_item($item_id) : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/' . $item_id . '/track/');
    }

    public static function untrack_item($item_id) : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/' . $item_id . '/untrack/');
    }

    public static function tracked_member_items($user_id, $page = 1) : Url
    {
        $page = $page !== 1 ? $page . '/' : '';
        return DispatchManager::get_url(self::$dispatcher, '/tracked/' . $user_id . '/' . $page);
    }

    public static function home() : Url
    {
        return DispatchManager::get_url(self::$dispatcher, '/');
    }
}
?>
