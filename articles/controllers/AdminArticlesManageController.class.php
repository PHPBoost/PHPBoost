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
	const NUMBER_ITEMS_PER_PAGE = 20;
	
	private $lang;
	private $view;
	
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
		$mode = $request->get_getvalue('sort', 'desc');
		$field = $request->get_getvalue('field', 'date');
		
		$sort_mode = ($mode == 'asc') ? 'asc' : 'desc';
		
		switch ($field)
		{
			case 'cat':
				$sort_field = 'id_category';
				break;
			case 'author':
				$sort_field = 'login';
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
		
		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($page, $field, $mode);
		
		$result = PersistenceContext::get_querier()->select('SELECT articles.*, member.*, notes.average_notes, notes.number_notes, note.note
		FROM '. ArticlesSetup::$articles_table . ' articles
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = articles.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = articles.id AND notes.module_name = \'articles\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = articles.id AND note.module_name = \'articles\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
		ORDER BY ' . $sort_field . ' ' . $sort_mode . '
		LIMIT :number_per_page OFFSET :start_limit',
			array(
				'number_per_page' => $pagination->get_number_items_per_page(),
				'start_limit' => $pagination->get_display_from()
		));
		
		while($row = $result->fetch())
		{
			$article = new Article();
			$article->set_properties($row);
			
			$this->view->assign_block_vars('articles', $article->get_tpl_vars());
		}
		
		$this->view->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_ARTICLES' => $result->get_rows_count() > 0,
			'PAGINATION' => $pagination->display(),
			'U_SORT_TITLE_ASC' => ArticlesUrlBuilder::manage_articles('title', 'asc', $page)->rel(),
			'U_SORT_TITLE_DESC' => ArticlesUrlBuilder::manage_articles('title', 'desc', $page)->rel(),
			'U_SORT_CATEGORY_ASC' => ArticlesUrlBuilder::manage_articles('cat', 'asc', $page)->rel(),
			'U_SORT_CATEGORY_DESC' => ArticlesUrlBuilder::manage_articles('cat', 'desc', $page)->rel(),
			'U_SORT_AUTHOR_ASC' => ArticlesUrlBuilder::manage_articles('author', 'asc', $page)->rel(),
			'U_SORT_AUTHOR_DESC' => ArticlesUrlBuilder::manage_articles('author', 'desc', $page)->rel(),
			'U_SORT_DATE_ASC' => ArticlesUrlBuilder::manage_articles('date', 'asc', $page)->rel(),
			'U_SORT_DATE_DESC' => ArticlesUrlBuilder::manage_articles('date', 'desc', $page)->rel(),
			'U_SORT_PUBLISHED_ASC' => ArticlesUrlBuilder::manage_articles('published', 'asc', $page)->rel(),
			'U_SORT_PUBLISHED_DESC' => ArticlesUrlBuilder::manage_articles('published', 'desc', $page)->rel()
		));
	}
	
	private function get_pagination($page, $sort_field, $sort_mode)
	{
		$articles_number = PersistenceContext::get_querier()->count(ArticlesSetup::$articles_table);
		
		$pagination = new ModulePagination($page, $articles_number, self::NUMBER_ITEMS_PER_PAGE);
		$pagination->set_url(ArticlesUrlBuilder::manage_articles($sort_field, $sort_mode, '%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
}
?>