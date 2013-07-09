<?php
/*##################################################
 *		    ArticlesModuleHomePage.class.php
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

class ArticlesModuleHomePage implements ModuleHomePage
{	
	private $lang;
	private $view;
	private $form;
	private $auth_read;
	private $auth_add;
	private $auth_moderation;
	private $auth_write;
	private $category;
	private $search_category_children_options;
	
	public static function get_view()
	{
		$object = new self();
		return $object->build_view();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->view = new FileTemplate('articles/ArticlesDisplayHomeCategoryController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function build_form($field, $mode)
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHorizontal('filters');
		$form->add_fieldset($fieldset);
		
		$sort_fields = $this->list_sort_fields();
		
		$fieldset->add_field(new FormFieldLabel($this->lang['articles.sort_filter_title']));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_fields,
			array('events' => array('change' => 'document.location = "'. ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->absolute() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.asc'], 'asc'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.desc'], 'desc')
			), 
			array('events' => array('change' => 'document.location = "' . ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->absolute() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$this->form = $form;
	}
	
	private function build_view()
	{
		$this->check_authorizations();
		$this->init();
		
		$request = AppContext::get_request();
		
		$authorized_categories = $this->get_authorized_categories();
		
		$authorized_cats_sql = !empty($authorized_categories) ? 'AND ac.id IN (' . implode(', ', $authorized_categories) . ')' : '';
		
		$now = new Date();
				
		$nbr_categories = count($authorized_categories);
		
		$comments_enabled = ArticlesConfig::load()->get_comments_enabled();
		
		$nbr_articles_cat = PersistenceContext::get_querier()->count(ArticlesSetup::$articles_table, 
                                        'WHERE id_category = :id_category AND (published = 1 OR (published = 2 AND (publishing_start_date < :timestamp_now 
                                        AND publishing_end_date = 0) OR publishing_end_date > :timestamp_now))', 
                                        array(
                                            'id_category' => $this->category->get_id(),
                                            'timestamp_now' => $now->get_timestamp()
                                        )
                );
		
		$number_articles_not_published = PersistenceContext::get_querier()->count(ArticlesSetup::$articles_table, 
						    'WHERE published = 0 OR (published = 2 AND (publishing_start_date > ' . $now->get_timestamp() . 
						    ' AND publishing_end_date < ' . $now->get_timestamp() . '))');
		
		$this->view->put_all(array(
			'C_MOSAIC' => ArticlesConfig::load()->get_display_type() == ArticlesConfig::DISPLAY_MOSAIC,
			'C_ADD' => $this->auth_add,
			'C_MODERATE' => $this->auth_moderation,
			'C_PENDING_ARTICLES' => $number_articles_not_published > 0 && $this->auth_moderation,
			'C_COMMENTS_ENABLED' => $comments_enabled,
			'C_ARTICLES_CAT' => $nbr_categories > 0,
			'C_CURRENT_CAT' => $this->category->get_id() != Category::ROOT_CATEGORY,
			'ID_CAT' => $this->category->get_name(),
			'U_EDIT_CONFIG' => ArticlesUrlBuilder::articles_configuration()->absolute(),
			'U_MANAGE_CATEGORIES' => ArticlesUrlBuilder::manage_categories()->absolute(),
			'U_PENDING_ARTICLES' => ArticlesUrlBuilder::display_pending_articles()->absolute(), 
			'U_ADD_ARTICLES' => ArticlesUrlBuilder::add_article()->absolute(),
			'U_SYNDICATION' => ArticlesUrlBuilder::category_syndication($this->category->get_id())->rel()
		));
		
		//All root cats
		$result = PersistenceContext::get_querier()->select('SELECT @id_cat:= ac.id, ac.id, ac.name, ac.description, ac.image, ac.rewrited_name,
		(SELECT COUNT(*) FROM '. ArticlesSetup::$articles_table .' articles 
		WHERE articles.id_category = @id_cat AND (articles.published = 1 OR (articles.published = 2 
		AND (articles.publishing_start_date < :timestamp_now AND articles.publishing_end_date = 0) 
		OR articles.publishing_end_date > :timestamp_now))) AS nbr_articles FROM ' . ArticlesSetup::$articles_cats_table .
		' ac WHERE ac.id_parent = :id_category ' . $authorized_cats_sql . ' ORDER BY ac.id_parent',
		array(
			'timestamp_now' => $now->get_timestamp(),
			'id_category' => $this->category->get_id()
			), SelectQueryResult::FETCH_ASSOC
		);
		//Sub-cats and their children display
		while ($row = $result->fetch())
		{
			$children_cat = ArticlesService::get_categories_manager()->get_childrens($row['id'], $this->search_category_children_options);
			$children_cat_links = '';
			$nbr_children_cat = count($children_cat);
			
			if (!empty($children_cat))
			{
				foreach ($children_cat as $child_cat)
				{
					$children_cat_links .= '<a style="font-size:10px;" href="' . ArticlesUrlBuilder::display_category($child_cat->get_id(), $child_cat->get_rewrited_name())->absolute() . '">' . $child_cat->get_name() . '</a>';
					if ($nbr_children_cat - 1 > 0)
					{
					    $children_cat_links .= ', ';
					    $nbr_children_cat--;
					}
				}
			}
			
			$this->view->assign_block_vars('cat_list', array(
				'ID_CATEGORY' => $row['id'],
				'CATEGORY_NAME' => $row['name'] . ' (' . $row['nbr_articles'] . ')',
				'CATEGORY_DESCRIPTION' => FormatingHelper::second_parse($row['description']),
				'CATEGORY_ICON_SOURCE' => !empty($row['image']) ? ($row['image'] == 'articles.png' || $row['image'] == 'articles_mini.png' ? ArticlesUrlBuilder::home()->absolute() . $row['image'] : $row['image']) : '',
				'L_NBR_ARTICLES_CAT' => StringVars::replace_vars($this->lang['articles.nbr_articles_category'], array('number' => $row['nbr_articles'])),
				'U_CATEGORY' => ArticlesUrlBuilder::display_category($row['id'], $row['rewrited_name'])->absolute(),
				'U_SUBCATEGORIES' => (count($children_cat) > 0) ? $children_cat_links : 'aucune'
			));
		}
		
		//Articles in current cat
		if ($nbr_articles_cat > 0)
		{
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
			
			$current_page = ($request->get_getint('page', 1) > 0) ? $request->get_getint('page', 1) : 1;
			$nbr_articles_per_page = ArticlesConfig::load()->get_number_articles_per_page();
			
			$pagination = new ModulePagination($current_page, $nbr_articles_cat, $nbr_articles_per_page);
			
			$limit_page = $pagination->get_display_from();

			$result = PersistenceContext::get_querier()->select('SELECT articles.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
			FROM ' . ArticlesSetup::$articles_table . ' articles
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = articles.author_user_id
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = articles.id AND com.module_id = "articles"
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = articles.id AND notes.module_name = "articles"
			LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = articles.id AND note.module_name = "articles" AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
			WHERE articles.id_category = :id_category AND (articles.published = 1 OR (articles.published = 2 AND (articles.publishing_start_date < :timestamp_now 
			AND articles.publishing_end_date = 0) OR articles.publishing_end_date > :timestamp_now)) 
			ORDER BY ' .$sort_field . ' ' . $sort_mode . ' LIMIT ' . $nbr_articles_per_page . ' OFFSET ' .$limit_page, 
				array(
					'id_category' => $this->category->get_id(),
					'timestamp_now' => $now->get_timestamp()
				    ), SelectQueryResult::FETCH_ASSOC
			    );
			
			$pagination->set_url(ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name($sort_field, $sort_mode, '/%d')));

			$this->build_form($field, $mode);

			$this->view->put_all(array(
				    'C_ARTICLES_FILTERS' => true,
				    'PAGINATION' => ($nbr_articles_cat > $nbr_articles_per_page) ? $pagination->display()->render() : '',
				    'L_TOTAL_ARTICLES' => StringVars::replace_vars($this->lang['articles.nbr_articles_category'], array('number' => $nbr_articles_cat))
			));

			while($row = $result->fetch())
			{
				$article = new Articles();
				$article->set_properties($row);
				
				$keywords = ArticlesKeywordsService::get_article_keywords($article->get_id());
				
				$keywords_list = $this->build_keywords_list($keywords);
				
				$this->view->assign_block_vars('articles', array_merge($article->get_tpl_vars(), array(
					'C_KEYWORDS' => $keywords->get_rows_count() > 0 ? true : false,
					'U_KEYWORDS_LIST' => $keywords_list
				)));
			}
			
			$this->view->put('FORM', $this->form->display());
		}
		else 
		{
			$this->view->put_all(array(
				'L_NO_ARTICLE' => $this->lang['articles.no_article.category']
			));
		}
		return $this->view;
	}
	
	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id', 0);
			if (!empty($id))
			{
				try {
					$row = PersistenceContext::get_querier()->select_single_row(ArticlesSetup::$articles_cats_table, array('*'), 'WHERE id=:id', array('id' => $id));

					$category = new RichCategory();
					$category->set_properties($row);
					$this->category = $category;
				} 
				catch (RowNotFoundException $e) 
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}
	
	private function build_keywords_list($keywords)
	{
		$keywords_list = '';
		$nbr_keywords = $keywords->get_rows_count();
		
		while ($row = $keywords->fetch())
		{	
			$keywords_list .= '<a class="small_link" href="' . ArticlesUrlBuilder::display_tag($row['rewrited_name'])->absolute() . '">' . $row['name'] . '</a>';
			if ($nbr_keywords - 1 > 0)
			{
				$keywords_list .= ', ';
				$nbr_keywords--;
			}
		}
		return $keywords_list;
	}
	
	private function get_authorized_categories()
	{
		$this->search_category_children_options = new SearchCategoryChildrensOptions();
		$this->search_category_children_options->get_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$this->search_category_children_options->set_enable_recursive_exploration(false);
		$authorized_categories = ArticlesService::get_categories_manager()->get_childrens($this->category->get_id(), $this->search_category_children_options);
		$ids_categories = array_keys($authorized_categories);
		
		return $ids_categories;
	}
	
	private function check_authorizations()
	{
		$category = $this->get_category();
		
		$this->auth_read = ArticlesAuthorizationsService::check_authorizations($category->get_id())->read();
		$this->auth_write = ArticlesAuthorizationsService::check_authorizations($category->get_id())->write();
		$this->auth_add = ArticlesAuthorizationsService::check_authorizations($category->get_id())->write() || ArticlesAuthorizationsService::check_authorizations($category->get_id())->contribution();
		$this->auth_moderation = ArticlesAuthorizationsService::check_authorizations($category->get_id())->moderation();
		
		if (!($this->auth_read))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function list_sort_fields()
	{
		$options = array();

		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.date'], 'date');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.title'], 'title');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.views'], 'view');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.com'], 'com');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.note'], 'note');
		$options[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.author'], 'author');

		return $options;
	}
}
?>