<?php
/*##################################################
 *		    AdminArticlesManageController.class.php
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
class AdminArticlesManageController extends AdminModuleController
{
	private $lang;
	private $view;
	private $form;
	
	public function execute(HTTPRequestCustom $request)
	{	
		$this->init();
		
		$this->build_view($request);
		
		return new AdminArticlesDisplayResponse($this->view, $this->lang['articles_management']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'articles');
		$this->view = new FileTemplate('articles/AdminArticlesManageController.tpl');
		$this->view->add_lang($this->lang);
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
		LEFT JOIN ' . ArticlesSetup::$articles_cats_table . ' articles_cat ON articles_cat.id = articles.id_category 
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = articles.author_user_id
		ORDER BY ' . $sort_field . ' ' . $sort_mode . ' LIMIT ' . $nbr_articles_per_page .
		' OFFSET ' . $limit_page, array(), SelectQueryResult::FETCH_ASSOC);
		
		$nbr_articles = $result->get_rows_count();
		
		if($nbr_articles > 0)
		{	
			$pagination = $this->get_pagination($nbr_articles, $field, $mode);
			
			$this->view->put_all(array(
				'C_PAGINATION' => $pagination->has_several_pages(),
				'PAGINATION' => $pagination->display(),
				'U_SORT_TITLE_ASC' => ArticlesUrlBuilder::manage_articles('title', 'ASC', $current_page)->rel(),
				'U_SORT_TITLE_DESC' => ArticlesUrlBuilder::manage_articles('title', 'DESC', $current_page)->rel(),
				'U_SORT_CATEGORY_ASC' => ArticlesUrlBuilder::manage_articles('cat', 'ASC', $current_page)->rel(),
				'U_SORT_CATEGORY_DESC' => ArticlesUrlBuilder::manage_articles('cat', 'DESC', $current_page)->rel(),
				'U_SORT_AUTHOR_ASC' => ArticlesUrlBuilder::manage_articles('author', 'ASC', $current_page)->rel(),
				'U_SORT_AUTHOR_DESC' => ArticlesUrlBuilder::manage_articles('author', 'DESC', $current_page)->rel(),
				'U_SORT_DATE_ASC' => ArticlesUrlBuilder::manage_articles('date', 'ASC', $current_page)->rel(),
				'U_SORT_DATE_DESC' => ArticlesUrlBuilder::manage_articles('date', 'DESC', $current_page)->rel(),
				'U_SORT_PUBLISHED_ASC' => ArticlesUrlBuilder::manage_articles('published', 'ASC', $current_page)->rel(),
				'U_SORT_PUBLISHED_DESC' => ArticlesUrlBuilder::manage_articles('published', 'DESC', $current_page)->rel(),
			));
			
			while($row = $result->fetch())
			{
				$user_group_color = User::get_group_color($row['user_groups'], $row['level']);
				
				$category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category']);
				
				$title = strlen($row['title']) > 45 ? TextHelper::substr_html($row['title'], 0, 45) . '...' : $row['title'];
				
				$published_date = '';
				if ($row['publishing_start_date'] > 0)
					$published_date .= gmdate_format('date_format_short', $row['publishing_start_date']);

				if ($row['publishing_end_date'] > 0 && $row['publishing_start_date'] > 0)
					$published_date .= ' ' . strtolower(LangLoader::get_message('until', 'main')) . ' ' . gmdate_format('date_format_short', $row['publishing_end_date']);
				elseif ($row['publishing_end_date'] > 0)
					$published_date .= LangLoader::get_message('until', 'main') . ' ' . gmdate_format('date_format_short', $row['publishing_end_date']);
				
				
				$this->view->assign_block_vars('articles', array(
					'C_USER_GROUP_COLOR' => !empty($user_group_color),
					'L_TITLE' => $title,
					'L_CATEGORY' => $category->get_name(),
					'DATE' => gmdate_format('date_format_short', $row['date_created']), 
					'PUBLISHED' => ($row['published'] == 1) ? LangLoader::get_message('yes', 'main') : LangLoader::get_message('no', 'main'),
					'PUBLISHED_DATE' => $published_date,
					'PSEUDO' => $row['login'],
					'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
					'USER_GROUP_COLOR' => $user_group_color,
					'U_AUTHOR' => UserUrlBuilder::profile($row['author_user_id'])->rel(),
					'U_ARTICLE' => ArticlesUrlBuilder::display_article($category->get_id(), $category->get_rewrited_name(), $row['id'], $row['rewrited_title'])->rel(),
					'U_CATEGORY' => ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
					'U_EDIT_ARTICLE' => ArticlesUrlBuilder::edit_article($row['id'])->rel(),
					'U_DELETE_ARTICLE' => ArticlesUrlBuilder::delete_article($row['id'])->rel()
				));
			}
		}
		else 
		{
			$this->view->put_all(array(
				'C_NO_ARTICLES' => true
			));
		}
		$this->view->put('FORM', $this->form->display());
	}
	
	private function get_pagination($nbr_articles, $field, $mode)
	{
		$current_page = AppContext::get_request()->get_getint('page', 1);
		
		$pagination = new ModulePagination($current_page, $nbr_articles, ArticlesConfig::load()->get_number_articles_per_page());
		$pagination->set_url(ArticlesUrlBuilder::manage_articles($field, $mode, '%d'));
		
		if ($pagination->current_page_is_empty() && $current_page > 1)
	        {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
	        }
	
		return $pagination;
	}
}
?>