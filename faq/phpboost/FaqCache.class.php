<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 03 01
 * @since       PHPBoost 4.0 - 2014 09 02
*/

class FaqCache implements CacheData
{
	private $items = array();
	private $categories = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->items = $this->categories = array();

		$result = PersistenceContext::get_querier()->select('
			SELECT id, id_category, title
			FROM ' . FaqSetup::$faq_table . ' faq
			WHERE approved = 1
			ORDER BY RAND()
			LIMIT 50'
		);

		while ($row = $result->fetch())
		{
			$this->categories[] = $row['id_category'];

			$this->items[$row['id_category']][] = array(
				'id' => $row['id'],
				'title' => $row['title']
			);
		}
		$result->dispose();
	}

	public function get_items()
	{
		return $this->items;
	}

	public function get_category_items($id_category)
	{
		return $this->items[$id_category];
	}

	public function get_categories()
	{
		return $this->categories;
	}

	/**
	 * Loads and returns the faq cached data.
	 * @return FaqCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'module', 'faq');
	}

	/**
	 * Invalidates the current faq cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('module', 'faq');
	}
}
?>
