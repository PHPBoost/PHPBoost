<?php
/*##################################################
 *		       ArticlesDeleteController.class.php
 *                            -------------------
 *   begin                : March 05, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class ArticlesDeleteController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();
		
		$article = $this->get_article($request);
		
		if (!$article->is_authorized_to_delete())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
		
		ArticlesService::delete('WHERE id=:id', array('id' => $article->get_id()));
		ArticlesService::get_keywords_manager()->delete_relations($article->get_id());

		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'articles', 'id' => $article->get_id()));
		
		CommentsService::delete_comments_topic_module('articles', $article->get_id());
		NotationService::delete_notes_id_in_module('articles', $article->get_id());
		
		Feed::clear_cache('articles');
		ArticlesCategoriesCache::invalidate();
		
		AppContext::get_response()->redirect(($request->get_url_referrer() && !strstr($request->get_url_referrer(), ArticlesUrlBuilder::display_article($article->get_category()->get_id(), $article->get_category()->get_rewrited_name(), $article->get_id(), $article->get_rewrited_title())->rel()) ? $request->get_url_referrer() : ArticlesUrlBuilder::home()), StringVars::replace_vars(LangLoader::get_message('articles.message.success.delete', 'common', 'articles'), array('title' => $article->get_title())));
	}
	
	private function get_article(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		if (!empty($id))
		{
			try {
				return ArticlesService::get_article('WHERE articles.id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
	}
}
?>