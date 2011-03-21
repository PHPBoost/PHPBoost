<?php
/*##################################################
 *                      NewsletterArchivesController.class.php
 *                            -------------------
 *   begin                : March 21, 2011
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

class NewsletterArchivesController extends AbstractController
{
	private $lang;
	private $view;
	
	private $nbr_archives_per_page = 25;

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form($request);

		return $this->build_response($this->view);
	}

	private function build_form($request)
	{
		$id_stream = $request->get_int('id_stream', 0);
		$field = $request->get_value('field', 'login');
		$sort = $request->get_value('sort', 'top');
		$current_page = $request->get_int('page', 1);
		$mode = ($sort == 'top') ? 'ASC' : 'DESC';
		
		if (!NewsletterAuthorizationsService::id_stream($id_stream)->read_archives())
		{
			NewsletterAuthorizationsService::get_errors()->read_archives();
		}
		
		if (!NewsletterStreamsCache::load()->get_existed_stream($id_stream))
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), LangLoader::get_message('admin.stream-not-existed', 'newsletter_common', 'newsletter'));
			DispatchManager::redirect($controller);
		}
		
		switch ($field)
		{
			case 'stream' :
				$field_bdd = 'stream_id';
			break;
			case 'subject' :
				$field_bdd = 'subject';
			break;
			case 'date' :
				$field_bdd = 'timestamp';
			break;
			case 'subscribers' :
				$field_bdd = 'nbr_subscribers';
			break;
			default :
				$field_bdd = 'timestamp';
		}
		
		$nbr_archives = PersistenceContext::get_sql()->count_table(NewsletterSetup::$newsletter_table_archives, __LINE__, __FILE__);
		$nbr_pages =  ceil($nbr_archives / $this->nbr_archives_per_page);
		$pagination = new Pagination($nbr_pages, $current_page);
		
		$pagination->set_url_sprintf_pattern(DispatchManager::get_url('/newsletter', '/archives/'. $id_stream .'/'. $field .'/'. $sort .'/%d')->absolute());
		$this->view->put_all(array(
			'C_SUBSCRIBERS' => (float)$nbr_archives,
			'SORT_STREAM_TOP' => DispatchManager::get_url('/newsletter', '/archives/'. $id_stream .'/stream/top/'. $current_page)->absolute(),
			'SORT_STREAM_BOTTOM' => DispatchManager::get_url('/newsletter', '/archives/'. $id_stream .'/stream/bottom/'. $current_page)->absolute(),
			'SORT_SUBJECT_TOP' => DispatchManager::get_url('/newsletter', '/archives/'. $id_stream .'/subject/top/'. $current_page)->absolute(),
			'SORT_SUBJECT_BOTTOM' => DispatchManager::get_url('/newsletter', '/archives/'. $id_stream .'/subject/bottom/'. $current_page)->absolute(),
			'SORT_DATE_TOP' => DispatchManager::get_url('/newsletter', '/archives/'. $id_stream .'/date/top/'. $current_page)->absolute(),
			'SORT_DATE_BOTTOM' => DispatchManager::get_url('/newsletter', '/archives/'. $id_stream .'/date/bottom/'. $current_page)->absolute(),
			'SORT_SUBSCRIBERS_TOP' => DispatchManager::get_url('/newsletter', '/archives/'. $id_stream .'/subscribers/top/'. $current_page)->absolute(),
			'SORT_SUBSCRIBERS_BOTTOM' => DispatchManager::get_url('/newsletter', '/archives/'. $id_stream .'/subscribers/bottom/'. $current_page)->absolute(),
			'PAGINATION' => $pagination->export()->render()
		));

		$limit_page = $current_page > 0 ? $current_page : 1;
		$limit_page = (($limit_page - 1) * $this->nbr_archives_per_page);
		
		$stream_condition = empty($id_stream) ? "" : "WHERE stream_id = '". $id_stream ."'";
		$result = PersistenceContext::get_querier()->select("SELECT *
		FROM " . NewsletterSetup::$newsletter_table_archives . "
		". $stream_condition ."
		ORDER BY ". $field_bdd ." ". $mode ."
		LIMIT ". $this->nbr_archives_per_page ." OFFSET :start_limit",
			array(
				'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		while ($row = $result->fetch())
		{
			$stream_cache = NewsletterStreamsCache::load()->get_stream($row['stream_id']);
			$this->view->assign_block_vars('archives_list', array(
				'STREAM_NAME' => $stream_cache['name'],
				'VIEW_ARCHIVE' => DispatchManager::get_url('/newsletter', '/archive/'. $row['id'])->absolute(),
				'SUBJECT' => $row['subject'],
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'NBR_SUBSCRIBERS' => $row['nbr_subscribers'],
			));
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
		$this->view = new FileTemplate('newsletter/NewsletterArchivesController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['newsletter'], PATH_TO_ROOT . '/newsletter/');
		$breadcrumb->add($this->lang['archives.list'], DispatchManager::get_url('/newsletter', '/archives/')->absolute());
		$response->get_graphical_environment()->set_page_title($this->lang['archives.list']);
		return $response;
	}
}

?>