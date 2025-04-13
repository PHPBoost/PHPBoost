<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 04 13
 * @since       PHPBoost 6.0 - 2022 11 18
 */

class WikiCache implements CacheData
{
    private $items = [];

    /**
     * {@inheritdoc}
     */
    public function synchronize()
    {
        $this->items = [];

        $now = new Date();

        $result = PersistenceContext::get_querier()->select('SELECT i.*
            FROM ' . WikiSetup::$wiki_articles_table . ' i
            WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
            ORDER BY i_order ASC', [
                'timestamp_now' => $now->get_timestamp()
        ]);

        while ($row = $result->fetch())
        {
            $this->items[$row['id']] = $row;
        }
        $result->dispose();
    }

    public function get_items()
    {
        return $this->items;
    }

    public function item_exists($id)
    {
        return array_key_exists($id, $this->items);
    }

    public function get_item($id)
    {
        if ($this->item_exists($id))
        {
            return $this->items[$id];
        }
        return null;
    }

    public function get_items_number()
    {
        return count($this->items);
    }

    /**
     * Loads and returns the wiki cached data.
     * @return WikiCache The cached data
     */
    public static function load()
    {
        return CacheManager::load(__CLASS__, 'wiki', 'minimenu');
    }

    /**
     * Invalidates the current wiki cached data.
     */
    public static function invalidate()
    {
        CacheManager::invalidate('wiki', 'minimenu');
    }
}
?>
