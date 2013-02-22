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
			$rewrited_name = AppContext::get_request()->get_getstring('rewrited_name', '');
			if (!empty($rewrited_name))
			{
				try {
					$this->news = NewsService::get_news('WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
		}
		return $this->news;
	}
	
	private function build_view()
	{
		
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
				if ($news->get_start_date()->is_posterior_to($now) && $news->get_end_date()->is_anterior_to($now) && $user_not_read_authorizations_not_approval)
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
		
		$categories = NewsService::get_categories_manager()->get_parents($this->get_news()->get_id_cat(), true);
		foreach ($categories as $id => $category)
		{
			if ($id != Category::ROOT_CATEGORY)
				$response->add_breadcrumb_link($category->get_name(), NewsUrlBuilder::display_category($category->get_rewrited_name()));
		}
		$category = $categories[$this->get_news()->get_id()];
		$response->add_breadcrumb_link($this->get_news()->get_name(), NewsUrlBuilder::display_news($category->get_rewrited_name(), $this->get_news()->get_rewrited_name()));
		
		return $response->display($this->tpl);
	}
}
?>