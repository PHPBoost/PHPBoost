<?php
/*##################################################
 *		    AdminManageArticlesController.class.php
 *                            -------------------
 *   begin                : June 04, 2013
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
class AdminManageArticlesController extends AdminModuleController
{
	private $lang;
	private $view;
	private $form;
	
	public function execute(HTTPRequestCustom $request)
	{	
		$this->init();
		
		$this->build_view($request);
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->view = new FileTemplate('articles/AdminManageArticlesController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function build_form($field, $mode)
	{
		$category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
		
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHorizontal('filters');
		$form->add_fieldset($fieldset);
		
		$sort_fields = $this->list_sort_fields();
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_fields,
			array('events' => array('change' => 'document.location = "'. ArticlesUrlBuilder::manage_articles()->absolute() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.asc'], 'asc'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.desc'], 'desc')
			), 
			array('events' => array('change' => 'document.location = "' . ArticlesUrlBuilder::manage_articles()->absolute() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$this->form = $form;
	}
	
	private function build_view($request)
	{
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		$mode = $request->get_getstring('sort', 'desc');
		$field = $request->get_getstring('field', 'date');
			
		$sort_mode = ($mode == 'asc') ? 'ASC' : 'DESC';

		switch ($field)
		{
			case 'cat':
				$sort_field = 'id_category';
				break;
			case 'author':
				$sort_field = 'author_user_id';
				break;
			case 'title':
				$sort_field = 'title';
				break;
			case 'published':
				$sort_field = 'published';
				break;
			default:
				$sort_field = 'date_created';
				break;
		}
		
		$current_page = ($request->get_getint('page', 1) > 0) ? $request->get_getint('page', 1) : 1;
		$nbr_articles_per_page = 25;

		$limit_page = (($current_page - 1) * $nbr_articles_per_page);
		
		$result = PersistenceContext::get_querier()->select('SELECT articles.*, articles_cat.name, member.level, member.user_groups, member.user_id, member.login  
		FROM '. ArticlesSetup::$articles_table . ' articles
		LEFT JOIN ' . ArticlesSetup::$articles_cats_table . 'articles_cat ON articles_cat.id = articles.id_category 
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = articles.author_user_id
		ORDER BY ' . $sort_field . ' ' . $sort_mode . ' LIMIT ' . $nbr_articles_per_page .
		' OFFSET ' . $limit_page, SelectQueryResult::FETCH_ASSOC);
		
		$nbr_articles = $result->get_rows_count();
		
		$pagination = new ArticlesPagination($current_page, $nbr_articles, $nbr_articles_per_page);
		$pagination->set_url(ArticlesUrlBuilder::manage_articles()->absolute() . $sort_field . '/' . $sort_mode . '/%d');
		
		$this->build_form($field, $mode);
		
		$this->view->put_all(array(
			'L_PUBLISHED_ARTICLES' => $this->lang['articles.published_articles'],
			'L_MODULE_NAME' => $this->lang['articles'],
			'L_ALERT_DELETE_ARTICLE' => $this->lang['articles.form.alert_delete_article'],
			'L_TOTAL_PENDING' => $nbr_articles_pending > 0 ? sprintf($this->lang['articles.nbr_articles.pending'], $nbr_articles_pending) : '',
			'L_PENDING_ARTICLES' => $this->lang['articles.pending_articles'],
			'L_EDIT_CONFIG' => $this->lang['articles_configuration'],
			'U_PUBLISHED_ARTICLES' => ArticlesUrlBuilder::home()->absolute(), 
			'U_ADD_ARTICLES' => ArticlesUrlBuilder::add_article()->absolute(),
			'U_EDIT_CONFIG' => ArticlesUrlBuilder::articles_configuration()->absolute(),
			'U_SYNDICATION' => ArticlesUrlBuilder::category_syndication(Category::ROOT_CATEGORY)->rel()
		));
		
		if($nbr_articles_pending > 0)
		{	
			$add_auth = ArticlesAuthorizationsService::check_authorizations()->write() || ArticlesAuthorizationsService::check_authorizations()->contribution();
			
			$this->view->put_all(array(
				'C_ARTICLES_FILTERS' => true,
				'C_ADD' => $add_auth,
				'L_DATE' => LangLoader::get_message('date', 'main'),
				'L_VIEW' => LangLoader::get_message('views', 'main'),
				'L_NOTE' => LangLoader::get_message('note', 'main'),
				'L_COM' => LangLoader::get_message('com', 'main'),
				'L_WRITTEN' => LangLoader::get_message('written_by', 'main'),
				'PAGINATION' => ($nbr_articles_pending > $nbr_articles_per_page) ? $pagination->display()->render() : ''
			));
			
			$notation = new Notation();
			$notation->set_module_name('articles');
			$notation->set_notation_scale(ArticlesConfig::load()->get_notation_scale());
			
			while($row = $result->fetch())
			{
				$notation->set_id_in_module($row['id']);
				
				$user_group_color = User::get_group_color($row['user_groups'], $row['level']);
				
				$category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category']);
				$edit_auth = ArticlesAuthorizationsService::check_authorizations($category->get_id())->moderation() || ArticlesAuthorizationsService::check_authorizations($category->get_id())->write() && $row['author_user_id'] == $request->get_current_user()->get_id();
				
				$this->view->assign_block_vars('articles', array(
					'C_DELETE' => ArticlesAuthorizationsService::check_authorizations($category->get_id())->moderation(),
					'C_EDIT' => $edit_auth,
					'C_USER_GROUP_COLOR' => !empty($user_group_color),
					'TITLE' => $row['title'],
					'PICTURE' => $row['picture_url'],
					'DATE' => gmdate_format('date_format_short', $row['date_created']),
					'NUMBER_VIEW' => $row['number_view'],
					'L_NUMBER_COM' => empty($row['number_comments']) ? '0' : $row['number_comments'],
					'NOTE' => (int)NotationService::get_number_notes($notation) > 0 ? NotationService::display_static_image($notation, $row['average_notes']) : $this->lang['articles.no_notes'],
					'DESCRIPTION' =>FormatingHelper::second_parse($row['description']), 
					'PSEUDO' => $row['login'],
					'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
					'USER_GROUP_COLOR' => $user_group_color,
					'U_COMMENTS' => ArticlesUrlBuilder::display_comments_article($category->get_id(), $category->get_rewrited_name(), $row['id'], $row['rewrited_title'])->absolute(),
					'U_AUTHOR' => UserUrlBuilder::profile($row['author_user_id'])->absolute(),
					'U_ARTICLE' => ArticlesUrlBuilder::display_article($category->get_id(), $category->get_rewrited_name(), $row['id'], $row['rewrited_title'])->absolute(),
					'U_EDIT_ARTICLE' => ArticlesUrlBuilder::edit_article($row['id'])->absolute(),
					'U_DELETE_ARTICLE' => ArticlesUrlBuilder::delete_article($row['id'])->absolute()
				));
			}
		}
		else 
		{
			$this->view->put_all(array(
				'L_NO_PENDING_ARTICLES' => $this->lang['articles.no_pending_article']
			));
		}
		$this->view->put('FORM', $this->form->display());
	}
	
	private function list_sort_fields()
	{
		$options = array();

		$options[] = new FormFieldSelectChoiceOption($this->lang['admin.articles.sort_field.cat'], 'cat');
		$options[] = new FormFieldSelectChoiceOption($this->lang['admin.articles.sort_field.title'], 'title');
		$options[] = new FormFieldSelectChoiceOption($this->lang['admin.articles.sort_field.author'], 'author');
		$options[] = new FormFieldSelectChoiceOption($this->lang['admin.articles.sort_field.date'], 'date');
		$options[] = new FormFieldSelectChoiceOption($this->lang['admin.articles.sort_field.published'], 'published');

		return $options;
	}
	
	private function generate_response()
	{
		$response = new ArticlesDisplayResponse();
		$response->set_page_title($this->lang['articles.pending_articles']);
		
		$response->add_breadcrumb_link($this->lang['articles'], ArticlesUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['articles.pending_articles'], ArticlesUrlBuilder::display_pending_articles());
	
		return $response->display($this->view);
	}
}
?>