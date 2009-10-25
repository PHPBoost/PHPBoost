<?php
/*##################################################
 *                         feed_item.class.php
 *                         -------------------
 *   begin                : June 21, 2008
 *   copyright            : (C) 2005 LoÃ¯c Rouchon
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

import('util/Url');

/**
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @desc Contains meta-informations and informations about a feed entry / item
 * @package content
 * @subpackage syndication
 */
class FeedItem
{
    ## Public Methods ##
    /**
     * @desc Builds a FeedItem element
     */
    function FeedItem() {}

    ## Setters ##
    /**
     * @desc Sets the feed item title
     * @param string $value The title
     */
    function set_title($value) { $this->title = strip_tags($value); }
    /**
     * @desc Sets the feed item date
     * @param Date $value a date object representing the item date
     */
    function set_date($value) { $this->date = $value; }
    /**
     * @desc Sets the feed item description
     * @param string $value the feed item description
     */
    function set_desc($value) { $this->desc = $value; }
    /**
     * @desc Sets the feed item picture
     * @param string $value the picture url
     */
    function set_image_url($value) { $this->image_url = $value; }
    /**
     * @desc Sets the feed item auth, useful to check authorizations
     * @param int[string] $value the item authorizations array
     */
    function set_auth($auth) { $this->auth = $auth; }
    /**
     * @desc Sets the feed item link
     * @param mixed $value a string url or an Url object
     */
    function set_link($value)
    {
        if (!of_class($value, URL__CLASS))
        {
            $value = new Url($value);
        }
        $this->link = $value->absolute();
    }
    /**
     * @desc Sets the feed item guid
     * @param mixed $value a string url or an Url object
     */
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
    var $date = null;       // Feed date
    var $desc = '';         // Item Description
    var $guid = '';         // Item GUID
    var $image_url = '';    // Item Image
    var $auth = null;       // Authorizations
}

?>