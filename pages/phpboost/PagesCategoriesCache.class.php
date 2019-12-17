<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 29
 * @since       PHPBoost 4.1 - 2015 06 29
*/

class PagesCategoriesCache implements CacheData
{
	private $categories = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->categories = array();

		$result = PersistenceContext::get_querier()->select("SELECT c.id, c.id_parent, c.id_page, p.title, p.auth
		FROM " . PagesSetup::$pages_cats_table . " c
		LEFT JOIN " . PagesSetup::$pages_table . " p ON p.id = c.id_page
		ORDER BY c.id_parent, p.title");

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
	 * Loads and returns the pages categories cached data.
	 * @return PagesCategoriesCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'pages', 'categories');
	}

	/**
	 * Invalidates the current pages categories cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('pages', 'categories');
	}
}
?>
