<?php
/*##################################################
 *                               WikiCategoriesCache.class.php
 *                            -------------------
 *   begin                : June 29, 2015
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

class WikiCategoriesCache implements CacheData
{
	private $categories = array();
	
	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->categories = array();
		
		$result = PersistenceContext::get_querier()->select("SELECT c.id, c.id_parent, c.article_id, a.title, a.encoded_title, a.auth
		FROM " . WikiSetup::$wiki_cats_table . " c
		LEFT JOIN " . WikiSetup::$wiki_articles_table . " a ON a.id = c.article_id
		ORDER BY c.id_parent, a.title");
		
		while ($row = $result->fetch())
		{
			$row['auth'] = unserialize($row['auth']);
			$this->categories[$row['id']] = $row;
		}
	}
	
	public function get_categories()
	{
		return $this->categories;
	}
	
	public function category_exists($id)
	{
		return array_key_exists($id, $this->categories);
	}
	
	public function get_category($id)
	{
		if ($this->category_exists($id))
		{
			return $this->categories[$id];
		}
		return null;
	}
	
	public function get_number_categories()
	{
		return count($this->categories);
	}
	
	/**
	 * Loads and returns the wiki categories cached data.
	 * @return WikiCategoriesCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'wiki', 'categories');
	}
	
	/**
	 * Invalidates the current wiki categories cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('wiki', 'categories');
	}
}
?>
