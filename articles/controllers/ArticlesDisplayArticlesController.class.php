<?php
/*##################################################
 *		       ArticlesDisplayArticlesController.class.php
 *                            -------------------
 *   begin                : April 03, 2013
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

class ArticlesDisplayArticlesController extends ModuleController
{	
	private $lang;
	private $tpl;
	
	private $article;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view();
					
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->tpl = new FileTemplate('articles/ArticlesDisplayArticlesController.tpl');
	}
	
	private function get_article()
	{
		if ($this->article === null)
		{
			$id = AppContext::get_request()->get_getint('id');
			if (!empty($id))
			{
				try 
				{
					$this->article = ArticlesService::get_article('WHERE id=:id', array('id' => $id));
				} 
				catch (RowNotFoundException $e) 
				{
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
		}
		return $this->article;
	}
	
	private function build_view()
	{
		
	}
	
	private function check_authorizations()
	{
		$article = $this->get_article();
		
		$no_reading_authorizations = !ArticlesAuthorizationsService::check_authorizations($article->get_id_category())->read() && !ArticlesAuthorizationsService::check_authorizations()->read();
		$no_reading_authorizations_no_approval = $no_reading_authorizations && !ArticlesAuthorizationsService::check_authorizations($article->get_id_category())->moderation() && (!ArticlesAuthorizationsService::check_authorizations($article->get_id_category())->write() && $article->get_author_user_id() != AppContext::get_current_user()->get_id());
		
		switch ($article->get_publishing_state()) 
		{
			case Articles::PUBLISHED_NOW:
				if ($no_reading_authorizations)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Articles::NOT_PUBLISHED:
				if ($no_reading_authorizations_no_approval)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Articles::PUBLISHED_DATE:
				$now = new Date();
				if (!$article->get_publishing_state() && $no_reading_authorizations_no_approval)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			default:
				$error_controller = PHPBoostErrors::unexisting_page();
   				DispatchManager::redirect($error_controller);
			break;
		}
	}
	
	private function generate_response()
	{
		$response = new NewsDisplayResponse();
		$response->set_page_title($this->article->get_title());
		$response->add_breadcrumb_link($this->lang['articles'], ArticlesUrlBuilder::home());
		
		$categories = array_reverse(ArticlesService::get_categories_manager()->get_parents($this->article->get_id_category(), true));
		foreach ($categories as $id => $category)
		{
			if ($id != Category::ROOT_CATEGORY)
				$response->add_breadcrumb_link($category->get_name(), ArticlesUrlBuilder::display_category($id, $category->get_rewrited_name()));
		}
		$category = $categories[$this->article->get_id()];
		$response->add_breadcrumb_link($this->article->get_title(), ArticlesUrlBuilder::display_article($category->get_rewrited_name(), $this->article->get_id(), $this->article->get_rewrited_title()));
		
		return $response->display($this->tpl);
	}
}
?>