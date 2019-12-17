<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 04
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class NewsService
{
	private static $db_querier;

	private static $keywords_manager;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
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

	public static function delete($condition, array $parameters)
	{
		self::$db_querier->delete(NewsSetup::$news_table, $condition, $parameters);
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

	public static function update_number_view(News $news)
	{
		self::$db_querier->update(NewsSetup::$news_table, array('number_view' => $news->get_number_view()), 'WHERE id=:id', array('id' => $news->get_id()));
	}

	public static function get_keywords_manager()
	{
		if (self::$keywords_manager === null)
		{
			self::$keywords_manager = new KeywordsManager(NewsKeywordsCache::load());
		}
		return self::$keywords_manager;
	}
}
?>
