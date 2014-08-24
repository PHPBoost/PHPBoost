<?php
/*##################################################
 *                               WebCache.class.php
 *                            -------------------
 *   begin                : August 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */

class WebCache implements CacheData
{
	private $partners_weblinks = array();
	
	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->partners_weblinks = array();
		
		$config = WebConfig::load();
		
		$result = PersistenceContext::get_querier()->select('
			SELECT web.id, web.name, web.partner_picture
			FROM ' . WebSetup::$web_table . ' web
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
			WHERE approved = 1 AND partner = 1
			ORDER BY ' . $config->get_sort_type() . ' ' . $config->get_sort_mode() . '
			LIMIT :partners_number_in_menu OFFSET 0', array(
				'partners_number_in_menu' => (int)$config->get_partners_number_in_menu()
		));
		
		while ($row = $result->fetch())
		{
			$this->partners_weblinks[] = array(
				'id' => $row['id'],
				'name' => $row['name'],
				'partner_picture' => $row['partner_picture']
			);
		}
	}
	
	public function get_partners_weblinks()
	{
		return $this->partners_weblinks;
	}
	
	public function partner_weblink_exists($id)
	{
		return array_key_exists($id, $this->partners_weblinks);
	}
	
	public function get_partner_weblink_item($id)
	{
		if ($this->partner_weblink_exists($id))
		{
			return $this->partners_weblinks[$id];
		}
		return null;
	}
	
	public function get_number_partners_weblinks()
	{
		return count($this->partners_weblinks);
	}
	
	/**
	 * Loads and returns the web cached data.
	 * @return WebCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'module', 'web');
	}
	
	/**
	 * Invalidates the current web cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('module', 'web');
	}
}
?>
