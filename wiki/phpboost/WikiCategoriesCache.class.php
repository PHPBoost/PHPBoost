<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 29
 * @since       PHPBoost 4.1 - 2015 06 29
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
			$row['auth'] = TextHelper::unserialize($row['auth']);
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
