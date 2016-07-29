<?php
/*##################################################
 *		                  NewsDeleteController.class.php
 *                            -------------------
 *   begin                : February 15, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
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
		
		AppContext::get_response()->redirect(($request->get_url_referrer() && !strstr($request->get_url_referrer(), NewsUrlBuilder::display_news($news->get_category()->get_id(), $news->get_category()->get_rewrited_name(), $news->get_id(), $news->get_rewrited_name())->rel()) ? $request->get_url_referrer() : NewsUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('news.message.success.delete', 'common', 'news'), array('name' => $news->get_name())));
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