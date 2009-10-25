<?php
/*##################################################
 *                            feeds_list.class.php
 *                            -------------------
 *   begin                : Februrary 25, 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

import('content/feed/Feed');
import('content/feed/FeedsCat');

/**
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @desc This class contains an agregation of differents feeds
 * @package content
 * @subpackage syndication
 */
class FeedsList
{
    function FeedsList() {}
    
    /**
     * @desc Adds the FeedsCat $cat_tree to the current feeds list
     * @param FeedsCat $cat_tree The feed to add to the list
     * @param string $feed_type The feed category name
     */
    function add_feed($cat_tree, $feed_type)
    {
        $this->list[$feed_type] = $cat_tree;
    }
    
    /**
     * @desc Returns the feeds list
     * @return FeedsCat[] the feeds list
     */
    function get_feeds_list()
    {
        return $this->list;
    }
    
    /**
     * @var array Contains the feeds trees
     */
    var $list = array();
}

?>