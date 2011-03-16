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

class NewsletterHomeController extends AbstractController
{
	private $lang;
	private $view;
	private $nbr_streams_per_page = 25;

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form($request);

		return $this->build_response($this->view);
	}

	private function build_form($request)
	{	
		$current_page = $request->get_int('page', 1);
		$nbr_streams = PersistenceContext::get_sql()->count_table(NewsletterSetup::$newsletter_table_streams, __LINE__, __FILE__);
		$nbr_pages =  ceil($nbr_streams / $this->nbr_streams_per_page);
		$pagination = new Pagination($nbr_pages, $current_page);
		
		$pagination->set_url_sprintf_pattern(DispatchManager::get_url('/newsletter', '')->absolute());
		$this->view->put_all(array(
			'C_STREAMS' => (float)$nbr_streams,
			'LINK_SUBSCRIBE' => DispatchManager::get_url('/newsletter', '/subscribe/')->absolute(),
			'LINK_UNSUBSCRIBE' => DispatchManager::get_url('/newsletter', '/unsubscribe/')->absolute(),
			'PAGINATION' => $pagination->export()->render()
		));

		$limit_page = $current_page > 0 ? $current_page : 1;
		$limit_page = (($limit_page - 1) * $this->nbr_streams_per_page);
		
		$result = PersistenceContext::get_querier()->select("SELECT id, name, description, picture, visible, auth
		FROM " . NewsletterSetup::$newsletter_table_streams . "
		LIMIT ". $this->nbr_streams_per_page ." OFFSET :start_limit",
			array(
				'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		while ($row = $result->fetch())
		{
			$auth = is_array($row['auth']) ? AppContext::get_user()->check_auth($row['auth'], NewsletterConfig::CAT_AUTH_READ) : AppContext::get_user()->check_auth(NewsletterConfig::load()->get_authorizations(), NewsletterConfig::AUTH_READ);
			$auth_view_archives = is_array($row['auth']) ? AppContext::get_user()->check_auth($row['auth'], NewsletterConfig::CAT_AUTH_READ_ARCHIVES) : AppContext::get_user()->check_auth(NewsletterConfig::load()->get_authorizations(), NewsletterConfig::AUTH_READ_ARCHIVES);
			if ($auth && $row['visible'] == 1)
			{
				$this->view->assign_block_vars('streams_list', array(
					'PICTURE' => PATH_TO_ROOT . $row['picture'],
					'NAME' => $row['name'],
					'DESCRIPTION' => $row['description'],
					'VIEW_ARCHIVES' => $auth_view_archives ? '<a href="' . DispatchManager::get_url('/newsletter', '/archives/'. $row['id'])->absolute() . '">'. $this->lang['newsletter.view_archives'] .'</a>' : $this->lang['newsletter.not_level']
				));
			}
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
		$this->view = new FileTemplate('newsletter/NewsletterHomeController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], PATH_TO_ROOT . '/newsletter/');
		$breadcrumb->add($this->lang['newsletter.list_newsletters'], DispatchManager::get_url('/newsletter', '')->absolute());
		$response->get_graphical_environment()->set_page_title($this->lang['newsletter.list_newsletters']);
		return $response;
	}
}

?>