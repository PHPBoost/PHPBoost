<?php
/*##################################################
 *                         FeedData.class.php
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
 * @desc Contains meta-informations about a feed with its entries
 * @package {@package}
 */
class FeedData
{
	private $title = '';        // Feed Title
    private $link = '';         // Feed Url
    private $date = null;       // Feed date
    private $desc = '';         // Feed Description
    private $lang = '';         // Feed Language
    private $host = '';         // Feed Host
    private $items = array();   // Items
    private $auth_bit = 0;      // Auth bit
	
    /**
     * @desc Builds a FeedData Object
     * @param FeedData $data an other FeedData object to clone
     */
    public function __construct($data = null)
    {
		if ($data !== null && $data instanceof FeedData)
		{
			$this->title = $data->title;
			$this->link = $data->link;
			$this->date = $data->date;
			$this->desc = $data->desc;
			$this->lang = $data->lang ;
			$this->host = $data->host;
			$this->items = $data->items;
		}
	}
    
    ## Setters ##
    /**
     * @desc Sets the feed title
     * @param string $value The title
     */
    public function set_title($value) { $this->title = strip_tags($value); }
    /**
     * @desc Sets the feed data date
     * @param Date $value a date object representing the feed date
     */
    public function set_date($value) { $this->date = $value; }
    /**
     * @desc Sets the feed description
     * @param string $value the feed description
     */
    public function set_desc($value) { $this->desc = $value; }
    /**
     * @desc Sets the feed language
     * @param string $value the feed language
     */
    public function set_lang($value) { $this->lang = $value; }
    /**
     * @desc Sets the feed host
     * @param string $value the feed host
     */
    public function set_host($value) { $this->host = $value; }
    /**
     * @desc Sets the feed auth bit, useful to check authorizations
     * @param int $value the bit position in an int (from 1 to 32)
     */
    public function set_auth_bit($value) { $this->auth_bit = $value; }
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
    
    public function add_item($item) { array_push($this->items, $item); }
    
    ## Getters ##
    public function get_title() { return $this->title; }
    public function get_link() { return $this->link; }
    public function get_date() { return $this->date->format(Date::FORMAT_DAY_MONTH, Timezone::USER_TIMEZONE); }
    public function get_date_rfc2822() { return $this->date->format(Date::FORMAT_RFC2822, Timezone::USER_TIMEZONE); }
    public function get_date_iso8601() { return $this->date->format(Date::FORMAT_ISO8601, Timezone::USER_TIMEZONE); }
	public function get_date_text() { return $this->date->format(Date::FORMAT_DAY_MONTH_YEAR_LONG, Timezone::USER_TIMEZONE); }
    public function get_desc() { return $this->desc; }
    public function get_lang() { return $this->lang; }
    public function get_host() { return $this->host; }
    
    /**
     * @desc Returns the feed items
     * @return FeedItem[] the feed items
     */
    public function get_items()
    {
        $items = array();
        foreach ($this->items as $item)
        {
            if ((gettype($item->get_auth()) != 'array' || $this->auth_bit == 0) || AppContext::get_current_user()->check_auth($item->get_auth(), $this->auth_bit))
                $items[] = $item;
        }
        
        return $items;
    }
    
    public function serialize()
    {
        return serialize($this);
    }
    
    
    /**
     * @desc Returns a items list containing $number items starting from the $begin_at one
     * @param int $number the number of items to retrieve
     * @param int $begin_at the number of the first to retrieve
     * @return FeedItem[] the items list containing $number items starting from the $begin_at one
     */
    public function subitems($number = 10, $begin_at = 0)
    {
        $secured_items = $this->get_items();
        $nb_items = count($secured_items);
        
        $items = array();
        $end_at = $begin_at + $number;
        for ($i = $begin_at; ($i < $nb_items) && ($i < $end_at) ; $i++)
            $items[] =& $secured_items[$i];
        
        return $items;
    }
}
?>