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
		$this->check_authorizations();
		
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
		$news_config = NewsConfig::load();
		
		$condition = 'WHERE id_category IN :authorized_categories
		' . (!NewsAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND (approbation_type = 0 OR (approbation_type = 2 AND (start_date > :timestamp_now OR (end_date != 0 AND end_date < :timestamp_now))))';
		$parameters = array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);
		
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);
		
		$result = PersistenceContext::get_querier()->select('SELECT news.*, member.*
		FROM '. NewsSetup::$news_table .' news
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = news.author_user_id
		' . $condition . '
		ORDER BY top_list_enabled DESC, news.creation_date DESC
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));
		
		$number_columns_display_news = $news_config->get_number_columns_display_news();
		$this->tpl->put_all(array(
			'C_DISPLAY_BLOCK_TYPE' => $news_config->get_display_type() == NewsConfig::DISPLAY_BLOCK,
			'C_DISPLAY_LIST_TYPE' => $news_config->get_display_type() == NewsConfig::DISPLAY_LIST,
			'C_DISPLAY_CONDENSED_CONTENT' => $news_config->get_display_condensed_enabled(),
			'C_COMMENTS_ENABLED' => $news_config->get_comments_enabled(),
			
			'C_NEWS_NO_AVAILABLE' => $result->get_rows_count() == 0,
			'C_PENDING_NEWS' => true,
			'C_PAGINATION' => $pagination->has_several_pages(),
		
			'PAGINATION' => $pagination->display(),
			'C_SEVERAL_COLUMNS' => $number_columns_display_news > 1,
			'NUMBER_COLUMNS' => $number_columns_display_news
		));
		
		while ($row = $result->fetch())
		{
			$news = new News();
			$news->set_properties($row);
			
			$this->tpl->assign_block_vars('news', $news->get_array_tpl_vars());
			$this->build_sources_view($news);
		}
		$result->dispose();
	}
	
	private function build_sources_view(News $news)
	{
		$sources = $news->get_sources();
		$nbr_sources = count($sources);
		if ($nbr_sources)
		{
			$this->tpl->put('news.C_SOURCES', $nbr_sources > 0);
			
			$i = 1;
			foreach ($sources as $name => $url)
			{	
				$this->tpl->assign_block_vars('news.sources', array(
					'C_SEPARATOR' => $i < $nbr_sources,
					'NAME' => $name,
					'URL' => $url,
				));
				$i++;
			}
		}
	}
	
	private function get_pagination($condition, $parameters, $page)
	{
		$number_news = PersistenceContext::get_querier()->count(NewsSetup::$news_table, $condition, $parameters);
		
		$pagination = new ModulePagination($page, $number_news, (int)NewsConfig::load()->get_number_news_per_page());
		$pagination->set_url(NewsUrlBuilder::display_pending_news('%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function check_authorizations()
	{
		if (!(NewsAuthorizationsService::check_authorizations()->write() || NewsAuthorizationsService::check_authorizations()->contribution() || NewsAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['news.pending'], $this->lang['news']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['news.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(NewsUrlBuilder::display_pending_news(AppContext::get_request()->get_getint('page', 1)));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['news'], NewsUrlBuilder::home());
		$breadcrumb->add($this->lang['news.pending'], NewsUrlBuilder::display_pending_news());
		
		return $response;
	}
}
?>