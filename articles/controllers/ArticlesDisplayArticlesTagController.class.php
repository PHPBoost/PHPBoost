<?php
/*##################################################
 *		       ArticlesDisplayArticlesTagController.class.php
 *                            -------------------
 *   begin                : June 05, 2013
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

class ArticlesDisplayArticlesTagController extends ModuleController
{	
	private $lang;
	private $view;
	private $keyword;
	private $category;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view($request);
					
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->view = new FileTemplate('articles/ArticlesDisplayArticlesTagController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function get_keyword()
	{
		if ($this->keyword === null)
		{
			$rewrited_name = AppContext::get_request()->get_getstring('tag', '');
			if (!empty($rewrited_name))
			{
				try {
					$row = PersistenceContext::get_querier()->select_single_row(ArticlesSetup::$articles_keywords_relation_table, array('*'), 'WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));
					
					$keyword = new ArticlesKeywords();
					$keyword->set_properties($row);
					$this->keyword = $keyword;
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$error_controller = PHPBoostErrors::unexisting_page();
   				DispatchManager::redirect($error_controller);
			}
		}
		return $this->keyword;
	}
	
	private function build_view($request)
	{
		$this->get_keyword();
		
		$auth_add = ArticlesAuthorizationsService::check_authorizations()->write() || ArticlesAuthorizationsService::check_authorizations()->contribution();
		$comments_enabled = ArticlesConfig::load()->get_comments_enabled();
		
		$this->view->put_all(array(
			'C_ADD' => $auth_add,
			'C_COMMENTS_ENABLED' => $comments_enabled,
			'L_EDIT_CONFIG' => $this->lang['articles_configuration'],
			'L_ADD_ARTICLES' => $this->lang['articles.add'],
			'L_MODULE_NAME' => $this->lang['articles'],
			'L_TAG' => $this->lang['articles.tag'] . $this->keyword->get_name(),
			'U_ADD_ARTICLES' => ArticlesUrlBuilder::add_article()->absolute(),
			'U_EDIT_CONFIG' => ArticlesUrlBuilder::articles_configuration()->absolute(),
			'U_SYNDICATION' => ArticlesUrlBuilder::category_syndication($this->category->get_id())->rel()
		));
		
		$current_page = $request->get_getint('page', 1);
		$nbr_articles_per_page = ArticlesConfig::load()->get_number_articles_per_page();
		$limit_page = (($current_page - 1) * $nbr_articles_per_page);
		
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		$result = PersistenceContext::get_querier()->select('SELECT articles.*, member.*, 
		com.number_comments, note.number_notes, note.average_notes FROM ' . ArticlesSetup::$articles_table . ' articles
		LEFT JOIN '. ArticlesSetup::$articles_keywords_relation_table .' relation ON relation.id_article = articles.id 
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = articles.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = articles.id AND com.module_id = "articles"
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' note ON note.id_in_module = articles.id AND note.module_name = "articles"
		WHERE realtion.id_keyword = :id_keyword AND (articles.published = 1 OR (articles.published = 2 AND (articles.publishing_start_date < :timestamp_now 
		AND articles.publishing_end_date = 0) OR articles.publishing_end_date > :timestamp_now)) 
		LIMIT ' . $nbr_articles_per_page . ' OFFSET ' .$limit_page, 
			array(
				'id_keyword' => $this->keyword->get_id(),
				'timestamp_now' => $now->get_timestamp()
			    ), SelectQueryResult::FETCH_ASSOC
		    );
		
		$nbr_articles = $result->get_rows_count();
		
		$pagination = new ArticlesPagination($current_page, $nbr_articles, $nbr_articles_per_page);
		$pagination->set_url(ArticlesUrlBuilder::display_tag($this->keyword->get_rewrited_name())->absolute() . '%d');
		
		$notation = new Notation();
		$notation->set_module_name('articles');
		$notation->set_notation_scale(ArticlesConfig::load()->get_notation_scale());
		
		while ($row = $result->fetch())
		{
			$article = new Articles();
			$article->set_properties($row);
			
			$category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category($article->get_id_category());
			
			$user = $article->get_author_user();
			$auth_moderation = ArticlesAuthorizationsService::check_authorizations($category->get_id())->moderation();
			$auth_write = ArticlesAuthorizationsService::check_authorizations($category->get_id())->write();
			
			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			
			$notation->set_id_in_module($article->get_id());
			
			$this->view->assign_block_vars('articles', array(
			    'C_EDIT' => $auth_moderation || $auth_write && $article->get_author_user()->get_id() == AppContext::get_current_user()->get_id(),
			    'C_DELETE' => $auth_moderation,
			    'C_USER_GROUP_COLOR' => !empty($user_group_color),
			    'C_AUTHOR_DISPLAYED' => $article->get_author_name_displayed(),
			    'C_NOTATION_ENABLED' => $article->get_notation_enabled(),
			    'TITLE' => $article->get_title(),
			    'PICTURE' => $article->get_picture(),
			    'DATE' => $article->get_date_created()->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO),
			    'L_COMMENTS' => CommentsService::get_number_and_lang_comments('articles', $article->get_id()),
			    'L_DATE' => LangLoader::get_message('date', 'main'),
			    'L_VIEW' => LangLoader::get_message('views', 'main'),
			    'L_NO_AUTHOR_DISPLAYED' => $this->lang['articles.no_author_diplsayed'],
			    'L_ALERT_DELETE_ARTICLE' => $this->lang['articles.form.alert_delete_article'],
			    'NOTE' => $row['number_notes'] > 0 ? NotationService::display_static_image($notation, $row['average_notes']) : $this->lang['articles.no_notes'],
			    'PSEUDO' => $user->get_pseudo(),
			    'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			    'USER_GROUP_COLOR' => $user_group_color,
			    'U_COMMENTS' => ArticlesUrlBuilder::display_comments_article($category->get_id(), $category->get_rewrited_name(), $article->get_id(), $article->get_rewrited_title())->absolute(),
			    'U_AUTHOR' => UserUrlBuilder::profile($article->get_author_user()->get_id())->absolute(),
			    'U_EDIT_ARTICLE' => ArticlesUrlBuilder::edit_article($article->get_id())->absolute(),
			    'U_DELETE_ARTICLE' => ArticlesUrlBuilder::delete_article($article->get_id())->absolute()
			));
		}
	}
	
	private function generate_response()
	{
		$response = new ArticlesDisplayResponse();
		$response->set_page_title($this->keyword->get_name());
		$response->add_breadcrumb_link($this->lang['articles'], ArticlesUrlBuilder::home());
		$response->add_breadcrumb_link($this->keyword->get_name(), ArticlesUrlBuilder::display_tag($this->keyword->get_rewrited_name()));
		
		return $response->display($this->view);
	}
}
?>