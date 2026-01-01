<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 6.0 - 2022 11 22
*/

class WikiUserExtensionPoint implements UserExtensionPoint
{
    public function get_publications_module_view($user_id)
    {
        return WikiUrlBuilder::display_member_items($user_id)->rel();
    }

    public function get_publications_module_name()
    {
        return LangLoader::get_message('wiki.module.title', 'common', 'wiki');
    }

    public function get_publications_module_id()
    {
        return 'wiki';
    }

    public function get_publications_module_icon()
    {
        return 'fa fa-fw fa-graduation-cap';
    }

    public function get_publications_number($user_id)
    {
        return PersistenceContext::get_querier()->count(PREFIX . 'wiki_contents', 'WHERE author_user_id = :user_id AND active_content = 1', ['user_id' => $user_id]);
    }
}
