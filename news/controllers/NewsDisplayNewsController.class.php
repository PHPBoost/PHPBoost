<?php
/*##################################################
 *		               NewsDisplayNewsController.class.php
 *                            -------------------
 *   begin                : February 15, 2013
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
class NewsDisplayNewsController extends ModuleController
{	
	private $lang;
	private $tpl;
	
	private $news;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view();
					
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'news');
		$this->tpl = new FileTemplate('news/NewsDisplayNewsController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	private function get_news()
	{
		if ($this->news === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->news = NewsService::get_news('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
				$this->news = new News();
		}
		return $this->news;
	}
	
	private function build_view()
	{
		$news = $this->get_news();
		$category = NewsService::get_categories_manager()->get_categories_cache()->get_category($news->get_id_cat());
		
		$this->tpl->put_all($news->get_array_tpl_vars());
		
		if (NewsConfig::load()->get_comments_enabled())
		{
			$comments_topic = new NewsCommentsTopic($news);
			$comments_topic->set_id_in_module($news->get_id());
			$comments_topic->set_url(NewsUrlBuilder::display_news($category->get_id(), $category->get_rewrited_name(), $news->get_id(), $news->get_rewrited_name()));
			
			$this->tpl->put_all(array(
				'COMMENTS' => $comments_topic->display(),
			));
		}
		$this->build_sources_view($news);
		$this->build_keywords_view($news);
		$this->build_suggested_news($news);
		$this->build_navigation_links($news);
	}
	
	private function build_sources_view(News $news)
	{
		$sources = $news->get_sources();
		$nbr_sources = count($sources);
		$this->tpl->put('C_SOURCES', $nbr_sources > 0);
		
		$i = 1;
		foreach ($sources as $name => $url)
		{	
			$this->tpl->assign_block_vars('sources', array(
				'C_SEPARATOR' => $i < $nbr_sources,
				'NAME' => $name,
				'URL' => $url,
			));
			$i++;
		}
	}
	
	private function build_keywords_view(News $news)
	{
		$keywords = $news->get_keywords();
		$nbr_keywords = count($keywords);
		$this->tpl->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{	
			$this->tpl->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => NewsUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}
	
	
	private function build_suggested_news(News $news)
	{
		$result = PersistenceContext::get_querier()->select('
		SELECT id, name, id_category, rewrited_name, 
		(2 * FT_SEARCH_RELEVANCE(name, :search_content) + FT_SEARCH_RELEVANCE(contents, :search_content) / 3) AS relevance
		FROM '. NewsSetup::$news_table .'
		WHERE (FT_SEARCH(name, :search_content) OR	FT_SEARCH(contents, :search_content)) AND id <> :excluded_id
		ORDER BY relevance DESC LIMIT 0, 10', array(
			'excluded_id' => $news->get_id(),
			'search_content' => $news->get_name() .','. $news->get_contents(),
		));
		
		$this->tpl->put('C_SUGGESTED_NEWS', ($result->get_rows_count() > 0 && NewsConfig::load()->get_news_suggestions_enabled()));
		
		while ($row = $result->fetch())
		{
			$this->tpl->assign_block_vars('suggested', array(
				'NAME' => $row['name'],
				'URL' => NewsUrlBuilder::display_news($row['id_category'], NewsService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_name'])->rel()
			));
		}
	}
	
	private function build_navigation_links(News $news)
	{
		$now = new Date();
		$timestamp_news = $news->get_creation_date()->get_timestamp();

		$result = PersistenceContext::get_querier()->select('
		(SELECT id, name, id_category, rewrited_name, \'PREVIOUS\' as type
		FROM '. NewsSetup::$news_table .'
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND creation_date < :timestamp_news AND id_category IN :authorized_categories ORDER BY creation_date DESC LIMIT 1 OFFSET 0)
		UNION
		(SELECT id, name, id_category, rewrited_name, \'NEXT\' as type
		FROM '. NewsSetup::$news_table .'
		WHERE (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0))) AND creation_date > :timestamp_news AND id_category IN :authorized_categories ORDER BY creation_date ASC LIMIT 1 OFFSET 0)
		', array(
			'timestamp_now' => $now->get_timestamp(),
			'timestamp_news' => $timestamp_news,
			'authorized_categories' => array($news->get_id_cat())
		));
		
		while ($row = $result->fetch())
		{
			$this->tpl->put_all(array(
				'C_NEWS_NAVIGATION_LINKS' => true,
				'C_'. $row['type'] .'_NEWS' => true,
				$row['type'] . '_NEWS' => $row['name'],
				'U_'. $row['type'] .'_NEWS' => NewsUrlBuilder::display_news($row['id_category'], NewsService::get_categories_manager()->get_categories_cache()->get_category($row['id_category'])->get_rewrited_name(), $row['id'], $row['rewrited_name'])->rel(),
			));
		}
	}
	
	private function check_authorizations()
	{
		$news = $this->get_news();
		
		$not_authorized = !NewsAuthorizationsService::check_authorizations($news->get_id_cat())->moderation() && (!NewsAuthorizationsService::check_authorizations($news->get_id_cat())->write() && $news->get_author_user()->get_id() != AppContext::get_current_user()->get_id());
		
		switch ($news->get_approbation_type()) {
			case News::APPROVAL_NOW:
				if (!NewsAuthorizationsService::check_authorizations($news->get_id_cat())->read() && $not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case News::NOT_APPROVAL:
				if ($not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case News::APPROVAL_DATE:
				if (!$news->is_visible() && $not_authorized)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			default:
				$error_controller = PHPBoostErrors::unexisting_page();
   				DispatchManager::redirect($error_controller);
			break;
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_news()->get_name());
		$graphical_environment->get_seo_meta_data()->set_description($this->get_news()->get_real_short_contents());
		$graphical_environment->get_seo_meta_data()->set_keywords($this->get_news()->get_keywords_name());
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['news'], NewsUrlBuilder::home());
		
		$categories = array_reverse(NewsService::get_categories_manager()->get_parents($this->get_news()->get_id_cat(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), NewsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
		}
		$breadcrumb->add($this->get_news()->get_name(), NewsUrlBuilder::display_news($category->get_id(), $category->get_rewrited_name(), $this->get_news()->get_id(), $this->get_news()->get_rewrited_name()));
		
		return $response;
	}
}
?>