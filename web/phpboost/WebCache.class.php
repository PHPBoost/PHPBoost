<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 12
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebCache implements CacheData
{
	private $partners_items = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->partners_items = array();

		$now = new Date();
		$config = WebConfig::load();

		$result = PersistenceContext::get_querier()->select('
			SELECT web.id, web.title, web.rewrited_title, web.id_category, cats.rewrited_name AS category_rewrited_name, web.partner_thumbnail
			FROM ' . WebSetup::$web_table . ' web
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = web.id AND com.module_id = \'web\'
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
			LEFT JOIN ' . WebSetup::$web_cats_table . ' cats ON cats.id = web.id_category
			WHERE (partner = 1 OR privileged_partner = 1) AND (web.published = 1 OR (web.published = 2 AND (web.publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now))))
			ORDER BY web.privileged_partner DESC, ' . $config->get_partners_sort_field() . ' ' . $config->get_partners_sort_mode() . '
			LIMIT :partners_number_in_menu OFFSET 0', array(
				'timestamp_now' => $now->get_timestamp(),
				'partners_number_in_menu' => (int)$config->get_partners_number_in_menu()
		));

		while ($row = $result->fetch())
		{
			$this->partners_items[$row['id']] = $row;
		}
		$result->dispose();
	}

	public function get_partners_weblinks()
	{
		return $this->partners_items;
	}

	public function partner_weblink_exists($id)
	{
		return array_key_exists($id, $this->partners_items);
	}

	public function get_partner_weblink_item($id)
	{
		if ($this->partner_weblink_exists($id))
		{
			return $this->partners_items[$id];
		}
		return null;
	}

	public function get_number_partners_weblinks()
	{
		return count($this->partners_items);
	}

	/**
	 * Loads and returns the web cached data.
	 * @return WebCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'web', 'minimenu');
	}

	/**
	 * Invalidates the current web cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('web', 'minimenu');
	}
}
?>
