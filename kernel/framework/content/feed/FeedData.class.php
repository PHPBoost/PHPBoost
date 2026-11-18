<?php
/**
 * Contains meta-informations about a feed with its entries
 * @package     Content
 * @subpackage  Feed
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 08
 * @since       PHPBoost 2.0 - 2008 06 21
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FeedData
{
	private string $title = ''; // Feed Title
    private string $link = '';  // Feed Url
    private ?Date $date = null;  // Feed date
    private string $desc = '';  // Feed Description
    private string $lang = '';  // Feed Language
    private string $host = '';  // Feed Host
    private array $items = [];  // Items
    private int $auth_bit = 0;  // Auth bit

    /**
     * Builds a FeedData Object
     * @param FeedData $data an other FeedData object to clone
     */
    public function __construct(?FeedData $data = null)
    {
		if ($data !== null && $data instanceof FeedData)
		{
			$this->title = $data->title;
			$this->link  = $data->link;
			$this->date  = $data->date;
			$this->desc  = $data->desc;
			$this->lang  = $data->lang ;
			$this->host  = $data->host;
			$this->items = $data->items;
		}
	}

    ## Setters ##
    /**
     * Sets the feed title
     * @param string $value The title
     */
    public function set_title(string $value): void { $this->title = strip_tags($value); }
    /**
     * Sets the feed data date
     * @param Date $value a date object representing the feed date
     */
    public function set_date(Date $value): void { $this->date = $value; }
    /**
     * Sets the feed description
     * @param string $value the feed description
     */
    public function set_desc(string $value): void { $this->desc = $value; }
    /**
     * Sets the feed language
     * @param string $value the feed language
     */
    public function set_lang(string $value): void { $this->lang = $value; }
    /**
     * Sets the feed host
     * @param string $value the feed host
     */
    public function set_host(string $value): void { $this->host = $value; }
    /**
     * Sets the feed auth bit, useful to check authorizations
     * @param int $value the bit position in an int (from 1 to 32)
     */
    public function set_auth_bit(int $value): void { $this->auth_bit = $value; }
    /**
     * Sets the feed item link
     * @param mixed $value a string url or an Url object
     */
    public function set_link(string|Url $value): void
    {
        if (!($value instanceof Url))
        {
            $value = new Url($value);
        }
        $this->link = $value->absolute();
    }

    public function add_item(mixed $item): void { array_push($this->items, $item); }

    ## Getters ##
    public function get_title(): string { return $this->title; }
    public function get_link(): string { return $this->link; }
    public function get_date(): Date { return $this->date; }
    public function get_desc(): string { return $this->desc; }
    public function get_lang(): string { return $this->lang; }
    public function get_host(): string { return $this->host; }

    /**
     * Returns the feed items
     * @return FeedItem[] the feed items
     */
    public function get_items(): array
    {
        $items = [];
        foreach ($this->items as $item)
        {
            if ((gettype($item->get_auth()) != 'array' || $this->auth_bit == 0) || AppContext::get_current_user()->check_auth($item->get_auth(), $this->auth_bit))
            {
                $items[] = $item;
            }
        }

        return $items;
    }

    public function serialize()
    {
        return TextHelper::serialize($this);
    }


    /**
     * Returns a items list containing $number items starting from the $begin_at one
     * @param int $number the number of items to retrieve
     * @param int $begin_at the number of the first to retrieve
     * @return FeedItem[] the items list containing $number items starting from the $begin_at one
     */
    public function subitems(int $number = 10, int $begin_at = 0): array
    {
        $secured_items = $this->get_items();
        $nb_items = count($secured_items);

        $items = [];
        $end_at = $begin_at + $number;
        for ($i = $begin_at; ($i < $nb_items) && ($i < $end_at) ; $i++)
        {
            $items[] =& $secured_items[$i];
        }

        return $items;
    }
}
?>
