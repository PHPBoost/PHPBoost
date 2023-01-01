<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadCache implements CacheData
{
	private $items = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->items = array();

		$now = new Date();
		$config = DownloadConfig::load();
		$oldest_file_date = new Date(date('Y-m-d', strtotime('-' . $config->get_oldest_file_day_in_menu() . ' day')));

		$result = PersistenceContext::get_querier()->select('
			SELECT download.*, notes.average_notes, notes.notes_number
			FROM ' . DownloadSetup::$download_table . ' download
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = download.id AND notes.module_name = \'download\'
			WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
			' . ($config->is_limit_oldest_file_day_in_menu_enabled() ? 'AND update_date > :oldest_file_date' : '') . '
			ORDER BY ' . $config->get_sort_type() . ' DESC
			LIMIT :files_number_in_menu OFFSET 0', array(
				'timestamp_now' => $now->get_timestamp(),
				'files_number_in_menu' => (int)$config->get_files_number_in_menu(),
				'oldest_file_date' => $oldest_file_date->get_timestamp()
		));

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
	 * Loads and returns the download cached data.
	 * @return DownloadCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'download', 'minimenu');
	}

	/**
	 * Invalidates the current download cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('download', 'minimenu');
	}
}
?>
