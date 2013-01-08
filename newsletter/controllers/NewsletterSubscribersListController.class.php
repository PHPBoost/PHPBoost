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
	private $id_stream;
	private $stream_cache;
	
	private $nbr_subscribers_per_page = 25;

	public function execute(HTTPRequestCustom $request)
	{
		$this->id_stream = $request->get_int('id_stream', 0);
		$this->stream_cache = NewsletterStreamsCache::load()->get_stream($this->id_stream);
		
		if ($this->id_stream == 0 || empty($this->stream_cache))
		{
			AppContext::get_response()->redirect(NewsletterUrlBuilder::home()->absolute());
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
		
		if (!NewsletterAuthorizationsService::id_stream($this->id_stream)->read_subscribers())
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
		
		$nbr_subscribers = count($this->stream_cache['subscribers']);
		$nbr_pages =  ceil($nbr_subscribers / $this->nbr_subscribers_per_page);
		$pagination = new Pagination($nbr_pages, $current_page);
		
		$pagination->set_url_sprintf_pattern(NewsletterUrlBuilder::subscribers($this->id_stream .'/'. $field .'/'. $sort .'/%d')->absolute());
		$this->view->put_all(array(
			'C_SUBSCRIBERS' => (float)$nbr_subscribers,
			'C_SUBSCRIPTION' => NewsletterUrlBuilder::subscribe()->absolute(),
			'SORT_PSEUDO_TOP' => NewsletterUrlBuilder::subscribers($this->id_stream .'/pseudo/top/'. $current_page)->absolute(),
			'SORT_PSEUDO_BOTTOM' => NewsletterUrlBuilder::subscribers($this->id_stream .'/pseudo/bottom/'. $current_page)->absolute(),
			'PAGINATION' => $pagination->export()->render()
		));

		$limit_page = $current_page > 0 ? $current_page : 1;
		$limit_page = (($limit_page - 1) * $this->nbr_subscribers_per_page);
		

		$result = PersistenceContext::get_querier()->select("SELECT subscribers.id, subscribers.user_id, subscribers.mail, member.login, member.user_mail
		FROM " . NewsletterSetup::$newsletter_table_subscribers . " subscribers
		LEFT JOIN " . DB_TABLE_MEMBER . " member ON subscribers.user_id = member.user_id
		ORDER BY ". $field_bdd ." ". $mode ."
		LIMIT ". $this->nbr_subscribers_per_page ." OFFSET :start_limit",
			array(
				'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		while ($row = $result->fetch())
		{
			if (array_key_exists($row['id'], $this->stream_cache['subscribers']))
			{
				$moderation_auth = NewsletterAuthorizationsService::id_stream($this->id_stream)->moderation_subscribers();
				$pseudo = $row['user_id'] > 0 ? '<a href="'. UserUrlBuilder::profile($row['user_id'])->absolute() .'">'. $row['login'] .'</a>' : $this->lang['newsletter.visitor'];
				$this->view->assign_block_vars('subscribers_list', array(
					'C_AUTH_MODO' => $moderation_auth,
					'C_EDIT_LINK' => $row['user_id'] == User::VISITOR_LEVEL,
					'EDIT_LINK' => $row['user_id'] == User::VISITOR_LEVEL ? NewsletterUrlBuilder::edit_subscriber($row['id'])->absolute() : '',
					'DELETE_LINK' => NewsletterUrlBuilder::delete_subscriber($row['id'], $this->stream_cache['id'])->absolute(),
					'PSEUDO' => $pseudo,
					'MAIL' => $row['user_id'] > 0 ? $row['user_mail'] : $row['mail']
				));
			}
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
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
		$breadcrumb->add($this->lang['newsletter'], NewsletterUrlBuilder::home()->absolute());
		$name_page = $this->lang['newsletter.subscribers'] . ' : ' . $this->stream_cache['name'];
		$breadcrumb->add($name_page, NewsletterUrlBuilder::subscribers($this->id_stream)->absolute());
		$response->get_graphical_environment()->set_page_title($name_page);
		return $response;
	}
}
?>