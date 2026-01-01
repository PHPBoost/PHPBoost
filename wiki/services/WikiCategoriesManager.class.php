<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 6.0 - 2022 11 18
*/

class WikiCategoriesManager extends CategoriesManager
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
        $result = PersistenceContext::get_querier()->select('SELECT i.id
            FROM ' . WikiSetup::$wiki_articles_table . ' i
            LEFT JOIN ' . WikiSetup::$wiki_contents_table . ' c ON c.item_id = i.id
            WHERE id_category = :id_category AND c.item_id = i.id', [
                'id_category' => $id
            ]
        );
        while ($row = $result->fetch())
        {
            for($i = 0; $i < count(WikiService::get_item_content($row['id'])); $i++ )
            {
                PersistenceContext::get_querier()->delete(WikiSetup::$wiki_contents_table, 'WHERE item_id = :id', ['id' => $row['id']]);
            }
            WikiService::delete($row['id'], 0);
        }
        $result->dispose();

        parent::delete($id);
    }
}
?>
