<?php
/*##################################################
 *                      ArticlesDisplayCategoryController.class.php
 *                            -------------------
 *   begin                : May 13, 2013
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
class ArticlesDisplayCategoryController extends ModuleController
{
	private $lang;
	private $category;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'articles');
		$this->view = new FileTemplate('articles/ArticlesDisplaySeveralArticlesController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function build_view()
	{
		$now = new Date();
		$request = AppContext::get_request();
		$mode = $request->get_getstring('sort', ArticlesUrlBuilder::DEFAULT_SORT_MODE);
		$field = $request->get_getstring('field', ArticlesUrlBuilder::DEFAULT_SORT_FIELD);
		
		$this->build_categories_listing_view($now);
		$this->build_articles_listing_view($now, $field, $mode);
		$this->build_sorting_form($field, $mode);
	}
	
	private function build_articles_listing_view(Date $now, $field, $mode)
	{
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
				$sort_field = 'average_notes';
				break;
			case 'author':
				$sort_field = 'author_user_id';
				break;
			default:
				$sort_field = 'date_created';
				break;
		}

		$authorized_categories = ArticlesService::get_authorized_categories($this->get_category()->get_id());
		$pagination = $this->get_pagination($now, $field, $mode);
		
		$result = PersistenceContext::get_querier()->select('SELECT articles.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM ' . ArticlesSetup::$articles_table . ' articles
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = articles.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = articles.id AND com.module_id = \'articles\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = articles.id AND notes.module_name = \'articles\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = articles.id AND note.module_name = \'articles\' AND note.user_id = :user_id
		WHERE articles.id_category = :id_category AND (articles.published = 1 OR (articles.published = 2 AND articles.publishing_start_date < :timestamp_now 
		AND (articles.publishing_end_date > :timestamp_now OR articles.publishing_end_date = 0))) 
		ORDER BY ' .$sort_field . ' ' . $sort_mode . ' 
		LIMIT ' . $pagination->get_number_items_per_page() . ' OFFSET ' . $pagination->get_display_from(), array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		));
		
		$this->view->put_all(array(
			'C_MOSAIC' => ArticlesConfig::load()->get_display_type() == ArticlesConfig::DISPLAY_MOSAIC,
			'C_COMMENTS_ENABLED' => ArticlesConfig::load()->are_comments_enabled(),
			'C_ARTICLES_FILTERS' => true,
			'C_DISPLAY_CATS_ICON' => ArticlesConfig::load()->are_cats_icon_enabled(),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_NO_ARTICLE_AVAILABLE' => $result->get_rows_count() == 0,
			'PAGINATION' => $pagination->display(),
			'ID_CAT' => $this->category->get_id()
		));

		while($row = $result->fetch())
		{
			$article = new Article();
			$article->set_properties($row);
			
			$this->build_keywords_view($article);
			
			$this->view->assign_block_vars('articles', $article->get_tpl_vars());
		}
		$result->dispose();
	}
	
	private function build_categories_listing_view(Date $now)
	{
		$authorized_categories = ArticlesService::get_authorized_categories($this->get_category()->get_id());
		$result = PersistenceContext::get_querier()->select('SELECT @id_cat:= ac.id, ac.id, ac.name, ac.description, ac.image, ac.rewrited_name,
		(SELECT COUNT(*) FROM '. ArticlesSetup::$articles_table .' articles 
		WHERE articles.id_category = @id_cat AND (articles.published = 1 OR (articles.published = 2 AND articles.publishing_start_date < :timestamp_now 
		AND (articles.publishing_end_date > :timestamp_now OR articles.publishing_end_date = 0)))) AS nbr_articles
		FROM ' . ArticlesSetup::$articles_cats_table .' ac 
		WHERE ac.id_parent = :id_category AND ac.id IN :authorized_categories
		ORDER BY ac.id_parent',	array(
			'timestamp_now' => $now->get_timestamp(),
			'authorized_categories' => $authorized_categories,
			'id_category' => $this->category->get_id()
		));
		
		$nbr_cat_displayed = 0;
		while ($row = $result->fetch())
		{	
			$this->view->assign_block_vars('cat_list', array(
				'ID_CATEGORY' => $row['id'],
				'CATEGORY_NAME' => $row['name'],
				'CATEGORY_IMAGE' => $row['image'],
				'CATEGORY_DESCRIPTION' => FormatingHelper::second_parse($row['description']),
				'NBR_ARTICLES' => $row['nbr_articles'],
				'U_CATEGORY' => ArticlesUrlBuilder::display_category($row['id'], $row['rewrited_name'])->rel()
			));
                        
			if (!empty($row['id']))
			{
				$nbr_cat_displayed++;
			}
		}
		$result->dispose();
                
		$nbr_column_cats = ($nbr_cat_displayed > ArticlesConfig::load()->get_number_cols_display_cats()) ? ArticlesConfig::load()->get_number_cols_display_cats() : $nbr_cat_displayed;
		$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
		$column_width_cats = floor(100/$nbr_column_cats);
		
		$this->view->put_all(array('C_ARTICLES_CAT' => $nbr_cat_displayed > 0, 'COLUMN_WIDTH_CAT' => $column_width_cats));
	}
	
	private function build_sorting_form($field, $mode)
	{
		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options');
		
		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $this->lang['articles.sort_filter_title']));
		$form->add_fieldset($fieldset);
				
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, 
			array(
				new FormFieldSelectChoiceOption($this->lang['articles.sort_field.date'], 'date'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_field.title'], 'title'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_field.views'], 'view'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_field.com'], 'com'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_field.note'], 'note'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_field.author'], 'author')
			), 
			array('events' => array('change' => 'document.location = "'. ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->rel() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.asc'], 'asc'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.desc'], 'desc')
			), 
			array('events' => array('change' => 'document.location = "' . ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
		
		$this->view->put('FORM', $form->display());
	}
	
	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
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
	
	private function build_keywords_view(Article $article)
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
		
	private function get_pagination(Date $now, $field, $mode)
	{
		$number_articles = PersistenceContext::get_querier()->count(
			ArticlesSetup::$articles_table, 
			'WHERE id_category = :id_category AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))', 
			array(
				'id_category' => $this->get_category()->get_id(),
				'timestamp_now' => $now->get_timestamp()
		));
		
		$current_page = AppContext::get_request()->get_getint('page', 1);
		$pagination = new ModulePagination($current_page, $number_articles, (int)ArticlesConfig::load()->get_number_articles_per_page());
		$pagination->set_url(ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), $field, $mode, '%d'));
		
		if ($pagination->current_page_is_empty() && $current_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	
		return $pagination;
	}
	
	private function check_authorizations()
	{
		if (!ArticlesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{	
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->category->get_name());
		$graphical_environment->get_seo_meta_data()->set_description($this->category->get_description());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));
	
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['articles'], ArticlesUrlBuilder::home());
		
		$categories = array_reverse(ArticlesService::get_categories_manager()->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), AppContext::get_request()->get_getstring('field', 'date'), AppContext::get_request()->get_getstring('sort', 'desc'), AppContext::get_request()->get_getint('page', 1)));
		}
		
		return $response;
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view();
		return $object->view;
	}
}
?>