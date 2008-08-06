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
require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');

class FeedData
{
    ## Public Methods ##
    function FeedData($serialized_data = null)
	{
		if( $serialized_data != null )
		{
			$f_data = sunserialize($serialized_data);
			$this->title = $f_data->title;
			$this->link = $f_data->link;
			$this->date = $f_data->date;
			$this->desc = $f_data->desc;
			$this->lang = $f_data->lang ;
			$this->host = $f_data->host;
			$this->items = $f_data->items;
		}
	}
    
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
	
	function serialize()
	{
		return serialize($this);
	}
	
    // Returns a new FeedData object with only $number items from the $begin_at one
    function subitems($number = 10, $begin_at = 0)
    {
        $f_data = new FeedData($this->serialize());
        
        $f_data_length = count($f_data->items);
        for($i = $begin_at + $number; $i < $f_data_length; $i++)
            unset($f_data->items[$i]);
        
        for($i = 0; $i < $begin_at; $i++)
            unset($f_data->items[$i]);
        
        return $f_data;
    }
    
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