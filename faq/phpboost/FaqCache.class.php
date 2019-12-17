<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 03 01
 * @since       PHPBoost 4.0 - 2014 09 02
*/

class FaqCache implements CacheData
{
	private $questions = array();
	private $categories = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->questions = $this->categories = array();

		$result = PersistenceContext::get_querier()->select('
			SELECT id, id_category, question
			FROM ' . FaqSetup::$faq_table . ' faq
			WHERE approved = 1
			ORDER BY RAND()
			LIMIT 50'
		);

		while ($row = $result->fetch())
		{
			$this->categories[] = $row['id_category'];

			$this->questions[$row['id_category']][] = array(
				'id' => $row['id'],
				'question' => $row['question']
			);
		}
		$result->dispose();
	}

	public function get_questions()
	{
		return $this->questions;
	}

	public function get_category_questions($id_category)
	{
		return $this->questions[$id_category];
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
