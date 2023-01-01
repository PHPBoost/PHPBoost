<?php
/**
 * Contains meta-informations and informations about a feed entry / item
 * @package     Content
 * @subpackage  Feed
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 03
 * @since       PHPBoost 2.0 - 2008 06 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
     * Sets the feed item title
     * @param string $value The title
     */
    public function set_title($value) { $this->title = strip_tags($value); }
    /**
     * Sets the feed item date
     * @param Date $value a date object representing the item date
     */
    public function set_date($value) { $this->date = $value; }
    /**
     * Sets the feed item description
     * @param string $value the feed item description
     */
    public function set_desc($value) { $this->desc = $value; }
    /**
     * Sets the feed item picture
     * @param string $value the picture url
     */
    public function set_image_url($value) { $this->image_url = $value; }
    /**
     * Sets the feed item enclosure
     * @param FeedItemEnclosure $value the enclosure
     */
    public function set_enclosure(FeedItemEnclosure $value) { $this->enclosure = $value; }
    /**
     * Sets the feed item auth, useful to check authorizations
     * @param int[string] $value the item authorizations array
     */
    public function set_auth($auth) { $this->auth = $auth; }
    /**
     * Sets the feed item link
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
     * Sets the feed item guid
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
    public function get_title() { return TextHelper::htmlspecialchars_decode($this->title); }
    public function get_link() { return $this->link; }
    public function get_guid() { return $this->guid; }
    public function get_date() { return $this->date; }
    public function get_desc() { return TextHelper::htmlspecialchars_decode($this->desc); }
    public function get_image_url() { return $this->image_url; }
	public function get_enclosure() { return $this->enclosure; }
    public function get_auth() { return $this->auth; }
}
?>
