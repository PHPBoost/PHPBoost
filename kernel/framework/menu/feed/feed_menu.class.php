<?php
/*##################################################
 *                          feed_menu.class.php
 *                            -------------------
 *   begin                : January 14, 2009
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

import('menu/menu');
import('content/syndication/feed');

define('FEED_MENU__CLASS','FeedMenu');

/**
 * @author Loïc Rouchon horn@phpboost.com
 * @desc
 * @package Menu
 * @subpackage FeedMenu
 */
class FeedMenu extends Menu
{
    ## Public Methods ##
    function FeedMenu($title, $module_id, $category = 0, $name = DEFAULT_FEED_NAME, $number = 10, $begin_at = 0)
    {
       parent::Menu($title);
       $this->module_id = $module_id;
       $this->category = $category;
       $this->name = $name;
       $this->number = $number;
       $this->begin_at = $begin_at;
    }
    
    function get_url()
    {
        return Url::get_absolute_root() . '/syndication.php?m=' . $this->module_id .
        ($this->category > 0 ? '&amp;cat=' . $this->category : '') .
        (!empty($this->name) && $this->name != DEFAULT_FEED_NAME ? '&amp;name=' . $this->name : '');
    }
    
    ## Getters ##
    /**
     * @return string the menu content
     */
    function get_content() { return $this->content; }
    
    
    function display()
    {
        return Feed::get_parsed($this->module_id, $this->name, $this->cat,
            FeedMenu::get_template(), $this->number, $this->begin_at
        );
    }
    
    function cache_export()
    {
        return parent::cache_export_begin() .
            '\';import(\'content/syndicaction/feed\');$__menu=Feed::get_parsed(' .
            var_export($this->module_id, true) . ',' . var_export($this->name, true) . ',' .
            $this->cat . ',FeedMenu::get_template(),' . $this->number . ',' . $this->begin_at.');' .
            parent::cache_export_end();
    }
    
    /**
     * @desc
     * @return unknown_type
     */
    /* static */ function get_template()
    {
        return new Template('/framework/menus/feed/feed.tpl');
    }
    
    ## Private Attributes
    
    /**
     * @var string the feed url
     */
    var $url = '';
    var $module_id = '';
    var $name = '';
    var $category = 0;
    var $number = 10;
    var $begin_at = 0;
    
}

?>