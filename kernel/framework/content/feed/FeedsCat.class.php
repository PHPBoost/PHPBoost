<?php
/*##################################################
 *                            feeds_cat.class.php
 *                            -------------------
 *   begin                : Februrary 25, 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : horn@phpboost.com
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

import('util/Url');

/**
 * @author Loïc Rouchon <horn@phpboost.com>
 * @desc Describes a feed by building a category tree
 * @package content
 * @subpackage syndication
 */
class FeedsCat
{
    /**
     * @desc Builds a FeedsCat Object
     * @param string $module_id the feed module id
     * @param int $category_id the category id
     * @param string $category_name the category name
     */
    function FeedsCat($module_id, $category_id, $category_name)
    {
        $this->id = $category_id;
        $this->module_id = $module_id;
        $this->cat_name = $category_name;
    }
    
    /**
     * @desc Returns the feed url
     * @param string $feed_type The feed type
     * @return string the feed url
     */
    function get_url($feed_type = '')
    {
        $url = new Url('/syndication.php?m=' . $this->module_id . '&amp;cat=' . $this->id . '&amp;name=' . $feed_type);
        return $url->relative();
    }
    
    /**
     * @desc Returns the module id
     * @return string the module id
     */
    function get_module_id()
    {
        return $this->module_id;
    }
    
    
    /**
     * @desc Returns the category id
     * @return int the category id
     */
    function get_category_id()
    {
        return $this->id;
    }
    
    /**
     * @desc Returns the category name
     * @return string the category name
     */
    function get_category_name()
    {
        return $this->cat_name;
    }
    
    /**
     * @desc Adds a FeedsCat child to the current FeedsCat object
     * @param FeedsCat $child The element to add
     */
    function add_child($child)
    {
        $this->children[] = $child;
    }
    
    /**
     * @desc Returns the current category children
     * @return FeedsCat[] The current category children
     */
    function get_children()
    {
        return $this->children;
    }
    
    var $id = 0;
    var $cat_name = '';
    var $module_id = '';
    var $children = array();
}

?>