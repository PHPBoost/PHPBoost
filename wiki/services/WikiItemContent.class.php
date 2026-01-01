<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 6.0 - 2022 11 18
 */

class WikiItemContent
{
    private $content_id;
    private $item_id;
    private $summary;
    private $content;
    private $active_content;
    private $thumbnail_url;
    private $change_reason;
    private $content_level;
    private $custom_level;
    private $sources;

    private $update_date;

    private $author_user;
    private $author_custom_name;
    private $author_custom_name_enabled;

    const THUMBNAIL_URL = '/templates/__default__/images/default_item.webp';

    const ASC  = 'ASC';
    const DESC = 'DESC';

    const NO_LEVEL = 0;
    const WIP_LEVEL = 1;
    const SKETCH_LEVEL = 2;
    const REDO_LEVEL = 3;
    const CLAIM_LEVEL = 4;
    const TRUST_LEVEL = 5;
    const CUSTOM_LEVEL = 6;

    public function get_content_id()
    {
        return $this->content_id;
    }

    public function set_content_id($content_id)
    {
        $this->content_id = $content_id;
    }

    public function get_item_id()
    {
        return $this->item_id;
    }

    public function set_item_id($item_id)
    {
        $this->item_id = $item_id;
    }

    public function get_summary()
    {
        return $this->summary;
    }

    public function set_summary($summary)
    {
        $this->summary = $summary;
    }

    public function is_summary_enabled()
    {
        return !empty($this->summary);
    }

    public function get_real_summary()
    {
        if ($this->is_summary_enabled()) {
            return FormatingHelper::second_parse($this->summary);
        }
        return TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse($this->content), '<br><br/>'), (int)WikiConfig::load()->get_auto_cut_characters_number());
    }

    public function get_content()
    {
        return $this->content;
    }

    public function set_content($content)
    {
        $this->content = $content;
    }

    public function get_active_content()
    {
        return $this->active_content;
    }

    public function set_active_content($active_content)
    {
        $this->active_content = $active_content;
    }

    public function get_thumbnail()
    {
        if (!$this->thumbnail_url instanceof Url)
            return new Url($this->thumbnail_url == FormFieldThumbnail::DEFAULT_VALUE ? FormFieldThumbnail::get_default_thumbnail_url(self::THUMBNAIL_URL) : $this->thumbnail_url);

        return $this->thumbnail_url;
    }

    public function set_thumbnail($thumbnail)
    {
        $this->thumbnail_url = $thumbnail;
    }

    public function has_thumbnail()
    {
        $thumbnail = ($this->thumbnail_url instanceof Url) ? $this->thumbnail_url->rel() : $this->thumbnail_url;
        return !empty($thumbnail);
    }

    public function get_change_reason()
    {
        return $this->change_reason;
    }

    public function set_change_reason($change_reason)
    {
        $this->change_reason = $change_reason;
    }

    public function get_update_date()
    {
        return $this->update_date;
    }

    public function set_update_date(Date $update_date)
    {
        $this->update_date = $update_date;
    }

    public function get_author_user()
    {
        return $this->author_user;
    }

    public function set_author_user(User $author_user)
    {
        $this->author_user = $author_user;
    }

    public function get_author_custom_name()
    {
        return $this->author_custom_name;
    }

    public function set_author_custom_name($author_custom_name)
    {
        $this->author_custom_name = $author_custom_name;
    }

    public function is_author_custom_name_enabled()
    {
        return $this->author_custom_name_enabled;
    }

    public function get_content_level()
    {
        return $this->content_level;
    }

    public function set_content_level($content_level)
    {
        $this->content_level = $content_level;
    }

    public function has_content_level()
    {
        return $this->content_level != self::NO_LEVEL;
    }

    public function get_custom_level()
    {
        return $this->custom_level;
    }

    public function set_custom_level($custom_level)
    {
        $this->custom_level = $custom_level;
    }

    public function add_source($source)
    {
        $this->sources[] = $source;
    }

    public function set_sources($sources)
    {
        $this->sources = $sources;
    }

    public function get_sources()
    {
        return $this->sources;
    }

    public function get_properties()
    {
        return [
            'content_id'            => $this->get_content_id(),
            'item_id'               => $this->get_item_id(),
            'summary'               => $this->get_summary(),
            'content'               => $this->get_content(),
            'active_content'        => $this->get_active_content(),
            'change_reason'         => $this->get_change_reason(),
            'update_date'           => $this->get_update_date()->get_timestamp(),
            'author_user_id'        => $this->get_author_user()->get_id(),
            'author_custom_name'    => $this->get_author_custom_name(),
            'thumbnail'             => $this->get_thumbnail()->relative(),
            'content_level'         => $this->get_content_level(),
            'custom_level'          => $this->get_custom_level(),
            'sources'               => TextHelper::serialize($this->get_sources())
        ];
    }

    public function set_properties(array $properties)
    {
        $this->content_id       = $properties['content_id'];
        $this->item_id          = $properties['item_id'];
        $this->summary          = $properties['summary'];
        $this->content          = $properties['content'];
        $this->active_content   = $properties['active_content'];
        $this->change_reason    = $properties['change_reason'];
        $this->update_date      = new Date($properties['update_date'], Timezone::SERVER_TIMEZONE);
        $this->thumbnail_url    = $properties['thumbnail'];
        $this->content_level    = $properties['content_level'];
        $this->custom_level     = $properties['custom_level'];
        $this->sources          = !empty($properties['sources']) ? TextHelper::unserialize($properties['sources']) : [];

        $user = new User();
        if (!empty($properties['user_id']))
            $user->set_properties($properties);
        else
            $user->init_visitor_user();

        $this->set_author_user($user);

        $this->author_custom_name           = !empty($properties['author_custom_name']) ? $properties['author_custom_name'] : '';
        $this->author_custom_name_enabled   = !empty($properties['author_custom_name']);
    }

    public function init_default_properties()
    {
        $this->content                      = WikiConfig::load()->get_default_content();
        $this->active_content               = true;
        $this->thumbnail_url                = '';
        $this->update_date                  = new Date();
        $this->author_custom_name           = AppContext::get_current_user()->get_display_name();
        $this->author_custom_name_enabled   = false;
        $this->content_level                = self::NO_LEVEL;
        $this->sources                      = [];
        $this->author_user                  = AppContext::get_current_user();
    }
}
?>
