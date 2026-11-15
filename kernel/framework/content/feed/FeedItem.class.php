<?php
/**
 * Contains meta-informations and informations about a feed entry / item
 * @package     Content
 * @subpackage  Feed
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 08
 * @since       PHPBoost 2.0 - 2008 06 21
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class FeedItem
{
	private string $title = '';        // Item Title
    private string $link = '';         // Item Url
    private ?Date $date = null;       // Feed date
    private string $desc = '';         // Item Description
    private string $guid = '';         // Item GUID
    private string $image_url = '';    // Item Image
    private ?FeedItemEnclosure $enclosure = null; // Item Enclosure
    private array $auth = [];       // Authorizations

    ## Setters ##
    public function set_title(string $value): void { $this->title = strip_tags($value); }

    public function set_date(?Date $value): void { $this->date = $value; }

    public function set_desc(string $value): void { $this->desc = $value; }

    public function set_image_url(string|Url $value): void
    {
        if ($value instanceof Url)
        {
            $value = $value->absolute();
        }
        $this->image_url = $value;
    }

    public function set_enclosure(FeedItemEnclosure $value) { $this->enclosure = $value; }

    public function set_auth(array $auth) { $this->auth = $auth; }

    public function set_link(string|Url $value): void
    {
        if (!($value instanceof Url))
        {
            $value = new Url($value);
        }
        $this->link = $value->absolute();
    }

    public function set_guid(string|Url $value): void
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
    public function get_title(): string { return TextHelper::htmlspecialchars_decode($this->title); }
    public function get_link(): string { return $this->link; }
    public function get_guid(): string { return $this->guid; }
    public function get_date(): ?Date { return $this->date; }
    public function get_desc(): string { return TextHelper::htmlspecialchars_decode($this->desc); }
    public function get_image_url(): string { return $this->image_url; }
	public function get_enclosure(): ?FeedItemEnclosure { return $this->enclosure; }
    public function get_auth(): array { return $this->auth; }
}
?>
