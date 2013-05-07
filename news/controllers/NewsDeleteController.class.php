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
		$news = $this->get_news();
		
		if (!(NewsAuthorizationsService::check_authorizations($news->get_id_cat())->moderation() || (NewsAuthorizationsService::check_authorizations($news->get_id_cat())->write() && $news->get_user_id() == AppContext::get_current_user()->get_id())))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
   			DispatchManager::redirect($error_controller);
		}
		
		NewsService::delete('WHERE id=:id', array('id' => $news->get_id()));
		PersistenceContext::get_querier()->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'news', 'id' => $news->get_id()));
		
		CommentsService::delete_comments_topic_module('news', $news['id']);
	    
	    Feed::clear_cache('news');
	    
	    AppContext::get_response()->redirect(NewsUrlBuilder::home());
	}
	
	private function get_news(HTTPRequestCustom $request)
	{
		$id = AppContext::get_request()->get_getint('id', 0);
	
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