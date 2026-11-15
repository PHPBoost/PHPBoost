<?php
/**
 * Describes a feed by building a category tree
 * @package     Content
 * @subpackage  Feed
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 08
 * @since       PHPBoost 3.0 - 2009 02 25
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FeedsCat
{
	private int $id = 0;
    private string $cat_name = '';
    private string $module_id = '';
    private array $children = [];

    /**
     * Builds a FeedsCat Object
     * @param string $module_id the feed module id
     * @param int $category_id the category id
     * @param string $category_name the category name
     */
    public function __construct(string $module_id, int $category_id, string $category_name)
    {
        $this->id = $category_id;
        $this->module_id = $module_id;
        $this->cat_name = $category_name;
    }

    /**
     * Returns the feed url
     * @param string $feed_type The feed type
     * @return string the feed url
     */
    public function get_url(string $feed_type = ''): string
    {
        $url = DispatchManager::get_url('/syndication', '/rss/' . $this->module_id . '/' . $this->id . '/' . $feed_type . '/');
        return $url->relative();
    }

    /**
     * Returns the module id
     * @return string the module id
     */
    public function get_module_id(): string
    {
        return $this->module_id;
    }


    /**
     * Returns the category id
     * @return int the category id
     */
    public function get_category_id(): int
    {
        return $this->id;
    }

    /**
     * Returns the category name
     * @return string the category name
     */
    public function get_category_name(): string
    {
        return $this->cat_name;
    }

    /**
     * Adds a FeedsCat child to the current FeedsCat object
     * @param FeedsCat $child The element to add
     */
    public function add_child($child): void
    {
        $this->children[] = $child;
    }

    /**
     * Returns the current category children
     * @return FeedsCat[] The current category children
     */
    public function get_children(): array
    {
        return $this->children;
    }
}
?>
