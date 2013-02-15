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
	
	private $news;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
					
		return $this->generate_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'news');
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
			
			$this->news = new News();
		}
		return $this->news;
	}
	
	private function check_authorizations()
	{
		$news = $this->get_news();
		
		if (!NewsAuthorizationsService::check_authorizations()->read() && !NewsAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
   			DispatchManager::redirect($error_controller);
		}
			
		if (
			($news->get_approbation_type() == News::NOT_APPROVAL) && 
			!NewsAuthorizationsService::check_authorizations($news->get_id_cat())->moderation() && 
			!(NewsAuthorizationsService::check_authorizations($news->get_id_cat())->write() && $news->get_user_id() == AppContext::get_current_user()->get_id())
		)
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
   			DispatchManager::redirect($error_controller);
		}
		
		$now = new Date();
		if (
			($news->get_approbation_type() == News::APPROVAL_DATE) && 
			!(
			$news->get_start_date()->is_anterior_to($now)
			&& 
			$news->get_end_date()->is_posterior_to($now)
			)
		)
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
   			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response(View $tpl)
	{
		$response = new NewsDisplayResponse();
		$response->set_page_title($this->get_news()->get_title());
		return $response->display($tpl);
	}
}
?>