<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 19
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ArticlesService
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
		return self::$db_querier->count(ArticlesSetup::$articles_table, $condition, $parameters);
	}

	public static function add(Article $article)
	{
		$result = self::$db_querier->insert(ArticlesSetup::$articles_table, $article->get_properties());
		return $result->get_last_inserted_id();
	}

	public static function update(Article $article)
	{
		self::$db_querier->update(ArticlesSetup::$articles_table, $article->get_properties(), 'WHERE id=:id', array('id', $article->get_id()));
	}

	public static function delete(int $id)
	{
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
		
		self::$db_querier->delete(ArticlesSetup::$articles_table, 'WHERE id=:id', array('id' => $id));
		
		self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'articles', 'id' => $id));

		CommentsService::delete_comments_topic_module('articles', $id);
		KeywordsService::get_keywords_manager()->delete_relations($id);
		NotationService::delete_notes_id_in_module('articles', $id);
	}

	public static function get_article($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT articles.*, member.*, notes.average_notes, notes.number_notes, note.note
		FROM ' . ArticlesSetup::$articles_table . ' articles
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = articles.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = articles.id AND notes.module_name = \'articles\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = articles.id AND note.module_name = \'articles\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
		' . $condition, $parameters);

		$article = new Article();
		$article->set_properties($row);
		return $article;
	}

	public static function clear_cache()
	{
		Feed::clear_cache('articles');
		ArticlesCategoriesCache::invalidate();
		KeywordsCache::invalidate();
	}

	public static function update_number_view(Article $article)
	{
		self::$db_querier->update(ArticlesSetup::$articles_table, array('number_view' => $article->get_number_view()), 'WHERE id=:id', array('id' => $article->get_id()));
	}
}
?>
