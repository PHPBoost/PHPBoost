<?php
/*##################################################
 *                      AdminNewsManageController.class.php
 *                            -------------------
 *   begin                : June 24, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class AdminNewsManageController extends AdminModuleController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view($request);
		
		return new AdminNewsDisplayResponse($this->view, $this->lang['news.management']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'news');
		$this->view = new FileTemplate('news/AdminNewsManageController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		$categories = NewsService::get_categories_manager()->get_categories_cache()->get_categories();
		
		$mode = $request->get_getvalue('sort', 'desc');
		$field = $request->get_getvalue('field', 'date');
		
		$sort_mode = ($mode == 'asc') ? 'ASC' : 'DESC';
		
		switch ($field)
		{
			case 'category':
				$sort_field = 'id_category';
				break;
			case 'author':
				$sort_field = 'login';
				break;
			case 'name':
				$sort_field = 'name';
				break;
			default:
				$sort_field = 'creation_date';
				break;
		}
		
		$pagination = $this->get_pagination($field, $mode);
		
		$this->view->put_all(array(
			'C_NEWS_EXISTS' => !$pagination->current_page_is_empty(),
			'PAGINATION' => $pagination->display()
		));
		
		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM '. NewsSetup::$news_table .' news
		LEFT JOIN '. NewsSetup::$news_cats_table .' cat ON cat.id = news.id_category
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = news.author_user_id
		ORDER BY ' . $sort_field . ' ' . $sort_mode . '
		LIMIT :number_per_page OFFSET :start_limit',
			array(
				'number_per_page' => $pagination->get_number_items_per_page(),
				'start_limit' => $pagination->get_display_from()
		));
		
		while($row = $result->fetch())
		{
			$news = new News();
			$news->set_properties($row);
			
			$this->view->assign_block_vars('news', $news->get_array_tpl_vars());
		}
	}
	
	private function get_pagination($sort_field, $sort_mode)
	{
		$news_number = PersistenceContext::get_querier()->count(CalendarSetup::$calendar_events_table);
		
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = new ModulePagination($page, $news_number, (int)NewsConfig::load()->get_number_news_per_page());
		$pagination->set_url(NewsUrlBuilder::manage_news($sort_field, $sort_mode, '%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
}
?>