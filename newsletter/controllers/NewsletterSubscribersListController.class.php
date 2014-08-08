<?php
/*##################################################
 *                      NewsletterSubscribersListController.class.php
 *                            -------------------
 *   begin                : March 11, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class NewsletterSubscribersListController extends ModuleController
{
	private $lang;
	private $view;
	private $user;
	private $stream;
	
	private $nbr_subscribers_per_page = 25;

	public function execute(HTTPRequestCustom $request)
	{
		$this->stream = NewsletterStreamsCache::load()->get_stream($request->get_int('id_stream', 0));
		
		if ($this->stream->get_id() == 0)
		{
			AppContext::get_response()->redirect(NewsletterUrlBuilder::home());
		}
		
		$this->init();
		$this->build_form($request);

		return $this->build_response($this->view);
	}

	private function build_form($request)
	{
		$field = $request->get_value('field', 'login');
		$sort = $request->get_value('sort', 'top');
		$current_page = $request->get_int('page', 1);
		
		if (!NewsletterAuthorizationsService::id_stream($this->stream->get_id())->read_subscribers())
		{
			NewsletterAuthorizationsService::get_errors()->read_subscribers();
		}
		
		$mode = ($sort == 'top') ? 'ASC' : 'DESC';
		switch ($field)
		{
			case 'pseudo' :
				$field_bdd = 'login';
			break;
			default :
				$field_bdd = 'login';
		}
		
		$nbr_subscribers = count(NewsletterService::list_subscribers_by_stream($this->stream->get_id()));
		
		$pagination = new ModulePagination($current_page, $nbr_subscribers, $this->nbr_subscribers_per_page);
		$pagination->set_url(NewsletterUrlBuilder::subscribers($this->stream->get_id() .'/'. $field .'/'. $sort .'/%d'));

		if ($pagination->current_page_is_empty() && $current_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		$this->view->put_all(array(
			'C_SUBSCRIBERS' => (float)$nbr_subscribers,
			'C_SUBSCRIPTION' => NewsletterUrlBuilder::subscribe()->rel(),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'SORT_PSEUDO_TOP' => NewsletterUrlBuilder::subscribers($this->stream->get_id() .'/pseudo/top/'. $current_page)->rel(),
			'SORT_PSEUDO_BOTTOM' => NewsletterUrlBuilder::subscribers($this->stream->get_id() .'/pseudo/bottom/'. $current_page)->rel(),
			'PAGINATION' => $pagination->display()
		));

		$result = PersistenceContext::get_querier()->select("SELECT subscribers.id, subscribers.user_id, subscribers.mail, member.login, member.user_mail
		FROM " . NewsletterSetup::$newsletter_table_subscribers . " subscribers
		LEFT JOIN " . DB_TABLE_MEMBER . " member ON subscribers.user_id = member.user_id
		ORDER BY ". $field_bdd ." ". $mode ."
		LIMIT :number_items_per_page OFFSET :display_from",
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);
		
		while ($row = $result->fetch())
		{
			if (array_key_exists($row['id'], NewsletterService::list_subscribers_by_stream($this->stream->get_id())))
			{
				$pseudo = $row['user_id'] > 0 ? '<a href="'. UserUrlBuilder::profile($row['user_id'])->rel() .'">'. $row['login'] .'</a>' : $this->lang['newsletter.visitor'];
				$this->view->assign_block_vars('subscribers_list', array(
					'C_AUTH_MODO' => NewsletterAuthorizationsService::id_stream($this->stream->get_id())->moderation_subscribers(),
					'C_EDIT' => $row['user_id'] == User::VISITOR_LEVEL,
					'U_EDIT' => $row['user_id'] == User::VISITOR_LEVEL ? NewsletterUrlBuilder::edit_subscriber($row['id'])->rel() : '',
					'U_DELETE' => NewsletterUrlBuilder::delete_subscriber($row['id'], $this->stream->get_id())->rel(),
					'PSEUDO' => $pseudo,
					'MAIL' => $row['user_id'] > 0 ? $row['email'] : $row['mail']
				));
			}
		}
		$result->dispose();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'newsletter');
		$this->view = new FileTemplate('newsletter/NewsletterSubscribersListController.tpl');
		$this->view->add_lang($this->lang);
		$this->user = AppContext::get_current_user();
	}

	private function build_response(View $view)
	{
		$body_view = new FileTemplate('newsletter/NewsletterBody.tpl');
		$body_view->add_lang($this->lang);
		$body_view->put('TEMPLATE', $view);
		$response = new SiteDisplayResponse($body_view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], NewsletterUrlBuilder::home()->rel());
		$name_page = $this->lang['newsletter.subscribers'] . ' : ' . $this->stream->get_name();
		$breadcrumb->add($name_page, NewsletterUrlBuilder::subscribers($this->stream->get_id())->rel());
		$response->get_graphical_environment()->set_page_title($name_page);
		return $response;
	}
}
?>