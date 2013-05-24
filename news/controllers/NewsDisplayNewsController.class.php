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
		$main_lang = LangLoader::get('main');
		
		$this->tpl->put_all(array(
			'C_EDIT' => NewsAuthorizationsService::check_authorizations($news->get_id_cat())->moderation() || (NewsAuthorizationsService::check_authorizations($news->get_id_cat())->write() && $news->get_user_id() == AppContext::get_current_user()->get_id()),
			'C_DELETE' => NewsAuthorizationsService::check_authorizations($news->get_id_cat())->moderation(),
			'C_PICTURE' => $news->has_picture(),
		
			'L_SYNDICATION' => $main_lang['syndication'],
			'L_COMMENTS' => CommentsService::get_number_and_lang_comments('news', $news->get_id()),
			'L_EDIT' => $main_lang['edit'],
			'L_DELETE' => $main_lang['delete'],
		
			'ID' => $news->get_id(),
			'NAME' => $news->get_name(),
			'CONTENTS' => FormatingHelper::second_parse($news->get_contents()),
		
			'PSEUDO' => $news->get_author_user()->get_pseudo(),
			'DATE' => $news->get_creation_date()->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO),
		
				
			'U_SYNDICATION' => NewsUrlBuilder::category_syndication($news->get_id_cat())->rel(),
			'U_COMMENTS' => NewsUrlBuilder::display_comments_news($category->get_id(), $category->get_rewrited_name(), $news->get_id(), $news->get_rewrited_name())->rel(),
			'U_EDIT' => NewsUrlBuilder::edit_news($news->get_id())->rel(),
			'U_DELETE' => NewsUrlBuilder::delete_news($news->get_id())->rel(),
			'U_PICTURE' => $news->get_picture()->absolute(),
		));
		
		$this->build_sources_view($news);
	}
	
	private function build_sources_view(News $news)
	{
		$sources = $news->get_sources();
		$this->tpl->put('C_SOURCES', !empty($sources));
		
		$i = 0;
		foreach ($sources as $name => $url)
		{	
			$this->tpl->assign_block_vars('sources', array(
				'I' => $i,
				'NAME' => $name,
				'COMMA' => $i > 0 ? ', ' : ' ',
				'URL' => $url,
			));
			$i++;
		}
	}
	
	private function check_authorizations()
	{
		$news = $this->get_news();
		
		$user_not_read_authorizations = !NewsAuthorizationsService::check_authorizations($news->get_id_cat())->read() && !NewsAuthorizationsService::check_authorizations()->read();
		$user_not_read_authorizations_not_approval = $user_not_read_authorizations && !NewsAuthorizationsService::check_authorizations($news->get_id_cat())->moderation() && (!NewsAuthorizationsService::check_authorizations($news->get_id_cat())->write() && $news->get_author_user_id() != AppContext::get_current_user()->get_id());
		
		switch ($news->get_approbation_type()) {
			case News::APPROVAL_NOW:
				if ($user_not_read_authorizations)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case News::NOT_APPROVAL:
				if ($user_not_read_authorizations_not_approval)
				{
					$error_controller = PHPBoostErrors::user_not_authorized();
		   			DispatchManager::redirect($error_controller);
				}
			break;
			case News::APPROVAL_DATE:
				$now = new Date();
				if (!$news->is_visible() && $user_not_read_authorizations_not_approval)
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
		$response = new NewsDisplayResponse();
		$response->set_page_title($this->get_news()->get_name());
		$response->add_breadcrumb_link($this->lang['news'], NewsUrlBuilder::home());
		
		$categories = array_reverse(NewsService::get_categories_manager()->get_parents($this->get_news()->get_id_cat(), true));
		foreach ($categories as $id => $category)
		{
			if ($id != Category::ROOT_CATEGORY)
				$response->add_breadcrumb_link($category->get_name(), NewsUrlBuilder::display_category($id, $category->get_rewrited_name()));
		}
		$category = $categories[$this->get_news()->get_id_cat()];
		$response->add_breadcrumb_link($this->get_news()->get_name(), NewsUrlBuilder::display_news($category->get_id(), $category->get_rewrited_name(), $this->get_news()->get_id(), $this->get_news()->get_rewrited_name()));
		
		return $response->display($this->tpl);
	}
}
?>