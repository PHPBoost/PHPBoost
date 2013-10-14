<?php
/*##################################################
 *                      BugtrackerSolvedListController.class.php
 *                            -------------------
 *   begin                : November 13, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class BugtrackerSolvedListController extends ModuleController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->check_authorizations();
		
		$this->build_view($request);
		
		return $this->build_response($this->view);
	}

	private function build_view($request)
	{
		//Configuration load
		$config = BugtrackerConfig::load();
		$types = $config->get_types();
		$categories = $config->get_categories();
		$severities = $config->get_severities();
		$versions = $config->get_versions();
		$rejected_bug_color = $config->get_rejected_bug_color();
		$fixed_bug_color = $config->get_fixed_bug_color();
		
		$display_types = sizeof($types) > 1 ? true : false;
		$display_categories = sizeof($categories) > 1 ? true : false;
		$display_severities = sizeof($severities) > 1 ? true : false;
		$display_versions = sizeof($versions) > 1 ? true : false;
		
		$field = $request->get_value('field', 'date');
		$sort = $request->get_value('sort', 'desc');
		$current_page = $request->get_getint('page', 1);
		$filter = $request->get_value('filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		if (!empty($filter) && empty($filter_id))
		{
			$filter = $filter_id = '';
		}
		
		$filters = !empty($filter) ? explode('-', $filter) : array();
		$filters_ids = !empty($filter_id) ? explode('-', $filter_id) : array();
		
		$mode = ($sort == 'top') ? 'ASC' : 'DESC';
		
		switch ($field)
		{
			case 'id' :
				$field_bdd = 'id';
				break;
			case 'title' :
				$field_bdd = 'title';
				break;
			case 'status' :
				$field_bdd = 'status';
				break;
			default :
				$field_bdd = 'fix_date';
				break;
		}
		
		$select_filters = '';
		foreach ($filters as $key => $f)
		{
			$select_filters .= in_array($f, array('type', 'category', 'severity', 'status', 'fixed_in')) && $filters_ids[$key] ? "AND " . $f . " = '" . $filters_ids[$key] . "'" : '';
		}
		
		$stats_cache = BugtrackerStatsCache::load();
		$bugs_number = !empty($select_filters) ? BugtrackerService::count("WHERE (status = 'fixed' OR status = 'rejected')" . $select_filters) : $stats_cache->get_bugs_number(Bug::FIXED) + $stats_cache->get_bugs_number(Bug::REJECTED);
		
		$pagination = $this->get_pagination($bugs_number, $current_page, $field, $sort, $filter, $filter_id);
		
		$result = PersistenceContext::get_querier()->select("SELECT b.*, com.number_comments
		FROM " . BugtrackerSetup::$bugtracker_table . " b
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = b.id AND com.module_id = 'bugtracker'
		WHERE (status = 'fixed' OR status = 'rejected')" .
		$select_filters . "
		ORDER BY " . $field_bdd . " " . $mode . "
		LIMIT :number_items_per_page OFFSET :display_from",
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);
		
		$displayed_status = array();
		
		while ($row = $result->fetch())
		{
			if (!in_array($row['status'], $displayed_status)) $displayed_status[] = $row['status'];
			
			$this->view->assign_block_vars('bug', array(
				'C_LINE_COLOR'				=> true,
				'C_PROGRESS'				=> $config->is_progress_bar_displayed() && $row['progress'],
				'ID'						=> $row['id'],
				'TITLE'						=> ($config->is_cat_in_title_displayed() && $display_categories) ? '[' . $categories[$row['category']] . '] ' . $row['title'] : $row['title'],
				'LINE_COLOR' 				=> $row['status'] == Bug::FIXED ? $fixed_bug_color : $rejected_bug_color,
				'PROGRESS' 					=> $row['progress'],
				'STATUS'					=> $this->lang['bugs.labels.fields.status'] . ' : ' . $this->lang['bugs.status.' . $row['status']],
				'NUMBER_COMMENTS'			=> (int) $row['number_comments'],
				'L_COMMENTS'				=> $row['number_comments'] <= 1 ? LangLoader::get_message('comment', 'comments-common') : LangLoader::get_message('comments', 'comments-common'),
				'DATE' 						=> !empty($row['fix_date']) ? gmdate_format($config->get_date_form(), $row['fix_date']) : $this->lang['bugs.labels.not_yet_fixed'],
				'LINK_BUG_DETAIL'			=> BugtrackerUrlBuilder::detail($row['id'] . '/' . Url::encode_rewrite($row['title']))->rel(),
				'LINK_BUG_REOPEN_REJECT'	=> BugtrackerUrlBuilder::reopen($row['id'], 'solved', $current_page, (!empty($filter) ? $filter : ''), (!empty($filter) ? $filter_id : ''))->rel(),
				'LINK_BUG_EDIT'				=> BugtrackerUrlBuilder::edit($row['id'] . '/solved/' . $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->rel(),
				'LINK_BUG_HISTORY'			=> BugtrackerUrlBuilder::history($row['id'])->rel(),
				'LINK_BUG_DELETE'			=> BugtrackerUrlBuilder::delete($row['id'], 'solved', $current_page, (!empty($filter) ? $filter : ''), (!empty($filter) ? $filter_id : ''))->rel(),
				'LINK_COMMENTS'				=> BugtrackerUrlBuilder::detail($row['id'] . '/#comments_list')->rel()
			));
		}
		
		$this->view->put_all(array(
			'C_IS_ADMIN'				=> BugtrackerAuthorizationsService::check_authorizations()->moderation(),
			'C_BUGS' 					=> $result->get_rows_count() > 0,
			'C_COMMENTS'				=> $config->are_comments_enabled(),
			'C_PAGINATION'				=> $pagination->has_several_pages(),
			'PAGINATION' 				=> $pagination->display(),
			'BUGS_COLSPAN' 				=> BugtrackerAuthorizationsService::check_authorizations()->moderation() ? 5 : 4,
			'L_NO_BUG' 					=> empty($filters) ? $this->lang['bugs.notice.no_bug_solved'] : (sizeof($filters) > 1 ? $this->lang['bugs.notice.no_bug_matching_filters'] : $this->lang['bugs.notice.no_bug_matching_filter']),
			'L_DATE'					=> $this->lang['bugs.labels.fields.fix_date'],
			'L_REOPEN_REJECT'			=> $this->lang['bugs.actions.reopen'],
			'PICT_REOPEN_REJECT'		=> 'visible.png',
			'REOPEN_REJECT_CONFIRM'		=> 'reopen',
			'FILTER_LIST'				=> BugtrackerViews::build_filters('solved', $bugs_number),
			'PROGRESS_BAR'				=> BugtrackerViews::build_progress_bar(),
			'LEGEND'					=> BugtrackerViews::build_legend($displayed_status, 'solved'),
			'LINK_BUG_ID_TOP' 			=> BugtrackerUrlBuilder::solved('id/top/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->rel(),
			'LINK_BUG_ID_BOTTOM' 		=> BugtrackerUrlBuilder::solved('id/bottom/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->rel(),
			'LINK_BUG_TITLE_TOP' 		=> BugtrackerUrlBuilder::solved('title/top/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->rel(),
			'LINK_BUG_TITLE_BOTTOM' 	=> BugtrackerUrlBuilder::solved('title/bottom/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->rel(),
			'LINK_BUG_STATUS_TOP'		=> BugtrackerUrlBuilder::solved('status/top/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->rel(),
			'LINK_BUG_STATUS_BOTTOM'	=> BugtrackerUrlBuilder::solved('status/bottom/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->rel(),
			'LINK_BUG_DATE_TOP' 		=> BugtrackerUrlBuilder::solved('date/top/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->rel(),
			'LINK_BUG_DATE_BOTTOM' 		=> BugtrackerUrlBuilder::solved('date/bottom/'. $current_page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->rel()
		));
		
		return $this->view;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'bugtracker');
		$this->view = new FileTemplate('bugtracker/BugtrackerListController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function check_authorizations()
	{
		if (!BugtrackerAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function get_pagination($bugs_number, $page, $field, $sort, $filter, $filter_id)
	{
		$pagination = new ModulePagination($page, $bugs_number, (int)BugtrackerConfig::load()->get_items_per_page());
		$pagination->set_url(BugtrackerUrlBuilder::solved($field . '/' . $sort . '/%d' . (!empty($filter) ? '/' . $filter . '/' . $filter_id : '')));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function build_response(View $view)
	{
		$request = AppContext::get_request();
		$success = $request->get_value('success', '');
		$bug_id = $request->get_int('id', 0);
		
		$body_view = BugtrackerViews::build_body_view($view, 'solved');
		
		//Success messages
		switch ($success)
		{
			case 'add':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.add'], array('id' => $bug_id));
				break;
			case 'edit':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.edit'], array('id' => $bug_id));
				break;
			case 'fixed':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.fixed'], array('id' => $bug_id));
				break;
			case 'delete':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.delete'], array('id' => $bug_id));
				break;
			case 'reopen':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.reopen'], array('id' => $bug_id));
				break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$body_view->put('MSG', MessageHelper::display($errstr, E_USER_SUCCESS, 5));
		
		$response = new BugtrackerDisplayResponse();
		$response->add_breadcrumb_link($this->lang['bugs.module_title'], BugtrackerUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['bugs.titles.solved'], BugtrackerUrlBuilder::solved());
		$response->set_page_title($this->lang['bugs.titles.solved']);
		
		return $response->display($body_view);
	}
}
?>
