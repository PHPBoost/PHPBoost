<?php
/*##################################################
 *                      NewsletterSubscribersListController.class.php
 *                            -------------------
 *   begin                : March 11, 2011
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

class NewsletterSubscribersListController extends AbstractController
{
	private $lang;
	private $view;
	private $user;
	
	private $nbr_subscribers_per_page = 25;

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form($request);

		return $this->build_response($this->view);
	}

	private function build_form($request)
	{
		$field = $request->get_value('field', 'login');
		$sort = $request->get_value('sort', 'top');
		$current_page = $request->get_int('page', 1);
		
		if (!$this->user->check_auth(NewsletterConfig::load()->get_authorizations(), NewsletterConfig::AUTH_READ_SUBSCRIBERS))
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		$mode = ($sort == 'top') ? 'ASC' : 'DESC';
		
		switch ($field)
		{
			case 'pseudo' :
				$field_bdd = 'login';
			break;
			case 'newsletter' :
				$field_bdd = 'id_cats';
			break;
			default :
				$field_bdd = 'login';
		}
		
		$nbr_subscribers = PersistenceContext::get_sql()->count_table(NewsletterSetup::$newsletter_table_subscribers, __LINE__, __FILE__);
		$nbr_pages =  ceil($nbr_subscribers / $this->nbr_subscribers_per_page);
		$pagination = new Pagination($nbr_pages, $current_page);
		
		$pagination->set_url_sprintf_pattern(DispatchManager::get_url('/newsletter', '/subscribers/list/'. $field .'/'. $sort .'/%d')->absolute());
		$this->view->put_all(array(
			'C_SUBSCRIBERS' => (float)$nbr_subscribers,
			'C_SUBSCRIPTION' => DispatchManager::get_url('/newsletter', '/subscribe/')->absolute(),
			'SORT_PSEUDO_TOP' => DispatchManager::get_url('/newsletter', '/subscribers/list/pseudo/top/'. $current_page)->absolute(),
			'SORT_PSEUDO_BOTTOM' => DispatchManager::get_url('/newsletter', '/subscribers/list/pseudo/bottom/'. $current_page)->absolute(),
			'SORT_NEWSLETTER_NAME_TOP' => DispatchManager::get_url('/newsletter', '/subscribers/list/newsletter/top/'. $current_page)->absolute(),
			'SORT_NEWSLETTER_NAME_BOTTOM' => DispatchManager::get_url('/newsletter', '/subscribers/list/newsletter/bottom/'. $current_page)->absolute(),
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
			$pseudo = $row['user_id'] > 0 ? '<a href="'. DispatchManager::get_url('/member', '/profile/'. $row['user_id'] . '/')->absolute() .'">'. $row['login'] .'</a>' : $this->lang['subscribers.visitor'];
			$this->view->assign_block_vars('subscribers_list', array(
				'EDIT_LINK' => empty($row['user_id']) ? DispatchManager::get_url('/newsletter', '/subscriber/'. $row['id'] .'/edit/')->absolute() : '',
				'DELETE_LINK' => DispatchManager::get_url('/newsletter', '/subscriber/'. $row['id'] .'/delete/')->absolute(),
				'PSEUDO' => $pseudo,
				'NEWSLETTER_NAME' => is_array(unserialize($row['id_cats'])) ? $this->get_name_categories(unserialize($row['id_cats'])) : $this->lang['streams.no_cats'],
				'MAIL' => $row['user_id'] > 0 ? $row['user_mail'] : $row['mail']
			));
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
		$this->view = new FileTemplate('newsletter/NewsletterSubscribersListController.tpl');
		$this->view->add_lang($this->lang);
		$this->user = AppContext::get_user();
	}

	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], PATH_TO_ROOT . '/newsletter/');
		$breadcrumb->add($this->lang['admin.newsletter-subscribers'], DispatchManager::get_url('/newsletter', '/subscribers/list/')->absolute());
		$response->get_graphical_environment()->set_page_title($this->lang['admin.newsletter-subscribers']);
		return $response;
	}
	
	private function get_name_categories(Array $categories)
	{
		$names = array();
		foreach ($categories as $id_cats)
		{
			$row = PersistenceContext::get_querier()->select_single_row(NewsletterSetup::$newsletter_table_streams, array('name'), "WHERE id = '". $id_cats ."'");
			$names[] = $row['name'];
		}
		return implode('/', $names);
	}
}

?>