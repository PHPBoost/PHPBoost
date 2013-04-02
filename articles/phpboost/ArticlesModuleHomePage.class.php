<?php
/*##################################################
 *                      ArticlesModuleHomePage.class.php
 *                            -------------------
 *   begin                : March 19, 2013
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
	private $auth_read;
	private $add_auth;
	private $edit_auth;
private $auth_moderation;
	
	public static function get_view()
	{
		$object = new self();
		return $object->build_view();
	}
	
	public function build_view()
	{
		$this->check_authorizations();
		$this->init();
		
		$request = AppContext::get_request();
		
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->get_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$categories = ArticlesService::get_categories_manager()->get_childrens(Category::ROOT_CATEGORY, $search_category_children_options);
		$ids_categories = array_keys($categories);
		
		$authorized_cats_sql = !empty($ids_categories) ? 'AND ac.id IN (' . implode(', ', $ids_categories) . ')': '';
		
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		$current_page = ($request->get_getint('page',1) > 0) ? $request->get_getint('page',1) : 1;
		
		$nbr_categories = count($categories);
		$nbr_categories_per_page = ArticlesConfig::load()->get_number_categories_per_page();		
		$nbr_pages =  ceil($nbr_categories / $nbr_categories_per_page);
		$pagination = new Pagination($nbr_pages, $current_page);
		
		$nbr_column_cats = ($nbr_categories > $nbr_categories_per_page) ? $nbr_categories_per_page : $nbr_categories;
		$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
		$column_width_cats = floor(100 / $nbr_column_cats);
		
		$pagination->set_url_sprintf_pattern(ArticlesUrlBuilder::home()->absolute());// @todo : à vérifier, je dois inclure la page ?
		
		$number_articles_not_published = PersistenceContext::get_querier()->count(ArticlesSetup::$articles_table, 'WHERE published=0');
		
		$this->view->put_all(array(
			'C_ADD' => $this->add_auth,
			'C_MODERATE' => $this->auth_moderation,
			'C_PENDING_ARTICLES' => $number_articles_not_published > 0 && $this->auth_moderation,
			'ID_CAT' => ArticlesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY),
			'L_ADD_ARTICLES' => $this->lang['articles.add'],
			'L_MANAGE_CAT' => $this->lang['categories_management'],
			'L_EDIT_CONFIG' => $this->lang['articles_configuration'],
			'L_MODULE_NAME' => $this->lang['articles'],
			'L_PENDING_ARTICLES' => $this->lang['articles.pending_articles'],
			'COLUMN_WIDTH_CATS' => $column_width_cats,
			'PAGINATION' => $pagination->export()->render(),
			'U_EDIT_CONFIG' => ArticlesUrlBuilder::articles_configuration()->absolute(),
			'U_MANAGE_CAT' => ArticlesUrlBuilder::manage_categories()->absolute(),
			'U_PENDING_ARTICLES' => '', // @todo : link
			'U_ADD_ARTICLES' => ArticlesUrlBuilder::add_article()->absolute()
		));

		$limit_page = (($current_page - 1) * $nbr_categories_per_page);
		
		$result = PersistenceContext::get_querier()->select('SELECT @id_cat:= ac.id, ac.name, ac.description, ac.image,
		(SELECT COUNT(*) FROM '. ArticlesSetup::$articles_table .' articles 
		WHERE articles.id_category = @id_cat AND (articles.published = 1 OR (articles.published = 2 
		AND (articles.publishing_start_date < :timestamp_now AND articles.publishing_end_date > :timestamp_now) 
		OR articles.publishing_end_date = 0))) AS nbr_articles FROM ' . ArticlesSetup::$articles_cats_table .
		' ac :authorized_cats ORDER BY ac.id_parent, ac.c_order LIMIT :limit OFFSET :start_limit',
		array(
			'timestamp_now' => $now->get_timestamp(),
			'authorized_cats' => $authorized_cats_sql,
			'limit' => $nbr_categories_per_page,
			'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		
		while ($row = $result->fetch())
		{
			$children_cat = ArticlesService::get_categories_manager()->get_childrens($row['id'], $search_category_children_options);
			
			if (!empty($children_cat))
			{
				foreach ($children_cat as $child_cat)
				{
					$children_cat_links = $children_cat_links . '<a href="' . ArticlesUrlBuilder::display_category($child_cat->get_id(), $child_cat->get_rewrited_name())->absolute() . '">' . $child_cat->get_name() . '</a> / ';
				}
			}
			else
			{
				$children_cat_links = '';
			}
			
			$this->view->assign_block_vars('cat_list', array(
				'ID_CATEGORY' => $row['id'],
				'CATEGORY_NAME' => $row['name'],
				'CATEGORY_DESCRIPTION' => FormatingHelper::second_parse($row['description']),
				'CATEGORY_ICON_SOURCE' => !empty($row['image']) ? ($row['image'] == 'articles.png' || $row['image'] == 'articles_mini.png' ? ArticlesUrlBuilder::home()->absolute() . $row['image'] : $row['image']) : '',
				'L_NBR_ARTICLES_CAT' => sprintf($this->lang['articles.nbr_articles_category'],$row['nbr_articles']),
				'U_CATEGORY' => ArticlesUrlBuilder::display_category($row['id'], $row['rewrited_name'])->absolute(),
				'U_SUBCATS' => $children_cat_links
			));
		}	
	}
	
	private function check_authorizations()
	{
		$this->auth_read = ArticlesAuthorizationsService::check_authorizations()->read();
		$this->add_auth = ArticlesAuthorizationsService::check_authorizations()->write() || ArticlesAuthorizationsService::check_authorizations()->contribution();
		$this->edit_auth = ArticlesAuthorizationsService::check_authorizations()->write() || ArticlesAuthorizationsService::check_authorizations()->moderation();
		$this->auth_moderation = ArticlesAuthorizationsService::check_authorizations()->moderation();
		
		if (!($this->auth_read))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->view = new FileTemplate('articles/ArticlesModuleHomePage.tpl');
		$this->view->add_lang($this->lang);
	}
}
?>