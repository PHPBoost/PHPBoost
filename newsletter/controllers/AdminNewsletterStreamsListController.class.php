<?php
/*##################################################
 *                      AdminNewsletterStreamsListController.class.php
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

class AdminNewsletterStreamsListController extends AbstractController
{
	private $lang;
	private $view;
	private $user;
	
	private $nbr_categories_per_page = 25;

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
			case 'name' :
				$field_bdd = 'name';
			break;
			case 'status' :
				$field_bdd = 'visible';
			break;
			default :
				$field_bdd = 'name';
		}
		
		$nbr_cats = PersistenceContext::get_sql()->count_table(NewsletterSetup::$newsletter_table_streams, __LINE__, __FILE__);
		$nbr_pages =  ceil($nbr_cats / $this->nbr_categories_per_page);
		$pagination = new Pagination($nbr_pages, $current_page);
		
		$pagination->set_url_sprintf_pattern(DispatchManager::get_url('/newsletter', '/subscribers/list/'. $field .'/'. $sort .'/%d')->absolute());
		$this->view->put_all(array(
			'C_CATEGORIES_EXIST' => (float)$nbr_cats,
			'C_ADD_CATEGORIE' => DispatchManager::get_url('/newsletter', '/admin/stream/add/')->absolute(),
			'SORT_NAME_TOP' => DispatchManager::get_url('/newsletter', '/admin/categories/list/name/top/'. $current_page)->absolute(),
			'SORT_NAME_BOTTOM' => DispatchManager::get_url('/newsletter', '/admin/categories/list/name/bottom/'. $current_page)->absolute(),
			'SORT_STATUS_TOP' => DispatchManager::get_url('/newsletter', '/admin/categories/list/status/top/'. $current_page)->absolute(),
			'SORT_STATUS_BOTTOM' => DispatchManager::get_url('/newsletter', '/admin/categories/list/status/bottom/'. $current_page)->absolute(),
			'PAGINATION' => $pagination->export()->render()
		));

		$limit_page = $current_page > 0 ? $current_page : 1;
		$limit_page = (($limit_page - 1) * $this->nbr_categories_per_page);
		
		$result = PersistenceContext::get_querier()->select("SELECT cat.id, cat.name, cat.description, cat.visible
		FROM " . NewsletterSetup::$newsletter_table_streams . " cat
		ORDER BY ". $field_bdd ." ". $mode ."
		LIMIT ". $this->nbr_categories_per_page ." OFFSET :start_limit",
			array(
				'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		while ($row = $result->fetch())
		{	
			$this->view->assign_block_vars('categories_list', array(
				'EDIT_LINK' => DispatchManager::get_url('/newsletter', '/admin/stream/'. $row['id'] .'/edit/')->absolute(),
				'DELETE_LINK' => DispatchManager::get_url('/newsletter', '/admin/stream/'. $row['id'] .'/delete/')->absolute(),
				'NAME' => $row['name'],
				'DESCRIPTION' => $row['description'],
				'STATUS' => !$row['visible'] ? $this->lang['streams.visible-no'] : $this->lang['streams.visible-yes']
			));
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
		$this->view = new FileTemplate('newsletter/AdminNewsletterStreamsListController.tpl');
		$this->view->add_lang($this->lang);
		$this->user = AppContext::get_user();
	}

	private function build_response(View $view)
	{
		$response = new AdminMenuDisplayResponse($view);
		$response->set_title($this->lang['newsletter']);
		$response->add_link($this->lang['admin.newsletter-subscribers'], DispatchManager::get_url('/newsletter', '/subscribers/list'), '/newsletter/newsletter.png');
		$response->add_link($this->lang['admin.newsletter-archives'], DispatchManager::get_url('/newsletter', '/archives'), '/newsletter/newsletter.png');
		$response->add_link($this->lang['admin.newsletter-streams'], DispatchManager::get_url('/newsletter', '/admin/streams/list'), '/newsletter/newsletter.png');
		$response->add_link($this->lang['admin.newsletter-config'], DispatchManager::get_url('/newsletter', '/admin/config'), '/newsletter/newsletter.png');

		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['streams.add']);
		return $response;
	}
}

?>