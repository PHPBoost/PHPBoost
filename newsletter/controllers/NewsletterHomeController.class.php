<?php
/*##################################################
 *                      NewsletterHomeController.class.php
 *                            -------------------
 *   begin                : March 13, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class NewsletterHomeController extends ModuleController
{
	private $lang;
	private $view;
	private $nbr_streams_per_page = 25;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_form($request);

		return $this->build_response($this->view);
	}

	private function build_form($request)
	{
		$pagination = $this->get_pagination();
		
		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM ' . NewsletterSetup::$newsletter_table_streams . '
		LIMIT :number_items_per_page OFFSET :display_from',
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
		));
		
		while ($row = $result->fetch())
		{
			if (NewsletterAuthorizationsService::id_stream($row['id'])->read())
			{
				$this->view->assign_block_vars('streams_list', array(
					'C_VIEW_ARCHIVES' => NewsletterAuthorizationsService::id_stream($row['id'])->read_archives(),
					'C_VIEW_SUBSCRIBERS' => NewsletterAuthorizationsService::id_stream($row['id'])->read_subscribers(),
					'IMAGE' => Url::to_rel($row['image']),
					'NAME' => $row['name'],
					'DESCRIPTION' => $row['description'],
					'U_VIEW_ARCHIVES' => NewsletterUrlBuilder::archives($row['id'])->absolute(),
					'U_VIEW_SUBSCRIBERS' => NewsletterUrlBuilder::subscribers($row['id'])->absolute(),
				));
			}
		}
		
		$this->view->put_all(array(
			'C_STREAMS' => $result->get_rows_count() != 0,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display()
		));
		
		$result->dispose();
	}
	
	private function check_authorizations()
	{
		if (!NewsletterAuthorizationsService::default_authorizations()->read())
		{
			NewsletterAuthorizationsService::get_errors()->read();
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'newsletter');
		$this->view = new FileTemplate('newsletter/NewsletterHomeController.tpl');
		$this->view->add_lang($this->lang);
	}
		
	private function get_pagination()
	{
		$nbr_streams = PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_streams);
		
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = new ModulePagination($page, $nbr_streams, $this->nbr_streams_per_page);
		$pagination->set_url(NewsletterUrlBuilder::home('%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}

	private function build_response(View $view)
	{
		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put('TEMPLATE', $view);
		$response = new SiteDisplayResponse($body_view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], NewsletterUrlBuilder::home()->absolute());
		$breadcrumb->add($this->lang['newsletter.list_newsletters'], NewsletterUrlBuilder::home()->absolute());
		$response->get_graphical_environment()->set_page_title($this->lang['newsletter.list_newsletters']);
		return $response;
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->check_authorizations();
		$object->init();
		$object->build_form(AppContext::get_request());
		return $object->view;
	}
}
?>