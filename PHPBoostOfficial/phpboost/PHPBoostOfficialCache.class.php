<?php
/*##################################################
 *                               PHPBoostOfficialCache.class.php
 *                            -------------------
 *   begin                : December 5, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class PHPBoostOfficialCache implements CacheData
{
	private $last_version = array();
	private $previous_version = array();
	private $last_modules = array();
	private $last_themes = array();
	private $last_news = array();
	
	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$now = new Date();
		$config = PHPBoostOfficialConfig::load();
		
		$versions = $config->get_versions();
		$versions_number = count($versions);
		$modules_number = $themes_number = 0;
		
		while ($versions_number > 0)
		{
			$this->last_version = end($versions);
			$last_version_rewrited_major_version_number = str_replace('.', '-', $this->last_version['major_version_number']);
			try {
				$phpboost_download_id = PersistenceContext::get_querier()->get_column_value(DownloadSetup::$download_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => 'phpboost-' . $last_version_rewrited_major_version_number));
			} catch (RowNotFoundException $e) {
				$phpboost_download_id = 0;
				$this->last_version = array();
			}
			
			if (!empty($phpboost_download_id))
			{
				$phpboost_cat_id = 0;
				try {
					$phpboost_cat_id = PersistenceContext::get_querier()->get_column_value(DownloadSetup::$download_cats_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => 'phpboost-' . $last_version_rewrited_major_version_number));
				} catch (RowNotFoundException $e) {}
				
				if (!empty($phpboost_cat_id))
					$this->last_version['download_link'] = DownloadUrlBuilder::display($phpboost_cat_id, 'phpboost-' . $last_version_rewrited_major_version_number, $phpboost_download_id, 'phpboost-' . $last_version_rewrited_major_version_number)->rel();
			}
			
			$this->previous_version = prev($versions);
			$previous_version_rewrited_major_version_number = str_replace('.', '-', $this->previous_version['major_version_number']);
			try {
				$phpboost_download_id = PersistenceContext::get_querier()->get_column_value(DownloadSetup::$download_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => 'phpboost-' . $previous_version_rewrited_major_version_number));
			} catch (RowNotFoundException $e) {
				$phpboost_download_id = 0;
				$this->previous_version = array();
			}
			
			if (!empty($phpboost_download_id))
			{
				$phpboost_cat_id = 0;
				try {
					$phpboost_cat_id = PersistenceContext::get_querier()->get_column_value(DownloadSetup::$download_cats_table, 'id', 'WHERE rewrited_name = :rewrited_name', array('rewrited_name' => 'phpboost-' . $previous_version_rewrited_major_version_number));
				} catch (RowNotFoundException $e) {}
				
				if (!empty($phpboost_cat_id))
					$this->previous_version['download_link'] = DownloadUrlBuilder::display($phpboost_cat_id, 'phpboost-' . $previous_version_rewrited_major_version_number, $phpboost_download_id, 'phpboost-' . $previous_version_rewrited_major_version_number)->rel();
			}
			
			if (!empty($this->last_version) && !empty($this->previous_version))
			{
				$versions_number = 0;
			}
			else
			{
				unset($versions[$last_version_rewrited_major_version_number]);
				$versions_number--;
			}
		}
		
		$results = PersistenceContext::get_querier()->select('SELECT cats.rewrited_name as cat_rewrited_name, cats.name as cat_name, file.id, file.id_category, file.name, file.rewrited_name, file.short_contents, file.creation_date, file.picture_url, file.author_custom_name, user.display_name
			FROM ' . PREFIX . 'download file
			LEFT JOIN ' . PREFIX . 'download_cats cats ON cats.id = file.id_category
			LEFT JOIN ' . DB_TABLE_MEMBER . ' user ON user.user_id = file.author_user_id
			WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND (cats.rewrited_name LIKE "modules-phpboost-%" OR cats.rewrited_name LIKE "themes-phpboost-%")
			ORDER BY file.creation_date DESC', array(
			'timestamp_now' => $now->get_timestamp()
		));
		
		foreach ($results as $row)
		{
			if ($modules_number == $config->get_last_modules_number() && $themes_number == $config->get_last_themes_number())
				break;
			
			$exploded_cat_name = explode(' ', $row['cat_rewrited_name']);
			$phpboost_version = isset($exploded_cat_name[2]) ? $exploded_cat_name[2] : '';
			
			$item = array(
				'link' => DownloadUrlBuilder::display($row['id_category'], $row['cat_rewrited_name'], $row['id'], $row['rewrited_name'])->rel(),
				'picture' => Url::to_rel($row['picture_url']),
				'title' => $row['name'],
				'description' => $row['short_contents'],
				'version' => $phpboost_version,
				'author' => !empty($row['author_custom_name']) ? $row['author_custom_name'] : $row['display_name']
			);
			
			if (strstr($row['cat_rewrited_name'], 'modules') && $modules_number < $config->get_last_modules_number())
			{
				$this->last_modules[] = $item;
				$modules_number++;
			}
			else if (strstr($row['cat_rewrited_name'], 'themes') && $themes_number < $config->get_last_themes_number())
			{
				$this->last_themes[] = $item;
				$themes_number++;
			}
		}
		$results->dispose();
		
		$news = NewsService::get_news('WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) ORDER BY creation_date DESC LIMIT 0,1', array(
			'timestamp_now' => $now->get_timestamp()
		));
		
		$this->last_news = $news->get_array_tpl_vars();
	}
	
	public function get_last_version()
	{
		return $this->last_version;
	}
	
	public function get_last_version_major_version_number()
	{
		$version = $this->last_version;
		return !empty($version) && isset($version['major_version_number']) ? $version['major_version_number'] : '';
	}
	
	public function get_last_version_minor_version_number()
	{
		$version = $this->last_version;
		return !empty($version) && isset($version['minor_version_number']) ? $version['minor_version_number'] : '';
	}
	
	public function get_last_version_minimal_php_version()
	{
		$version = $this->last_version;
		return !empty($version) && isset($version['minimal_php_version']) ? $version['minimal_php_version'] : '';
	}
	
	public function get_last_version_name()
	{
		$version = $this->last_version;
		return !empty($version) && isset($version['name']) ? $version['name'] : '';
	}
	
	public function get_last_version_download_link()
	{
		$version = $this->last_version;
		return !empty($version) && isset($version['download_link']) ? $version['download_link'] : '';
	}
	
	public function get_last_version_updates_cat_link()
	{
		$version = $this->last_version;
		return !empty($version) && isset($version['updates_cat_link']) ? $version['updates_cat_link'] : '';
	}
	
	public function get_last_version_pdk_link()
	{
		$version = $this->last_version;
		return !empty($version) && isset($version['phpboost_pdk_link']) ? $version['phpboost_pdk_link'] : '';
	}
	
	public function get_last_version_modules_cat_link()
	{
		$version = $this->last_version;
		return !empty($version) && isset($version['modules_cat_link']) ? $version['modules_cat_link'] : '';
	}
	
	public function get_last_version_themes_cat_link()
	{
		$version = $this->last_version;
		return !empty($version) && isset($version['themes_cat_link']) ? $version['themes_cat_link'] : '';
	}
	
	public function get_previous_version()
	{
		return $this->previous_version;
	}
	
	public function get_previous_version_major_version_number()
	{
		$version = $this->previous_version;
		return !empty($version) && isset($version['major_version_number']) ? $version['major_version_number'] : '';
	}
	
	public function get_previous_version_minor_version_number()
	{
		$version = $this->previous_version;
		return !empty($version) && isset($version['minor_version_number']) ? $version['minor_version_number'] : '';
	}
	
	public function get_previous_version_minimal_php_version()
	{
		$version = $this->previous_version;
		return !empty($version) && isset($version['minimal_php_version']) ? $version['minimal_php_version'] : '';
	}
	
	public function get_previous_version_name()
	{
		$version = $this->previous_version;
		return !empty($version) && isset($version['name']) ? $version['name'] : '';
	}
	
	public function get_previous_version_download_link()
	{
		$version = $this->previous_version;
		return !empty($version) && isset($version['download_link']) ? $version['download_link'] : '';
	}
	
	public function get_previous_version_updates_cat_link()
	{
		$version = $this->previous_version;
		return !empty($version) && isset($version['updates_cat_link']) ? $version['updates_cat_link'] : '';
	}
	
	public function get_previous_version_pdk_link()
	{
		$version = $this->previous_version;
		return !empty($version) && isset($version['phpboost_pdk_link']) ? $version['phpboost_pdk_link'] : '';
	}
	
	public function get_previous_version_modules_cat_link()
	{
		$version = $this->previous_version;
		return !empty($version) && isset($version['modules_cat_link']) ? $version['modules_cat_link'] : '';
	}
	
	public function get_previous_version_themes_cat_link()
	{
		$version = $this->previous_version;
		return !empty($version) && isset($version['themes_cat_link']) ? $version['themes_cat_link'] : '';
	}
	
	public function get_last_modules()
	{
		return $this->last_modules;
	}
	
	public function get_last_themes()
	{
		return $this->last_themes;
	}
	
	public function get_last_news()
	{
		return $this->last_news;
	}
	
	/**
	 * Loads and returns the PHPBoostOfficial cached data.
	 * @return PHPBoostOfficialCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'phpboostofficial', 'versions');
	}
	
	/**
	 * Invalidates the current PHPBoostOfficial cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('phpboostofficial', 'versions');
	}
}
?>
