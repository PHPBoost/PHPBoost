<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 31
 * @since       PHPBoost 6.0 - 2021 03 24
*/

class CalendarCategoriesManager extends CategoriesManager
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

		$result = PersistenceContext::get_querier()->select('SELECT id_event
		FROM ' . CalendarSetup::$calendar_events_table . ' event
		LEFT JOIN ' . CalendarSetup::$calendar_events_content_table . ' event_content ON event_content.id = event.content_id
		WHERE id_category = :id_category', array('id_category' => $id));
		while ($row = $result->fetch())
		{
			CalendarService::delete_item($row['id_event']);
		}
		$result->dispose();

		parent::delete($id);
	}
}
?>
