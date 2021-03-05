<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 05
 * @since       PHPBoost 6.0 - 2021 02 26
*/

class NewsManager extends ItemsManager
{
	 /**
	 * @desc Return the list of items corresponding to the condition.
	 * @param string $condition Restriction to apply to the item
	 * @param array $parameters Parameters of the condition
	 * @param int $number_items_per_page Number of items to display
	 * @param int $display_from First item to take into account
	 * @param string $sort_field Field on which apply the sorting
	 * @param string $sort_mode Sort mode (asc or desc)
	 */
	public function get_items($condition = '', array $parameters = array(), int $number_items_per_page = 0, int $display_from = 0, $sort_field = '', $sort_mode = 'DESC', $keywords = false)
	{
		$now = new Date();
		$items = array();
		
		$result = self::$db_querier->select('SELECT ' . self::$module_id . '.*, member.*, comments_topic.number_comments, average_notes.average_notes, average_notes.number_notes, note.note
		FROM ' . self::$items_table . ' ' . self::$module_id . '
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = ' . self::$module_id . '.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' comments_topic ON comments_topic.module_id = :module_id AND comments_topic.id_in_module = ' . self::$module_id . '.id
		' . ($keywords ? 'LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' keywords_relations ON keywords_relations.module_id = :module_id AND keywords_relations.id_in_module = ' . self::$module_id . '.id' : '') . '
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' average_notes ON average_notes.module_name = :module_id AND average_notes.id_in_module = ' . self::$module_id . '.id
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.module_name = :module_id AND note.id_in_module = ' . self::$module_id . '.id AND note.user_id = :current_user_id
		' . $condition . '
		ORDER BY top_list_enabled DESC' . ($sort_field ? ', ' . $sort_field . ' ' . $sort_mode : '') . '
		' . ($number_items_per_page ? 'LIMIT :number_items_per_page OFFSET :display_from' : ''), array_merge($parameters, array(
			'module_id'             => self::$module_id,
			'current_user_id'       => AppContext::get_current_user()->get_id(),
			'timestamp_now'         => $now->get_timestamp(),
			'number_items_per_page' => $number_items_per_page,
			'display_from'          => $display_from
		)));
		
		while ($row = $result->fetch())
		{
			$item = self::get_item_class();
			$item->set_properties($row);
			$items[] = $item;
		}
		$result->dispose();
		
		return $items;
	}

	 /**
	 * @desc Return a list of suggested news, related to the consulted one.
	 * @param string[] $item Item to compare with
	 */
	public function get_suggested_news(Item $item)
	{
		$now = new Date();
		$suggested_news = array();
		
		$result = self::$db_querier->select('SELECT id, title, id_category, rewrited_title, thumbnail, (2 * FT_SEARCH_RELEVANCE(title, :search_content) + FT_SEARCH_RELEVANCE(content, :search_content) / 3) AS relevance
		FROM ' . self::$items_table . '
		WHERE (FT_SEARCH(title, :search_content) OR FT_SEARCH(content, :search_content)) AND id <> :excluded_id
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
		ORDER BY relevance DESC
		LIMIT 0, 10', array(
			'excluded_id' => $item->get_id(),
			'search_content' => $item->get_title() . ',' . $item->get_content(),
			'timestamp_now' => $now->get_timestamp()
		));
		
		while ($row = $result->fetch())
		{
			$suggested_news[] = $row;
		}
		$result->dispose();
		
		return $suggested_news;
	}

	 /**
	 * @desc Return the previous and the next news from the consulted one.
	 * @param string[] $item Item the current news
	 */
	public function get_navigation_links(Item $item)
	{
		$now = new Date();
		$navigation_links = array();
		
		$result = self::$db_querier->select('(SELECT id, title, id_category, rewrited_title, thumbnail, \'PREVIOUS\' as type
		FROM '. self::$items_table .'
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND creation_date < :timestamp AND id_category IN :authorized_categories
		ORDER BY creation_date DESC
		LIMIT 1
		OFFSET 0)
		UNION
		(SELECT id, title, id_category, rewrited_title, thumbnail, \'NEXT\' as type
		FROM '. self::$items_table .'
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND creation_date > :timestamp AND id_category IN :authorized_categories
		ORDER BY creation_date ASC
		LIMIT 1
		OFFSET 0)', array(
			'timestamp_now' => $now->get_timestamp(),
			'timestamp' => $item->get_creation_date()->get_timestamp(),
			'authorized_categories' => array($item->get_id_category())
		));
		
		while ($row = $result->fetch())
		{
			$navigation_links[] = $row;
		}
		$result->dispose();
		
		return $navigation_links;
	}
}
?>
