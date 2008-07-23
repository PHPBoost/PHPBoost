<?php
/*##################################################
 *                         feed_data.class.php
 *                         -------------------
 *   begin                : June 21, 2008
 *   copyright            : (C) 2005 Loïc Rouchon
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

require_once(PATH_TO_ROOT . '/kernel/framework/content/syndication/feed_item.class.php');

class FeedData
{
    ## Public Methods ##
    function FeedData() {}
    
    ## Setters ##
    function set_title($value) { $this->title = $value; }
    function set_link($value) { $this->link = $value; }
    function set_date($value) { $this->date = $value; }
    function set_desc($value) { $this->desc = $value; }
    function set_lang($value) { $this->lang = $value; }
    function set_host($value) { $this->host = $value; }
    
    function add_item($item) { array_push($this->items, $item); }
    
    ## Getters ##
    function get_title() { return $this->title; }
    function get_link() { return $this->link; }
    function get_date() { return $this->date->format(DATE_FORMAT_TINY, TIMEZONE_USER); }
    function get_date_rfc822() { return $this->date->format(DATE_RFC822_F, TIMEZONE_USER); }
    function get_date_rfc3339() { return $this->date->format(DATE_RFC3339_F, TIMEZONE_USER); }
    function get_desc() { return $this->desc; }
    function get_lang() { return $this->lang; }
    function get_host() { return $this->host; }
    
    function get_items() { return $this->items; }
    
    ## Private Methods ##
    
    ## Private attributes ##
    var $title = '';        // Feed Title
    var $link = '';         // Feed Url
    var $date = null;         // Feed date
    var $desc = '';         // Feed Description
    var $lang = '';         // Feed Language
    var $host = '';         // Feed Host
    var $items = array();   // Items
}

?>