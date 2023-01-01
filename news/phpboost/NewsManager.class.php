<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 28
 * @since       PHPBoost 6.0 - 2021 02 26
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsManager extends ItemsManager
{
	/**
	 * @desc Initialize the additional parameters to custom the request to get items to sort by prioritized items.
	 */
	protected function init_get_items_additional_parameters()
	{
		$this->get_items_static_sort_field = 'top_list_enabled';
		$this->get_items_static_sort_mode = 'DESC';
	}

	/**
	 * @desc Return a list of suggested news, related to the consulted one.
	 * @param string[] $item Item to compare with
	 */
	public function get_suggested_news(Item $item)
	{
		$now = new Date();
		$suggested_news = array();

		$result = self::$db_querier->select('SELECT id, title, id_category, rewrited_title, thumbnail, creation_date, update_date, (2 * FT_SEARCH_RELEVANCE(title, :search_content) + FT_SEARCH_RELEVANCE(content, :search_content) / 3) AS relevance
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
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND update_date < :timestamp AND id_category IN :authorized_categories
		ORDER BY update_date DESC, top_list_enabled DESC
		LIMIT 1
		OFFSET 0)
		UNION
		(SELECT id, title, id_category, rewrited_title, thumbnail, \'NEXT\' as type
		FROM '. self::$items_table .'
		WHERE (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))) AND update_date > :timestamp AND id_category IN :authorized_categories
		ORDER BY update_date ASC, top_list_enabled ASC
		LIMIT 1
		OFFSET 0)', array(
			'timestamp_now' => $now->get_timestamp(),
			'timestamp' => $item->has_update_date() ? $item->get_update_date()->get_timestamp() : $item->get_creation_date()->get_timestamp(),
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
