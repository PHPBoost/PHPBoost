<?php
/**
 * This class contains an agregation of differents feeds
 * @package     Content
 * @subpackage  Feed
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 08
 * @since       PHPBoost 3.0 - 2009 02 25
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FeedsList
{
	private array $list = [];

    /**
     * Adds the FeedsCat $cat_tree to the current feeds list
     * @param FeedsCat $cat_tree The feed to add to the list
     * @param string $feed_type The feed category name
     */
    public function add_feed(FeedsCat $cat_tree, string $feed_type): void
    {
        $this->list[$feed_type] = $cat_tree;
    }

    /**
     * Returns the feeds list
     * @return FeedsCat[] the feeds list
     */
    public function get_feeds_list(): array
    {
        return $this->list;
    }
}
?>
