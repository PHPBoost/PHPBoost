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
		$request = AppContext::get_request();
                $categories = ArticlesCategoriesCache::load()->get_categories();
                
		$this->init();
		
                $unauthorized_cats = $this->check_authorizations($categories);
                $nbr_unauthorized_cats = count($unauthorized_cats);
                
                $unauthorized_cats_sql = ($nbr_unauthorized_cats > 0) ? 'AND ac.id NOT IN (' . implode(', ', $unauthorized_cats) . ')': '';
                
                $now = new Date(DATE_NOW, TIMEZONE_AUTO);
                
		$current_page = ($request->get_getint('page',1) > 0) ? $request->get_getint('page',1) : 1;
		
                
                $nbr_categories = count($categories);
                $nbr_categories_per_page = ArticlesConfig::load()->get_number_categories_per_page();		
                $nbr_pages =  ceil($nbr_categories / $nbr_categories_per_page);
		$pagination = new Pagination($nbr_pages, $current_page);
		
		$pagination->set_url_sprintf_pattern(ArticlesUrlBuilder::home());
                
                $number_articles_not_published = PersistenceContext::get_querier()->count(ArticlesSetup::$articles_table, 'WHERE published=0');
		
                $this->view->put_all(array(
			'C_IS_MODERATOR' => $this->auth_moderation,
                        'C_PENDING_ARTICLES' => $number_articles_not_published > 0 && $this->auth_moderation,
                        'PAGINATION' => $pagination->export()->render(),
                        'U_EDIT_CONFIG' => ArticlesUrlBuilder::articles_configuration()->absolute(),
                        'U_PENDING_ARTICLES_LINK' => '', // @todo : link
                        'U_ADD_ARTICLES' => ArticlesUrlBuilder::add_article()->absolute()
		));

		$limit_page = (($current_page - 1) * $nbr_categories_per_page);
		
		$result = PersistenceContext::get_querier()->select('SELECT @id_cat:= ac.id, ac.name, ac.description, ac.image,
                        (SELECT COUNT(*) FROM '. ArticlesSetup::$articles_table .' articles 
                        WHERE articles.id_category = @id_cat AND (articles.published = 1 OR (articles.published = 2 
                        AND (articles.publishing_start_date < :timestamp_now AND articles.publishing_end_date > :timestamp_now) 
                        OR articles.publishing_end_date = 0))) AS nbr_articles FROM ' . ArticlesSetup::$articles_cats_table .
                        ' ac :unauthorized_cats ORDER BY ac.id_parent, ac.c_order LIMIT :limit OFFSET :start_limit',
			array(
				'timestamp_now' => $now->get_timestamp(),
                                'unauthorized_cats' => $unauthorized_cats_sql,
                                'limit' => $nbr_categories_per_page,
                                'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
                
		while ($row = $result->fetch())
		{
			$childrens_cat = ArticlesCategoriesCache::load()->get_childrens($row['id']);
                        $nbr_childrens_cat = count($childrens_cat);
                        
                        if ($nbr_childrens_cat > 0)
                        {
                            foreach ($childrens_cat as $children_cat)
                            {
                                $childrens_cat_links = $childrens_cat_links . '<a href="' . ArticlesUrlBuilder::display_category($children_cat['id'], $children_cat['rewrited_name'])->absolute() . '">' . $children_cat['name'] . '</a> / ' ;
                            }
                        }
                        else
                        {
                            $childrens_cat_links = '';
                        }
                        
                        $this->view->assign_block_vars('cat_list', array(
                                'ID_CATEGORY' => $row['id'],
                                'CATEGORY_NAME' => $row['name'],
                                'CATEGORY_DESCRIPTION' => FormatingHelper::second_parse($rwo['description']),
                                'CATEGORY_ICON_SOURCE' => !empty($row['image']) ? ($row['image'] == 'articles.png' || $row['image'] == 'articles_mini.png' ? ArticlesUrlBuilder::home()->absolute() . $row['image'] : $row['image']) : '',
                                'L_NBR_ARTICLES_CAT' => sprintf($this->lang['articles.nbr_articles_category'],$row['nbr_articles']),
                                'U_CATEGORY' => ArticlesUrlBuilder::display_category($row['id'], $row['rewrited_name'])->absolute(),
                                'U_SUBCATS' => $childrens_cat_links
                        ));
		}	
	}
        
        private function check_authorizations($categories)
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
                
                $unauthorized_cats = array();
                foreach ($categories as $category)
                {
                        if (!(ArticlesAuthorizationsService::check_authorizations($category->get_id())->read()))
                        {
                                $unauthorized_cats[] = $category->get_id();
                        }
                }
                return $unauthorized_cats;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->view = new FileTemplate('articles/ArticlesHomeController.tpl');
		$this->view->add_lang($this->lang);
	}
}
?>