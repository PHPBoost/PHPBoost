<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 11 09
 * @since   	PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class NewsService
{
	private static $db_querier;

	private static $categories_manager;
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

	public static function get_authorized_categories($current_id_category)
	{
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);

		if (AppContext::get_current_user()->is_guest())
			$search_category_children_options->set_allow_only_member_level_authorizations(NewsConfig::load()->are_descriptions_displayed_to_guests());


		$categories = self::get_categories_manager()->get_children($current_id_category, $search_category_children_options, true);
		return array_keys($categories);
	}

	public static function get_categories_manager()
	{
		if (self::$categories_manager === null)
		{
			$categories_items_parameters = new CategoriesItemsParameters();
			$categories_items_parameters->set_table_name_contains_items(NewsSetup::$news_table);
			self::$categories_manager = new CategoriesManager(NewsCategoriesCache::load(), $categories_items_parameters);
		}
		return self::$categories_manager;
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
