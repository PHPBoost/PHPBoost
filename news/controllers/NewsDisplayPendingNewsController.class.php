<?php
/*##################################################
 *		                         NewsDisplayPendingNewsController.class.php
 *                            -------------------
 *   begin                : February 13, 2013
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class NewsDisplayPendingNewsController extends ModuleController
{
	private $tpl;
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	public function init()
	{
		$this->lang = LangLoader::get('common', 'news');
		$this->tpl = new FileTemplate('news/NewsDisplaySeveralNewsController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	public function build_view()
	{
		$now = new Date();
		$authorized_categories = NewsService::get_authorized_categories(Category::ROOT_CATEGORY);
		$number_columns_display_news = NewsConfig::load()->get_number_columns_display_news();
		$pagination = $this->get_pagination($now, $authorized_categories);
		
		$result = PersistenceContext::get_querier()->select('SELECT news.*, member.*
		FROM '. NewsSetup::$news_table .' news
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = news.author_user_id
		WHERE news.approbation_type = 0 OR (news.approbation_type = 2 AND (news.start_date > :timestamp_now OR end_date < :timestamp_now)) AND news.id_category IN :authorized_categories
		ORDER BY top_list_enabled DESC, news.creation_date DESC
		LIMIT :number_items_per_page OFFSET :display_from', array(
			'timestamp_now' => $now->get_timestamp(),
			'authorized_categories' => $authorized_categories,
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		));

		$i = 0;
		while ($row = $result->fetch())
		{
			if ($number_columns_display_news > 1)
			{
				$new_row = (($i % $number_columns_display_news) == 0 && $i > 0);
				$i++;
			}
			else
				$new_row = false;
				
			$news = new News();
			$news->set_properties($row);
						
			$this->tpl->assign_block_vars('news', array_merge($news->get_array_tpl_vars(), array(
				'C_NEWS_ROW' => $new_row,
				'L_COMMENTS' => CommentsService::get_number_and_lang_comments('news', $row['id']),
				'NUMBER_COM' => !empty($row['number_comments']) ? $row['number_comments'] : 0,
			)));
		}
		
		$this->tpl->put_all(array(
			'C_NEWS_NO_AVAILABLE' => $result->get_rows_count() == 0,
			'C_ADD' => NewsAuthorizationsService::check_authorizations()->write() || NewsAuthorizationsService::check_authorizations()->contribution(),
			'C_PENDING_NEWS' => NewsAuthorizationsService::check_authorizations()->write() || NewsAuthorizationsService::check_authorizations()->moderation(),
		
			'PAGINATION' => $pagination->display(),
		
			'L_NEWS_NO_AVAILABLE_TITLE' => $this->lang['news.pending'],
		));
		
		if ($number_columns_display_news > 1)
		{
			$column_width = floor(100 / $number_columns_display_news);
			$this->tpl->put_all(array(
				'C_NEWS_BLOCK_COLUMN' => true,
				'COLUMN_WIDTH' => $column_width
			));
		}
	}
	
	private function get_pagination(Date $now, $authorized_categories)
	{
		$number_news = PersistenceContext::get_querier()->count(
			NewsSetup::$news_table, 
			'WHERE approbation_type = 0 OR (approbation_type = 2 AND (start_date > :timestamp_now OR end_date < :timestamp_now)) AND id_category IN :authorized_categories', 
			array(
				'timestamp_now' => $now->get_timestamp(),
				'authorized_categories' => $authorized_categories
		));
		
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = new ModulePagination($page, $number_news, (int)NewsConfig::load()->get_number_news_per_page());
		$pagination->set_url(NewsUrlBuilder::display_pending_news('%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
        {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
        }
        
		return $pagination;
	}
		
	private function generate_response()
	{
		$response = new NewsDisplayResponse();
		$response->set_page_title($this->lang['news.pending']);
		$response->set_page_description($this->lang['news.seo.description.pending']);
		
		$response->add_breadcrumb_link($this->lang['news'], NewsUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['news.pending'], NewsUrlBuilder::display_pending_news());
	
		return $response->display($this->tpl);
	}
}
?>