<?php
/*##################################################
 *		    ArticlesDisplayCategoryController.class.php
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

class ArticlesDisplayCategoryController extends ModuleController
{	
	private $lang;
	private $tpl;
	private $view;
	private $category;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view($request);
					
		return $this->generate_response($this->tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->tpl = new FileTemplate('articles/ArticlesDisplayCategoryController.tpl');
                $this->tpl->add_lang($this->lang);
	}
        
        private function build_form($mode)
        {
                $form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHorizontal('filters'); // @todo : voir si je passe une classe en option pour styliser
		$form->add_fieldset($fieldset);
                
                $sort_fields = $this->list_sort_fields();
              
                $fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $sort_fields[0], $sort_fields,
			array('events' => array('change' => 'document.location = "'. ArticlesUrlBuilder::display_category($this->category-get_id(), $this->category->get_rewrited_name())->absolute() .'" + HTMLForms.getField("sort_fields").getValue(); /' . $mode)
		)));
                
                $fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', 'DESC',
			array(
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.asc'], 'ASC'),
				new FormFieldSelectChoiceOption($this->lang['articles.sort_mode.desc'], 'DESC')
			), 
			array('events' => array('change' => 'document.location = "' . ArticlesUrlBuilder::display_category($this->category-get_id(), $this->category->get_rewrited_name())->absolute() . '" + HTMLForms.getField("sort_fields").getValue() "/" + HTMLForms.getField("sort_mode").getValue();'))
		));
                
                $this->form = $form;
        }
	private function build_view($request)
	{
		$mode = ($request->get_getstring('mode','') == 'asc') ? 'ASC' : 'DESC';
                $sort = $request->get_getstring('sort', '');
                
                $number_articles_per_page = ArticlesConfig::load()->get_number_articles_per_page();
                $current_page = ($request->get_getint('page',1) > 0) ? $request->get_getint('page',1) : 1;
                $limit_page = (($current_page - 1) * $number_articles_per_page);
                 
                $now = new Date(DATE_NOW, TIMEZONE_AUTO);
                
		$result = PersistenceContext::get_querier()->select('SELECT articles.*, member.level, member.user_groups, member.login
		FROM '. ArticlesSetup::$articles_table .' articles
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = articles.author_user_id
                LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . 'com ON com.id_in_module = a.id AND com.module_id = "articles"
                LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . 'note ON note.id_in_module = a.id AND note.module_name = "articles"
		WHERE articles.id_category = :id_category AND (articles.published = 1 OR (articles.published = 2 AND (articles.publishing_start_date < :timestamp_now 
                AND articles.publishing_end_date > :timestamp_now) OR articles.publishing_end_date = 0)) 
                ORDER BY :sort :mode LIMIT ' . $number_articles_per_page . 'OFFSET :start_limit', 
                        array(
                              'id_category' => $this->category->get_id(),
                              'timestamp_now' => $now->get_timestamp(),
                              'start_limit' => $limit_page,
                              'sort' => $sort,
                              'mode' => $mode
                             ), SelectQueryResult::FETCH_ASSOC
                );
                
                $number_articles_in_category = $result->get_rows_count();
                    
                $number_pages = ceil($number_articles_in_category / $number_articles_per_page);
                $pagination = new Pagination($number_pages,$current_page);
                $pagination->set_url_sprintf_pattern(ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name()));
                
                $number_articles_not_published = PersistenceContext::get_querier()->count(ArticlesSetup::$articles_table, 'WHERE id_category=:id_category AND published=0', array(
                                                                                                  'id_category' => $this->category->get_id()
                                                                                                  )
                );
                
                $this->build_form($mode);
                
                $moderation_auth = ArticlesAuthorizationsService::check_authorizations($this->category->get_id())->moderation();
                
                $this->view->put_all(array(
                            'C_IS_MODERATOR' => $moderation_auth,
                            'C_PENDING_ARTICLES' => $number_articles_not_published > 0 && $moderation_auth,
                            'L_CAT' => $this->category->get_name(),
                            'L_TOTAL_ARTICLES' => $number_articles_in_category > 0 ? spintf($this->lang['articles.total_articles_category'], $number_articles_in_category) : '',
                            'U_PENDING_ARTICLES_LINK' => '', // @todo : link
                            'U_ADD_ARTICLES' => ArticlesUrlBuilder::add_article()->absolute()
                ));
                
                if($number_articles_in_category > 0)
                {
                        $add_auth = ArticlesAuthorizationsService::check_authorizations($this->category->get_id())->write() || ArticlesAuthorizationsService::check_authorizations($this->category->get_id())->contribution();
                        $edit_auth = ArticlesAuthorizationsService::check_authorizations($this->category->get_id())->write() || ArticlesAuthorizationsService::check_authorizations($this->category->get_id())->moderation();
                           
                        $this->view->put_all(array(
                                    'C_ADD' => $add_auth,
                                    'C_EDIT' => $edit_auth,
                                    'C_ARTICLES_FILTERS' => true,
                                    'PAGINATION' => $pagination->export()->render()
                        ));

                        $notation = new Notation();
                        $notation->set_module_name('articles');
                        $notation->set_notation_scale(ArticlesConfig::load()->get_notation_scale());

                        while($row = $result->fetch())
                        {                                
                                $notation->set_id_in_module($row['id']);
                                
                                $group_color = User::get_group_color($row['user_group'], $row['level']);

                                $this->view->assign_block_vars('articles', array(
                                    'C_GROUP_COLOR' => !empty($group_color),
                                    'TITLE' => $row['title'],
                                    'PICTURE' => $row['picture_url'],// @todo : link
                                    'DATE' => gmdate_format('date_format_short', $row['date_created']),
                                    'NUMBER_VIEW' => $row['number_view'],
                                    'L_NUMBER_COM' => empty($row['number_comments']) ? '0' : $row['number_comments'],
                                    'NOTE' => $row['number_notes'] > 0 ? NotationService::display_static_image($notation, $row['average_notes']) : $this->lang['articles.no_notes'],
                                    'DESCRIPTION' =>FormatingHelper::second_parse($row['description']),                                    
                                    'U_ARTICLES_LINK_COM' => ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->absolute() . $row['id'] . '-' . $row['rewrited_title'] . '/comments/',
                                    'U_AUTHOR' => '<a href="' . UserUrlBuilder::profile($row['user_id'])->absolute() . '" class="' . UserService::get_level_class($row['level']) . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . TextHelper::wordwrap_html($row['login'], 19) . '</a>',
                                    'U_ARTICLES_LINK' => ArticlesUrlBuilder::display_article($this->category->get_rewrited_name(), $row['id'], $row['rewrited_title'])->absolute(),
                                    'U_ARTICLES_EDIT' => ArticlesUrlBuilder::edit_article($row['id'])->absolute(),
                                    'U_ARTICLES_DELETE' => ArticlesUrlBuilder::delete_article($row['id'])->absolute()
                                ));
                        }
                }
                else 
                {
                    $this->view->put_all(array(
                            'L_NO_ARTICLES' => $this->lang['articles.no_article']
                    ));
                }
                $this->view->put('FORM', $this->form->display());
	}
	
	private function get_category()
	{
		if ($this->category === null)
		{
			$rewrited_name = AppContext::get_request()->get_getstring('rewrited_name', '');
			if (!empty($rewrited_name))
			{
				try {
					$row = PersistenceContext::get_querier()->select_single_row(ArticlesSetup::$articles_cats_table, array('*'), 'WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));

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
	
	private function check_authorizations()
	{
		$category = $this->get_category();
                
                if (!(ArticlesAuthorizationsService::check_authorizations($category->get_id())->read()))
                {
                        $error_controller = PHPBoostErrors::user_not_authorized();
                        DispatchManager::redirect($error_controller);
                }
	}
	
        private function list_sort_fields()
        {
                $options = array();

                $option[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.date'], 'date_created');
                $option[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.title'], 'title');
                $option[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.views'], 'number_view');
                $option[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.com'], 'com');
                $option[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.note'], 'note');
                $option[] = new FormFieldSelectChoiceOption($this->lang['articles.sort_field.author'], 'author_user_id');
            
                return $options;
        }
	private function generate_response(View $view)
	{
		$response = new ArticlesDisplayResponse();
		$response->set_page_title($this->category->get_name());
		
		$response->add_breadcrumb_link($this->lang['articles'], ArticlesUrlBuilder::home());
		
		$categories = array_reverse(ArticlesService::get_categories_manager()->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($id != Category::ROOT_CATEGORY)
				$response->add_breadcrumb_link($category->get_name(), ArticlesUrlBuilder::display_category($id, $category->get_rewrited_name()));
		}
	
		return $response->display($view);
	}
}
?>