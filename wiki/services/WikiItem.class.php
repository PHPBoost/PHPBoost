<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 6.0 - 2022 11 18
 */

class WikiItem
{
    private $id;
    private $id_category;
    private $title;
    private $rewrited_title;
    private $i_order;
    private $item_content;

    private $published;
    private $publishing_start_date;
    private $publishing_end_date;
    private $end_date_enabled;
    private $creation_date;

    private $views_number;
    private $notation;
    private $keywords;

    const THUMBNAIL_URL = '/templates/__default__/images/default_item.webp';

    const NOT_PUBLISHED        = 0;
    const PUBLISHED            = 1;
    const DEFERRED_PUBLICATION = 2;

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_id_category()
    {
        return $this->id_category;
    }

    public function set_id_category($id_category)
    {
        $this->id_category = $id_category;
    }

    public function get_title()
    {
        return $this->title;
    }

    public function set_title($title)
    {
        $this->title = $title;
    }

    public function get_rewrited_title()
    {
        return $this->rewrited_title;
    }

    public function set_rewrited_title($rewrited_title)
    {
        $this->rewrited_title = $rewrited_title;
    }

    public function get_i_order()
    {
        return $this->i_order;
    }

    public function set_i_order($i_order)
    {
        $this->i_order = $i_order;
    }

    public function set_item_content(WikiItemContent $item_content)
    {
        $this->item_content = $item_content;
    }

    public function get_item_content()
    {
        return $this->item_content;
    }

    public function get_category()
    {
        return CategoriesService::get_categories_manager('wiki')->get_categories_cache()->get_category($this->id_category);
    }

    public function get_publishing_state()
    {
        return $this->published;
    }

    public function set_publishing_state($published)
    {
        $this->published = $published;
    }

    public function is_published()
    {
        $now = new Date();
        return WikiAuthorizationsService::check_authorizations($this->id_category)->read() && ($this->get_publishing_state() == self::PUBLISHED || ($this->get_publishing_state() == self::DEFERRED_PUBLICATION && $this->get_publishing_start_date()->is_anterior_to($now) && ($this->end_date_enabled ? $this->get_publishing_end_date()->is_posterior_to($now) : true)));
    }

    public function get_status()
    {
        switch ($this->published) {
            case self::PUBLISHED:
                return LangLoader::get_message('common.status.published', 'common-lang');
            break;
            case self::DEFERRED_PUBLICATION:
                return LangLoader::get_message('common.status.deffered.date', 'common-lang');
            break;
            case self::NOT_PUBLISHED:
                return LangLoader::get_message('common.status.draft', 'common-lang');
            break;
        }
    }

    public function get_publishing_start_date()
    {
        return $this->publishing_start_date;
    }

    public function set_publishing_start_date(Date $publishing_start_date)
    {
        $this->publishing_start_date = $publishing_start_date;
    }

    public function get_publishing_end_date()
    {
        return $this->publishing_end_date;
    }

    public function set_publishing_end_date(Date $publishing_end_date)
    {
        $this->publishing_end_date = $publishing_end_date;
        $this->end_date_enabled = true;
    }

    public function is_end_date_enabled()
    {
        return $this->end_date_enabled;
    }

    public function get_creation_date()
    {
        return $this->creation_date;
    }

    public function set_creation_date(Date $creation_date)
    {
        $this->creation_date = $creation_date;
    }

    public function has_update_date()
    {
        return $this->item_content->get_update_date() !== null && $this->item_content->get_update_date() > $this->creation_date;
    }

    public function set_views_number($views_number)
    {
        $this->views_number = $views_number;
    }

    public function get_views_number()
    {
        return $this->views_number;
    }

    public function get_notation()
    {
        return $this->notation;
    }

    public function set_notation(Notation $notation)
    {
        $this->notation = $notation;
    }

    public function get_keywords()
    {
        if ($this->keywords === null)
        {
            $this->keywords = KeywordsService::get_keywords_manager()->get_keywords($this->id);
        }
        return $this->keywords;
    }

    public function get_keywords_name()
    {
        return array_keys($this->get_keywords());
    }

    public function is_authorized_to_add()
    {
        return
            WikiAuthorizationsService::check_authorizations($this->id_category)->write()
            || WikiAuthorizationsService::check_authorizations($this->id_category)->contribution()
        ;
    }

    public function is_authorized_to_edit()
    {
        return
            WikiAuthorizationsService::check_authorizations($this->id_category)->moderation()
            || WikiAuthorizationsService::check_authorizations($this->id_category)->write()
            || (
                WikiAuthorizationsService::check_authorizations($this->id_category)->contribution()
                && $this->item_content->get_author_user()->get_id() == AppContext::get_current_user()->get_id()
            )
        ;
    }

    public function is_authorized_to_duplicate()
    {
        return ModulesManager::get_module('wiki')->get_configuration()->has_duplication() && (WikiAuthorizationsService::check_authorizations($this->id_category)->write() || (WikiAuthorizationsService::check_authorizations($this->id_category)->contribution() && WikiAuthorizationsService::check_authorizations($this->id_category)->duplication()));
    }

    public function is_authorized_to_delete()
    {
        return
            WikiAuthorizationsService::check_authorizations($this->id_category)->moderation()
            || (
                (
                    WikiAuthorizationsService::check_authorizations($this->id_category)->write()
                    || WikiAuthorizationsService::check_authorizations($this->id_category)->contribution()
                )
                && $this->item_content->get_author_user()->get_id() == AppContext::get_current_user()->get_id()
            )
        ;
    }

    public function is_authorized_to_restore()
    {
        return
            WikiAuthorizationsService::check_authorizations($this->id_category)->moderation()
            || WikiAuthorizationsService::check_authorizations($this->id_category)->manage_archives()
        ;
    }

    public function get_properties()
    {
        return [
            'id'                    => $this->get_id(),
            'id_category'           => $this->get_id_category(),
            'title'                 => $this->get_title(),
            'rewrited_title'        => $this->get_rewrited_title(),
            'i_order'               => $this->get_i_order(),
            'published'             => $this->get_publishing_state(),
            'publishing_start_date' => $this->get_publishing_start_date() !== null ? $this->get_publishing_start_date()->get_timestamp() : 0,
            'publishing_end_date'   => $this->get_publishing_end_date() !== null ? $this->get_publishing_end_date()->get_timestamp() : 0,
            'creation_date'         => $this->get_creation_date()->get_timestamp(),
            'views_number'          => $this->get_views_number(),
        ];
    }

    public function set_properties(array $properties)
    {
        $item_content = new WikiItemContent();
        $item_content->set_properties($properties);

        $this->id                    = $properties['id'];
        $this->id_category           = $properties['id_category'];
        $this->title                 = $properties['title'];
        $this->rewrited_title        = $properties['rewrited_title'];
        $this->i_order               = $properties['i_order'];
        $this->item_content          = $item_content;
        $this->views_number          = $properties['views_number'];
        $this->published             = $properties['published'];
        $this->publishing_start_date = !empty($properties['publishing_start_date']) ? new Date($properties['publishing_start_date'], Timezone::SERVER_TIMEZONE) : null;
        $this->publishing_end_date   = !empty($properties['publishing_end_date']) ? new Date($properties['publishing_end_date'], Timezone::SERVER_TIMEZONE) : null;
        $this->end_date_enabled      = !empty($properties['publishing_end_date']);
        $this->creation_date         = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);

        $notation = new Notation();
        $notation->set_module_name('wiki');
        $notation->set_id_in_module($properties['id']);
        $notation->set_notes_number($properties['notes_number']);
        $notation->set_average_notes($properties['average_notes']);
        $notation->set_user_already_noted(!empty($properties['note']));
        $this->notation = $notation;
    }

    public function init_default_properties($id_category = Category::ROOT_CATEGORY)
    {
        $this->id_category             = $id_category;
        $this->published               = self::PUBLISHED;
        $this->publishing_start_date   = new Date();
        $this->publishing_end_date     = new Date();
        $this->views_number            = 0;
        $this->end_date_enabled        = false;
        $this->creation_date           = new Date();
    }

    public function clean_publishing_start_and_end_date()
    {
        $this->publishing_start_date   = null;
        $this->publishing_end_date     = null;
        $this->end_date_enabled        = false;
    }

    public function clean_publishing_end_date()
    {
        $this->publishing_end_date = null;
        $this->end_date_enabled = false;
    }

    public function get_item_url()
    {
        $category = $this->get_category();
        return WikiUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_title)->rel();
    }

    public function get_template_vars()
    {
        $category                  = $this->get_category();
        $content                   = FormatingHelper::second_parse($this->item_content->get_content());
        $rich_content              = HooksService::execute_hook_display_action('wiki', $content, $this->get_properties());
        $real_summary              = $this->item_content->get_real_summary();
        $user                      = WikiService::get_initial_content($this->id)->get_author_user();
        $author_user               = $this->item_content->get_author_user();
        $user_group_color          = User::get_group_color($user->get_groups(), $user->get_level(), true);
        $author_user_group_color   = User::get_group_color($author_user->get_groups(), $author_user->get_level(), true);
        $comments_number           = CommentsService::get_comments_number('wiki', $this->id);
        $sources                   = $this->item_content->get_sources();
        $nbr_sources               = count($sources);
        $config                    = WikiConfig::load();
        $tracked_list              = WikiService::get_tracked_items($this->id);

        return array_merge(
            Date::get_array_tpl_vars($this->creation_date, 'date'),
            Date::get_array_tpl_vars($this->item_content->get_update_date(), 'update_date'),
            Date::get_array_tpl_vars($this->publishing_start_date, 'differed_publishing_start_date'),
            [
                // Conditions
                'C_STICKY_SUMMARY'          => $config->get_sticky_summary(),
                'C_VISIBLE'                 => $this->is_published(),
                'C_CONTROLS'			    => $this->is_authorized_to_edit() || $this->is_authorized_to_delete() || $this->is_authorized_to_restore() || $this->is_authorized_to_duplicate(),
                'C_EDIT'                    => $this->is_authorized_to_edit(),
                'C_DUPLICATE'               => $this->is_authorized_to_duplicate(),
                'C_DELETE'                  => $this->is_authorized_to_delete(),
                'C_RESTORE'		            => $this->is_authorized_to_restore(),
                'C_READ_MORE'               => !$this->item_content->is_summary_enabled() && TextHelper::strlen($content) > $config->get_auto_cut_characters_number() && $real_summary != @strip_tags($content, '<br><br/>'),
                'C_HAS_THUMBNAIL'           => $this->item_content->has_thumbnail(),
                'C_AUTHOR_CUSTOM_NAME'      => $this->item_content->is_author_custom_name_enabled(),
                'C_ENABLED_VIEWS_NUMBER'    => $config->get_enabled_views_number(),
                'C_AUTHOR_GROUP_COLOR'      => !empty($user_group_color),
                'C_CONTRIBUTOR_GROUP_COLOR' => !empty($author_user_group_color),
                'C_HAS_UPDATE_DATE'         => $this->has_update_date(),
                'C_SOURCES'                 => $nbr_sources > 0,
                'C_DIFFERED'                => $this->published == self::DEFERRED_PUBLICATION,
                'C_NEW_CONTENT'             => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date('wiki', $this->get_publishing_start_date() != null ? $this->get_publishing_start_date()->get_timestamp() : $this->creation_date->get_timestamp()) && $this->is_published(),
                'C_INIT'				    => WikiService::get_initial_content($this->id)->get_content_id() == $this->item_content->get_content_id(),
                'C_CHANGE_REASON'		    => !empty($this->item_content->get_change_reason()),
                'C_IS_TRACKED'              => isset($tracked_list[0]) && AppContext::get_current_user()->get_id() == $tracked_list[0][1] && $this->id == $tracked_list[0][0],

                // Item
                'ID'                        => $this->id,
                'TITLE'                     => $this->title,
                'CONTENT'                   => $rich_content,
                'SUMMARY' 		            => $real_summary,
                'CHANGE_REASON'             => $this->item_content->get_change_reason(),
                'STATUS'                    => $this->get_publishing_state(),
                'AUTHOR_CUSTOM_NAME'        => $this->item_content->get_author_custom_name(),
                'C_AUTHOR_EXISTS'           => $user->get_id() !== User::VISITOR_LEVEL,
                'C_CONTRIBUTOR_EXISTS'      => $author_user->get_id() !== User::VISITOR_LEVEL,
                'AUTHOR_DISPLAY_NAME'       => $user->get_display_name(),
                'AUTHOR_LEVEL_CLASS'        => UserService::get_level_class($user->get_level()),
                'AUTHOR_GROUP_COLOR'        => $user_group_color,
                'CONTRIBUTOR_DISPLAY_NAME'  => $author_user->get_display_name(),
                'CONTRIBUTOR_LEVEL_CLASS'   => UserService::get_level_class($author_user->get_level()),
                'CONTRIBUTOR_GROUP_COLOR'   => $author_user_group_color,
                'VIEWS_NUMBER'              => $this->get_views_number(),
                'STATIC_NOTATION'           => NotationService::display_static_image($this->get_notation()),
                'NOTATION'                  => NotationService::display_active_image($this->get_notation()),

                'C_COMMENTS'      => !empty($comments_number),
                'L_COMMENTS'      => CommentsService::get_lang_comments('wiki', $this->id),
                'COMMENTS_NUMBER' => $comments_number,

                // Category
                'C_ROOT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY,
                'CATEGORY_ID'          => $category->get_id(),
                'CATEGORY_NAME'        => $category->get_name(),
                'CATEGORY_DESCRIPTION' => $category->get_description(),
                'U_CATEGORY'           => WikiUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
                'U_CATEGORY_THUMBNAIL' => $category->get_thumbnail()->rel(),
                'U_EDIT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY ? WikiUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($category->get_id(), 'wiki')->rel(),

                // Links
                'U_SYNDICATION'         => SyndicationUrlBuilder::rss('wiki', $this->id_category)->rel(),
                'U_AUTHOR_PROFILE'      => UserUrlBuilder::profile($user->get_id())->rel(),
                'U_CONTRIBUTOR_PROFILE' => UserUrlBuilder::profile($this->item_content->get_author_user()->get_id())->rel(),
                'U_ITEM'                => $this->get_item_url(),
                'U_TRACK'               => WikiUrlBuilder::track_item($this->id, AppContext::get_current_user()->get_id())->rel(),
                'U_UNTRACK'             => WikiUrlBuilder::untrack_item($this->id, AppContext::get_current_user()->get_id())->rel(),
                'U_HISTORY'             => WikiUrlBuilder::history($this->id)->rel(),
                'U_EDIT'                => WikiUrlBuilder::edit($this->id)->rel(),
                'U_DUPLICATE'           => WikiUrlBuilder::duplicate($this->id)->rel(),
                'U_DELETE'              => WikiUrlBuilder::delete($this->id, 0)->rel(),
                'U_THUMBNAIL'           => $this->item_content->get_thumbnail()->rel(),
                'U_COMMENTS'            => WikiUrlBuilder::display_comments($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_title)->rel()
            ]
        );
    }

    public function get_array_tpl_source_vars($source_name)
    {
        $vars = [];
        $sources = $this->item_content->get_sources();

        if (isset($sources[$source_name]))
        {
            $vars = [
                'C_SEPARATOR' => array_search($source_name, array_keys($sources)) < count($sources) - 1,
                'NAME' => $source_name,
                'URL'  => $sources[$source_name]
            ];
        }

        return $vars;
    }
}
?>
