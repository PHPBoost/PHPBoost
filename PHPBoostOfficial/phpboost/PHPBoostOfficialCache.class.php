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
	
	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$versions = PHPBoostOfficialConfig::load()->get_versions();
		$versions_number = count($versions);
		
		while ($versions_number > 0)
		{
			$this->last_version = end($versions);
			$last_version_rewrited_major_version_number = Url::encode_rewrite($this->last_version['major_version_number']);
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
			$previous_version_rewrited_major_version_number = Url::encode_rewrite($this->previous_version['major_version_number']);
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
