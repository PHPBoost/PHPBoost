<?php
/*##################################################
 *                         FeedItem.class.php
 *                         -------------------
 *   begin                : June 21, 2008
 *   copyright            : (C) 2005 Loïc Rouchon
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
 * @desc Contains meta-informations and informations about a feed entry / item
 * @package {@package}
 */
class FeedItem
{
	private $title = '';        // Item Title
    private $link = '';         // Item Url
    private $date = null;       // Feed date
    private $desc = '';         // Item Description
    private $guid = '';         // Item GUID
    private $image_url = '';    // Item Image
    private $enclosure;			// Item Enclosure
    private $auth = null;       // Authorizations
	
    ## Setters ##
    /**
     * @desc Sets the feed item title
     * @param string $value The title
     */
    public function set_title($value) { $this->title = strip_tags($value); }
    /**
     * @desc Sets the feed item date
     * @param Date $value a date object representing the item date
     */
    public function set_date($value) { $this->date = $value; }
    /**
     * @desc Sets the feed item description
     * @param string $value the feed item description
     */
    public function set_desc($value) { $this->desc = $value; }
    /**
     * @desc Sets the feed item picture
     * @param string $value the picture url
     */
    public function set_image_url($value) { $this->image_url = $value; }
 	/**
     * @desc Sets the feed item enclosure
     * @param FeedItemEnclosure $value the enclosure
     */
    public function set_enclosure(FeedItemEnclosure $value) { $this->enclosure = $value; }
    /**
     * @desc Sets the feed item auth, useful to check authorizations
     * @param int[string] $value the item authorizations array
     */
    public function set_auth($auth) { $this->auth = $auth; }
    /**
     * @desc Sets the feed item link
     * @param mixed $value a string url or an Url object
     */
	public function set_link($value)
    {
        if (!($value instanceof Url))
        {
            $value = new Url($value);
        }
        $this->link = $value->absolute();
    }
    /**
     * @desc Sets the feed item guid
     * @param mixed $value a string url or an Url object
     */
    public function set_guid($value)
    {
        if ($value instanceof Url)
        {
            $this->guid = $value->absolute();
        }
        else
        {
            $this->guid = $value;
        }
    }
    
    ## Getters ##
    public function get_title() { return str_replace('&', '&amp;', TextHelper::htmlspecialchars_decode($this->title)); }
    public function get_link() { return $this->link; }
    public function get_guid() { return $this->guid; }
    public function get_date() { return $this->date->format(Date::FORMAT_DAY_MONTH, Timezone::USER_TIMEZONE); }
    public function get_date_rfc2822() { return $this->date->format(Date::FORMAT_RFC2822, Timezone::USER_TIMEZONE); }
    public function get_date_iso8601() { return $this->date->format(Date::FORMAT_ISO8601, Timezone::USER_TIMEZONE); }
	public function get_hours() { return $this->date->get_hours(); }
	public function get_minutes() { return $this->date->get_minutes(); }
	public function get_date_text() { return $this->date->format(Date::FORMAT_DAY_MONTH_YEAR_LONG, Timezone::USER_TIMEZONE); }
    public function get_desc() { return str_replace('&', '&amp;', TextHelper::htmlspecialchars_decode($this->desc)); }
    public function get_image_url() { return $this->image_url; }
	public function get_enclosure() { return $this->enclosure; }
    public function get_auth() { return $this->auth; }
}
?>