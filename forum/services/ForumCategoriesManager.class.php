<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 04 04
 * @since       PHPBoost 6.0 - 2023 01 31
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ForumCategoriesManager extends CategoriesManager
{
	/**
	 * Deletes a category and items.
	 * @param int $id Id of the category to delete.
	 */
	public function delete($id)
	{
		if (!$this->get_categories_cache()->category_exists($id) || $id == Category::ROOT_CATEGORY)
		{
			throw new CategoryNotFoundException($id);
		}
		
		$result = PersistenceContext::get_querier()->select_rows(ForumSetup::$forum_topics_table, array('id'), 'WHERE id_category = :id_category', array('id_category' => $id));
		while ($row = $result->fetch())
		{
			$Forumfct = new Forum();
			$Forumfct->Del_topic($row['id']);
		}
		$result->dispose();

		parent::delete($id);
	}
}
?>
