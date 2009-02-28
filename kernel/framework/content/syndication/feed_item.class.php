<?php
/*##################################################
 *                         feed_item.class.php
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

import('util/url');

class FeedItem
{
    ## Public Methods ##
    function FeedItem() {}

    ## Setters ##
    function set_title($value) { $this->title = htmlspecialchars(strip_tags($value)); }
    function set_date($value) { $this->date = $value; }
    function set_desc($value) { $this->desc = $value; }
    function set_image_url($value) { $this->image_url = $value; }
    function set_auth($auth) { $this->auth = $auth; }
    function set_link($value)
    {
        if (of_class($value, URL__CLASS))
        {
            $this->link = $value->absolute();
        }
        else
        {
            $this->link = $value;
        }
    }
    function set_guid($value)
    {
        if (of_class($value, URL__CLASS))
        {
            $this->guid = $value->absolute();
        }
        else
        {
            $this->guid = $value;
        }
    }
    
    ## Getters ##
    function get_title() { return $this->title; }
    function get_link() { return $this->link; }
    function get_guid() { return $this->guid; }
    function get_date() { return $this->date->format(DATE_FORMAT_TINY, TIMEZONE_USER); }
    function get_date_rfc822() { return $this->date->format(DATE_RFC822_F, TIMEZONE_USER); }
    function get_date_rfc3339() { return $this->date->format(DATE_RFC3339_F, TIMEZONE_USER); }
    function get_desc() { return $this->desc; }
    function get_image_url() { return $this->image_url; }
    function get_auth() { return $this->auth; }
    
    ## Private Methods ##
    ## Private attributes ##
    var $title = '';        // Item Title
    var $link = '';         // Item Url
    var $date = null;         // Feed date
    var $desc = '';         // Item Description
    var $guid = '';         // Item GUID
    var $image_url = '';        // Item Image
    var $auth = null;       // Authorizations
}

?>