<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 20
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class NewsService
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	 /**
	 * @desc Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 */
	public static function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(NewsSetup::$news_table, $condition, $parameters);
	}

	public static function add(News $news)
	{
		$result = self::$db_querier->insert(NewsSetup::$news_table, $news->get_properties());

		return $result->get_last_inserted_id();
	}

	public static function update(News $news)
	{
		self::$db_querier->update(NewsSetup::$news_table, $news->get_properties(), 'WHERE id=:id', array('id' => $news->get_id()));
	}

	public static function delete(int $id)
	{
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
		
		self::$db_querier->delete(NewsSetup::$news_table, 'WHERE id=:id', array('id' => $id));
		
		self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'news', 'id' => $id));
		
		CommentsService::delete_comments_topic_module('news', $id);
		KeywordsService::get_keywords_manager()->delete_relations($id);
	}

	public static function get_news($condition, array $parameters = array())
	{
		$row = self::$db_querier->select_single_row_query('SELECT news.*, member.*
		FROM ' . NewsSetup::$news_table . ' news
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = news.author_user_id
		' . $condition, $parameters);
		$news = new News();
		$news->set_properties($row);
		return $news;
	}
	
	public static function clear_cache()
	{
		Feed::clear_cache('news');
		NewsCategoriesCache::invalidate();
		KeywordsCache::invalidate();
	}

	public static function update_number_view(News $news)
	{
		self::$db_querier->update(NewsSetup::$news_table, array('number_view' => $news->get_number_view()), 'WHERE id=:id', array('id' => $news->get_id()));
	}
}
?>
