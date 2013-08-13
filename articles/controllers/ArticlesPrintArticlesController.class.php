<?php
/*##################################################
 *		       ArticlesPrintArticlesController.class.php
 *                            -------------------
 *   begin                : June 03, 2013
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

class ArticlesPrintArticlesController extends ModuleController
{	
	private $lang;
	private $view;
	private $auth_moderation;
	private $auth_write;
	private $article;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view($request);
					
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->view = new FileTemplate('articles/ArticlesPrintArticlesController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function get_article()
	{
		if ($this->article === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try 
				{
					$this->article = ArticlesService::get_article('WHERE articles.id=:id', array('id' => $id));
				} 
				catch (RowNotFoundException $e) 
				{
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			    $this->article = new Articles();
		}
		return $this->article;
	}
	
	private function build_view()
	{	
		$article_contents = $this->article->get_contents();
		
		$contents = preg_replace('`\[page\](.*)\[/page\]`', '<h2>$1</h2>', $article_contents);
		
		$this->build_view_sources();
		
		$this->view->put_all(array(
			'PAGE_TITLE' => $this->lang['articles.print.article'] . ' - ' . $this->article->get_title() . ' - ' . GeneralConfig::load()->get_site_name(),
			'TITLE' => $this->article->get_title(),
			'L_SOURCE' => $this->lang['articles.sources'],
			'CONTENTS' => FormatingHelper::second_parse($contents)
		));
	}
	
	private function build_view_sources()
	{
	    $sources = $this->article->get_sources();
	    
	    $this->view->put('C_SOURCES', !empty($sources));
	    
	    $i = 0;
	    foreach ($sources as $name => $url)
	    {	
		    $this->view->assign_block_vars('sources', array(
			    'I' => $i,
			    'NAME' => $name,
			    'URL' => $url,
			    'COMMA' => $i > 0 ? ', ' : ' '
		    ));
		    $i++;
	    }	
	}
		
	private function check_authorizations()
	{
		$article = $this->get_article();
		
		$this->auth_write = ArticlesAuthorizationsService::check_authorizations($article->get_id_category())->write();
		$this->auth_moderation = ArticlesAuthorizationsService::check_authorizations($article->get_id_category())->moderation();
		
		$not_authorized = !$this->auth_moderation && (!$this->auth_write && $article->get_author_user()->get_id() != AppContext::get_current_user()->get_id());
		
		switch ($article->get_publishing_state()) 
		{
			case Articles::PUBLISHED_NOW:
				if (!ArticlesAuthorizationsService::check_authorizations()->read() && $not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Articles::NOT_PUBLISHED:
				if ($not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case Articles::PUBLISHED_DATE:
				if (!$article->is_published() && $not_authorized)
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
		$response = new SiteNodisplayResponse($this->view);
		return $response;
	}
}
?>