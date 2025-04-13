<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 04 13
 * @since       PHPBoost 4.0 - 2013 06 30
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class WikiConfig extends AbstractConfigData
{
    const MODULE_NAME      = 'module_name';
    const MENU_NAME        = 'menu_name';
    const STICKY_SUMMARY   = 'sticky_summary';

    const HOMEPAGE   = 'homepage';
    const CATEGORIES = 'categories';
    const EXPLORER   = 'explorer';
    const OVERVIEW   = 'overview';

    const CATEGORIES_PER_PAGE   = 'categories_per_page';
    const CATEGORIES_PER_ROW    = 'categories_per_row';
    const ITEMS_PER_PAGE        = 'items_per_page';
    const ITEMS_PER_ROW         = 'items_per_row';
    const DISPLAY_DESCRIPTION   = 'display_description';

    const DEFAULT_CONTENT = 'default_content';

    const SUMMARY_DISPLAYED_TO_GUESTS = 'summary_displayed_to_guests';
    const AUTHOR_DISPLAYED            = 'author_displayed';
    const NB_VIEW_ENABLED             = 'nb_view_enabled';
    const ROOT_CATEGORY_DESCRIPTION   = 'root_category_description';
    const AUTHORIZATIONS              = 'authorizations';

    const DISPLAY_TYPE = 'display_type';
    const GRID_VIEW    = 'grid_view';
    const LIST_VIEW    = 'list_view';
    const TABLE_VIEW   = 'table_view';

    const DEFERRED_OPERATIONS = 'deferred_operations';

    const SUGGESTED_ITEMS    = 'suggested_items';
    const SUGGESTED_ITEMS_NB = 'suggested_items_nb';
    const RELATED_ITEMS      = 'related_items';

    const AUTO_CUT_CHARACTERS_NUMBER = 'auto_cut_characters_number';

    public function get_module_name()
    {
        return $this->get_property(self::MODULE_NAME);
    }

    public function set_module_name($value)
    {
        $this->set_property(self::MODULE_NAME, $value);
    }

    public function get_menu_name()
    {
        return $this->get_property(self::MENU_NAME);
    }

    public function set_menu_name($value)
    {
        $this->set_property(self::MENU_NAME, $value);
    }

    public function get_sticky_summary()
    {
        return $this->get_property(self::STICKY_SUMMARY);
    }

    public function set_sticky_summary($value)
    {
        $this->set_property(self::STICKY_SUMMARY, $value);
    }

    public function get_homepage()
    {
        return $this->get_property(self::HOMEPAGE);
    }

    public function set_homepage($value)
    {
        $this->set_property(self::HOMEPAGE, $value);
    }

    public function get_categories_per_page()
    {
        return $this->get_property(self::CATEGORIES_PER_PAGE);
    }

    public function set_categories_per_page($value)
    {
        $this->set_property(self::CATEGORIES_PER_PAGE, $value);
    }

    public function get_categories_per_row()
    {
        return $this->get_property(self::CATEGORIES_PER_ROW);
    }

    public function set_categories_per_row($value)
    {
        $this->set_property(self::CATEGORIES_PER_ROW, $value);
    }

    public function get_items_per_page()
    {
        return $this->get_property(self::ITEMS_PER_PAGE);
    }

    public function set_items_per_page($value)
    {
        $this->set_property(self::ITEMS_PER_PAGE, $value);
    }

    public function get_display_description()
    {
        return $this->get_property(self::DISPLAY_DESCRIPTION);
    }

    public function set_display_description($value)
    {
        $this->set_property(self::DISPLAY_DESCRIPTION, $value);
    }

    public function get_items_per_row()
    {
        return $this->get_property(self::ITEMS_PER_ROW);
    }

    public function set_items_per_row($value)
    {
        $this->set_property(self::ITEMS_PER_ROW, $value);
    }

    public function get_display_type()
    {
        return $this->get_property(self::DISPLAY_TYPE);
    }

    public function set_display_type($value)
    {
        $this->set_property(self::DISPLAY_TYPE, $value);
    }

    public function get_default_content()
    {
        return $this->get_property(self::DEFAULT_CONTENT);
    }

    public function set_default_content($value)
    {
        $this->set_property(self::DEFAULT_CONTENT, $value);
    }

    public function display_summary_to_guests()
    {
        $this->set_property(self::SUMMARY_DISPLAYED_TO_GUESTS, true);
    }

    public function hide_summary_to_guests()
    {
        $this->set_property(self::SUMMARY_DISPLAYED_TO_GUESTS, false);
    }

    public function is_summary_displayed_to_guests()
    {
        return $this->get_property(self::SUMMARY_DISPLAYED_TO_GUESTS);
    }

    public function display_author()
    {
        $this->set_property(self::AUTHOR_DISPLAYED, true);
    }

    public function hide_author()
    {
        $this->set_property(self::AUTHOR_DISPLAYED, false);
    }

    public function is_author_displayed()
    {
        return $this->get_property(self::AUTHOR_DISPLAYED);
    }

    public function get_enabled_views_number()
    {
        return $this->get_property(self::NB_VIEW_ENABLED);
    }

    public function set_enabled_views_number($nb_view_enabled)
    {
        $this->set_property(self::NB_VIEW_ENABLED, $nb_view_enabled);
    }

    public function get_root_category_description()
    {
        return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
    }

    public function set_root_category_description($value)
    {
        $this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
    }

    public function get_authorizations()
    {
        return $this->get_property(self::AUTHORIZATIONS);
    }

    public function set_authorizations(Array $authorizations)
    {
        $this->set_property(self::AUTHORIZATIONS, $authorizations);
    }

    public function get_deferred_operations()
    {
        return $this->get_property(self::DEFERRED_OPERATIONS);
    }

    public function set_deferred_operations(Array $deferred_operations)
    {
        $this->set_property(self::DEFERRED_OPERATIONS, $deferred_operations);
    }

    public function get_enabled_items_suggestions()
    {
        return $this->get_property(self::SUGGESTED_ITEMS);
    }

    public function set_enabled_items_suggestions($enabled_items_suggestions)
    {
        $this->set_property(self::SUGGESTED_ITEMS, $enabled_items_suggestions);
    }

    public function get_suggested_items_nb()
    {
        return $this->get_property(self::SUGGESTED_ITEMS_NB);
    }

    public function set_suggested_items_nb($number)
    {
        $this->set_property(self::SUGGESTED_ITEMS_NB, $number);
    }

    public function get_enabled_navigation_links()
    {
        return $this->get_property(self::RELATED_ITEMS);
    }

    public function set_enabled_navigation_links($enabled_navigation_links)
    {
        $this->set_property(self::RELATED_ITEMS, $enabled_navigation_links);
    }

    public function get_auto_cut_characters_number()
    {
        return $this->get_property(self::AUTO_CUT_CHARACTERS_NUMBER);
    }

    public function set_auto_cut_characters_number($number)
    {
        $this->set_property(self::AUTO_CUT_CHARACTERS_NUMBER, $number);
    }

    /**
     * {@inheritdoc}
     */
    public function get_default_values()
    {
        return [
            self::MODULE_NAME                   => LangLoader::get_message('wiki.module.title', 'common', 'wiki') . ' ' . GeneralConfig::load()->get_site_name(),
            self::MENU_NAME                     => LangLoader::get_message('wiki.menu.title', 'common', 'wiki'),
            self::STICKY_SUMMARY                => false,
            self::HOMEPAGE                      => self::CATEGORIES,
            self::CATEGORIES_PER_PAGE           => 10,
            self::CATEGORIES_PER_ROW            => 3,
            self::ITEMS_PER_PAGE                => 15,
            self::ITEMS_PER_ROW                 => 2,
            self::DISPLAY_DESCRIPTION           => false,
            self::DISPLAY_TYPE                  => self::GRID_VIEW,
            self::DEFAULT_CONTENT               => '',
            self::SUMMARY_DISPLAYED_TO_GUESTS   => false,
            self::AUTHOR_DISPLAYED              => true,
            self::NB_VIEW_ENABLED               => false,
            self::ROOT_CATEGORY_DESCRIPTION     => LangLoader::get_message('default.root.description', 'install', 'wiki'),
            self::AUTO_CUT_CHARACTERS_NUMBER    => 128,
            self::AUTHORIZATIONS                => ['r-1' => 1, 'r0' => 39, 'r1' => 63],
            self::DEFERRED_OPERATIONS           => [],
            self::SUGGESTED_ITEMS               => false,
            self::SUGGESTED_ITEMS_NB            => 4,
            self::RELATED_ITEMS                 => true,
        ];
    }

    /**
     * Returns the configuration.
     * @return WikiConfig
     */
    public static function load()
    {
        return ConfigManager::load(__CLASS__, 'wiki', 'config');
    }

    /**
     * Saves the configuration in the database. Has it become persistent.
     */
    public static function save()
    {
        ConfigManager::save('wiki', self::load(), 'config');
    }
}
?>
