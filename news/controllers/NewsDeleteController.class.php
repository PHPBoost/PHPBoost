<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 09
 * @since       PHPBoost 4.0 - 2013 02 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class NewsDeleteController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$news = $this->get_news($request);

		if (!$news->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}

		NewsService::delete('WHERE id=:id', array('id' => $news->get_id()));
		NewsService::get_keywords_manager()->delete_relations($news->get_id());

		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'news', 'id' => $news->get_id()));

		CommentsService::delete_comments_topic_module('news', $news->get_id());

		Feed::clear_cache('news');
		NewsCategoriesCache::invalidate();
		NewsKeywordsCache::invalidate();

		AppContext::get_response()->redirect(($request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), NewsUrlBuilder::display_news($news->get_category()->get_id(), $news->get_category()->get_rewrited_name(), $news->get_id(), $news->get_rewrited_name())->rel()) ? $request->get_url_referrer() : NewsUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('news.message.success.delete', 'common', 'news'), array('name' => $news->get_name())));
	}

	private function get_news(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id))
		{
			try {
				return NewsService::get_news('WHERE id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>
