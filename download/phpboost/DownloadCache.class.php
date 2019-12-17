<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2014 08 24
*/

class DownloadCache implements CacheData
{
	private $downloadfiles = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->downloadfiles = array();

		$now = new Date();
		$config = DownloadConfig::load();
		$oldest_file_date = new Date(date('Y-m-d', strtotime('-' . $config->get_oldest_file_day_in_menu() . ' day')));

		$result = PersistenceContext::get_querier()->select('
			SELECT download.*, notes.average_notes, notes.number_notes
			FROM ' . DownloadSetup::$download_table . ' download
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = download.id AND notes.module_name = \'download\'
			WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))
			' . ($config->is_limit_oldest_file_day_in_menu_enabled() ? 'AND updated_date > :oldest_file_date' : '') . '
			ORDER BY ' . $config->get_sort_type() . ' DESC
			LIMIT :files_number_in_menu OFFSET 0', array(
				'timestamp_now' => $now->get_timestamp(),
				'files_number_in_menu' => (int)$config->get_files_number_in_menu(),
				'oldest_file_date' => $oldest_file_date->get_timestamp()
		));

		while ($row = $result->fetch())
		{
			$this->downloadfiles[$row['id']] = $row;
		}
		$result->dispose();
	}

	public function get_downloadfiles()
	{
		return $this->downloadfiles;
	}

	public function downloadfile_exists($id)
	{
		return array_key_exists($id, $this->downloadfiles);
	}

	public function get_downloadfile_item($id)
	{
		if ($this->downloadfile_exists($id))
		{
			return $this->downloadfiles[$id];
		}
		return null;
	}

	public function get_number_downloadfiles()
	{
		return count($this->downloadfiles);
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
