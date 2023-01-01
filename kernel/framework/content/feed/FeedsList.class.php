<?php
/**
 * This class contains an agregation of differents feeds
 * @package     Content
 * @subpackage  Feed
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 02 25
*/

class FeedsList
{
	private $list = array();

    /**
     * Adds the FeedsCat $cat_tree to the current feeds list
     * @param FeedsCat $cat_tree The feed to add to the list
     * @param string $feed_type The feed category name
     */
    public function add_feed($cat_tree, $feed_type)
    {
        $this->list[$feed_type] = $cat_tree;
    }

    /**
     * Returns the feeds list
     * @return FeedsCat[] the feeds list
     */
    public function get_feeds_list()
    {
        return $this->list;
    }
}
?>
