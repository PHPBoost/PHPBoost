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
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view($request);
					
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'articles');
		$this->view = new FileTemplate('articles/ArticlesDisplaySeveralArticlesController.tpl');
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
					$this->keyword = ArticlesService::get_keywords_manager()->get_keyword('WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));
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
		$now = new Date();

		$mode = $request->get_getstring('sort', 'desc');
		$field = $request->get_getstring('field', 'date');
			
		$sort_mode = ($mode == 'asc') ? 'ASC' : 'DESC';
		switch ($field)
		{
			case 'title':
				$sort_field = 'title';
				break;
			case 'view':
				$sort_field = 'number_view';
				break;
			case 'com':
				$sort_field = 'number_comments';
				break;
			case 'note':
				$sort_field = 'number_notes';
				break;
			case 'author':
				$sort_field = 'author_user_id';
				break;
			default:
				$sort_field = 'date_created';
				break;
		}
		
		$this->view->put_all(array(
			'C_MODERATE' => ArticlesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY)->moderation(),
			'C_MOSAIC' => ArticlesConfig::load()->get_display_type() == ArticlesConfig::DISPLAY_MOSAIC,
			'C_PENDING_ARTICLES' => false,
			'C_PUBLISHED_ARTICLES' => true,
			'C_ARTICLES_CAT' => false,
			'C_COMMENTS_ENABLED' => ArticlesConfig::load()->are_comments_enabled(),
			'C_ARTICLES_FILTERS' => true,
			'L_TAG' => $this->lang['articles.tags'] . ' : ' . $this->get_keyword()->get_name(),
			'U_ADD_ARTICLES' => ArticlesUrlBuilder::add_article(Category::ROOT_CATEGORY)->rel(),
			'U_SYNDICATION' => ArticlesUrlBuilder::category_syndication(Category::ROOT_CATEGORY)->rel()
		));
						
		$authorized_categories = ArticlesService::get_authorized_categories(Category::ROOT_CATEGORY);
		$pagination = $this->get_pagination($now, $authorized_categories, $field, $mode);
		
		$result = PersistenceContext::get_querier()->select('SELECT articles.*, member.*, 
		notes.number_notes, notes.average_notes, note.note FROM ' . ArticlesSetup::$articles_table . ' articles
		LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' relation ON relation.module_id = \'articles\' AND relation.id_in_module = articles.id 
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = articles.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = articles.id AND notes.module_name = "articles"
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = articles.id AND note.module_name = "articles" AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
		WHERE relation.id_keyword = :id_keyword AND (articles.published = 1 OR (articles.published = 2 AND articles.publishing_start_date < :timestamp_now 
		AND (articles.publishing_end_date > :timestamp_now OR articles.publishing_end_date = 0))) 
		ORDER BY ' .$sort_field . ' ' . $sort_mode . ' LIMIT ' . $pagination->get_number_items_per_page() . ' OFFSET ' . $pagination->get_display_from(), array(
			'id_keyword' => $this->keyword->get_id(),
			'timestamp_now' => $now->get_timestamp()
		));

		$this->build_sorting_form($field, $mode);
		
		$this->view->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display()
		));
		
		while ($row = $result->fetch())
		{
			$article = new Articles();
			$article->set_properties($row);
			
			$this->build_keywords_view($article);
			
			$this->view->assign_block_vars('articles', $article->get_tpl_vars());
		}
	}
	
	private function build_keywords_view(Articles $article)
	{
		$keywords = $article->get_keywords();
		$nbr_keywords = count($keywords);
		$this->view->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{	
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => ArticlesUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}
	
	private function build_sorting_form($field, $mode)
	{
		$category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
		
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $this->lang['articles.sort_filter_title']));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, array(
				new FormFieldSelectChoiceOption($this->lang['articles.sort_field.date'], 'date'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_field.title'], 'title'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_field.views'], 'view'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_field.com'], 'com'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_field.note'], 'note'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_field.author'], 'author')
			),
			array('events' => array('change' => 'document.location = "'. ArticlesUrlBuilder::display_pending_articles()->rel() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.asc'], 'asc'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.desc'], 'desc')
			), 
			array('events' => array('change' => 'document.location = "' . ArticlesUrlBuilder::display_pending_articles()->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$this->view->put('FORM', $form->display());
	}
	
	private function get_pagination(Date $now, $authorized_categories, $field, $mode)
	{
		$number_articles = PersistenceContext::get_querier()->count(
			ArticlesSetup::$articles_table, 
			'WHERE id_category IN :id_category AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))', 
			array(
				'id_category' => $authorized_categories,
				'timestamp_now' => $now->get_timestamp()
		));
		
		$current_page = AppContext::get_request()->get_getint('page', 1);
		
		$pagination = new ModulePagination($current_page, $number_articles, ArticlesConfig::load()->get_number_articles_per_page());
		$pagination->set_url(ArticlesUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), $field, $mode, '%d'));
		
		if ($pagination->current_page_is_empty() && $current_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	
		return $pagination;
	}
	
	private function generate_response()
	{
		$response = new ArticlesDisplayResponse();
		$response->set_page_title($this->get_keyword()->get_name());
		$response->set_page_description(StringVars::replace_vars($this->lang['articles.seo.description.tag'], array('subject' => $this->get_keyword()->get_name())));
		
		$response->add_breadcrumb_link($this->lang['articles'], ArticlesUrlBuilder::home());
		$response->add_breadcrumb_link($this->get_keyword()->get_name(), ArticlesUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name()));
		
		return $response->display($this->view);
	}
}
?>