<?php
/*##################################################
 *                            FeedsCat.class.php
 *                            -------------------
 *   begin                : Februrary 25, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc Describes a feed by building a category tree
 * @package {@package}
 */
class FeedsCat
{
	private $id = 0;
    private $cat_name = '';
    private $module_id = '';
    private $children = array();
	
    /**
     * @desc Builds a FeedsCat Object
     * @param string $module_id the feed module id
     * @param int $category_id the category id
     * @param string $category_name the category name
     */
    public function __construct($module_id, $category_id, $category_name)
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
    public function get_url($feed_type = '')
    {
    	$url = DispatchManager::get_url('/syndication', '/rss/' . $this->module_id . '/' . $this->id . '/' . $feed_type . '/');
        return $url->relative();
    }
    
    /**
     * @desc Returns the module id
     * @return string the module id
     */
    public function get_module_id()
    {
        return $this->module_id;
    }
    
    
    /**
     * @desc Returns the category id
     * @return int the category id
     */
    public function get_category_id()
    {
        return $this->id;
    }
    
    /**
     * @desc Returns the category name
     * @return string the category name
     */
    public function get_category_name()
    {
        return $this->cat_name;
    }
    
    /**
     * @desc Adds a FeedsCat child to the current FeedsCat object
     * @param FeedsCat $child The element to add
     */
    public function add_child($child)
    {
        $this->children[] = $child;
    }
    
    /**
     * @desc Returns the current category children
     * @return FeedsCat[] The current category children
     */
    public function get_children()
    {
        return $this->children;
    }
}
?>