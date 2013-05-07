<?php
/*##################################################
 *                      BugtrackerHistoryListController.class.php
 *                            -------------------
 *   begin                : November 11, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class BugtrackerHistoryListController extends ModuleController
{
	private $lang;
	private $view;
	private $bug;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->check_authorizations();
		
		$this->build_view();
		
		return $this->build_response($this->view);
	}

	private function build_view()
	{
		$request = AppContext::get_request();
		
		//Configuration load
		$config = BugtrackerConfig::load();
		$items_per_page = $config->get_items_per_page();
		$types = $config->get_types();
		$categories = $config->get_categories();
		$severities = $config->get_severities();
		$priorities = $config->get_priorities();
		$versions = $config->get_versions();
		$date_format = $config->get_date_form();
		
		$current_page = $request->get_int('page', 1);
		
		//Count history lines number
		$history_lines_number = BugtrackerService::count_history($this->bug->get_id());
		
		$pagination = new BugtrackerListPagination($current_page, $history_lines_number);
		$pagination->set_url(BugtrackerUrlBuilder::history($this->bug->get_id() . '/%d')->absolute());
		
		$this->view->put_all(array(
			'C_HISTORY' 	=> (float)$history_lines_number,
			'PAGINATION'	=> $pagination->display()->render(),
			'RETURN_NAME' 	=> LangLoader::get_message('back', 'main'),
			'LINK_RETURN' 	=> 'javascript:history.back(1);'
		));
		$limit_page = $current_page > 0 ? $current_page : 1;
		$limit_page = (($limit_page - 1) * $items_per_page);
		
		$result = PersistenceContext::get_querier()->select("SELECT *
		FROM " . BugtrackerSetup::$bugtracker_table . " b
		JOIN " . BugtrackerSetup::$bugtracker_history_table . " bh ON (bh.bug_id = b.id)
		JOIN " . DB_TABLE_MEMBER . " a ON (a.user_id = bh.updater_id)
		WHERE b.id = '" . $this->bug->get_id() . "'
		ORDER BY update_date DESC
		LIMIT ". $items_per_page ." OFFSET :start_limit",
			array(
				'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		
		while ($row = $result->fetch())
		{
			switch ($row['updated_field'])
			{
				case 'type': 
					$old_value = !empty($row['old_value']) ? stripslashes($types[$row['old_value']]) : $this->lang['bugs.notice.none'];
					$new_value = !empty($row['new_value']) ? stripslashes($types[$row['new_value']]) : $this->lang['bugs.notice.none'];
					break;
				
				case 'category': 
					$old_value = !empty($row['old_value']) ? stripslashes($categories[$row['old_value']]) : $this->lang['bugs.notice.none_e'];
					$new_value = !empty($row['new_value']) ? stripslashes($categories[$row['new_value']]) : $this->lang['bugs.notice.none_e'];
					break;
				
				case 'severity': 
					$old_value = !empty($row['old_value']) ? stripslashes($severities[$row['old_value']]['name']) : $this->lang['bugs.notice.none'];
					$new_value = !empty($row['new_value']) ? stripslashes($severities[$row['new_value']]['name']) : $this->lang['bugs.notice.none'];
					break;
				
				case 'priority': 
					$old_value = !empty($row['old_value']) ? stripslashes($priorities[$row['old_value']]) : $this->lang['bugs.notice.none_e'];
					$new_value = !empty($row['new_value']) ? stripslashes($priorities[$row['new_value']]) : $this->lang['bugs.notice.none_e'];
					break;
				
				case 'detected_in': 
				case 'fixed_in': 
					$old_value = !empty($row['old_value']) ? stripslashes($versions[$row['old_value']]['name']) : $this->lang['bugs.notice.none_e'];
					$new_value = !empty($row['new_value']) ? stripslashes($versions[$row['new_value']]['name']) : $this->lang['bugs.notice.none_e'];
					break;
				
				case 'status': 
					$old_value = $this->lang['bugs.status.' . $row['old_value']];
					$new_value = $this->lang['bugs.status.' . $row['new_value']];
					break;
				
				case 'reproductible': 
					$old_value = ($row['old_value'] == true) ? LangLoader::get_message('yes', 'main') : LangLoader::get_message('no', 'main');
					$new_value = ($row['new_value'] == true) ? LangLoader::get_message('yes', 'main') : LangLoader::get_message('no', 'main');
					break;
				
				case 'assigned_to_id': 
					$old_user = !empty($row['old_value']) && UserService::user_exists("WHERE user_aprob = 1 AND user_id=:user_id", array('user_id' => $row['old_value'])) ? UserService::get_user("WHERE user_aprob = 1 AND user_id=:user_id", array('user_id' => $row['old_value'])) : '';
					$new_user = !empty($row['new_value']) && UserService::user_exists("WHERE user_aprob = 1 AND user_id=:user_id", array('user_id' => $row['new_value'])) ? UserService::get_user("WHERE user_aprob = 1 AND user_id=:user_id", array('user_id' => $row['new_value'])) : '';
					$old_value = !empty($old_user) ? '<a href="' . UserUrlBuilder::profile($row['old_value'])->absolute() . '" class="' . UserService::get_level_class($old_user->get_level()) . '">' . $old_user->get_pseudo() . '</a>' : $this->lang['bugs.notice.no_one'];
					$new_value = !empty($new_user) ? '<a href="' . UserUrlBuilder::profile($row['new_value'])->absolute() . '" class="' . UserService::get_level_class($new_user->get_level()) . '">' . $new_user->get_pseudo() . '</a>' : $this->lang['bugs.notice.no_one'];
					break;
				
				default:
					$old_value = $row['old_value'];
					$new_value = $row['new_value'];
			}
			
			$this->view->assign_block_vars('history', array(
				'UPDATED_FIELD'	=> (!empty($row['updated_field']) ? $this->lang['bugs.labels.fields.' . $row['updated_field']] : $this->lang['bugs.notice.none']),
				'OLD_VALUE'		=> stripslashes($old_value),
				'NEW_VALUE'		=> stripslashes($new_value),
				'COMMENT'		=> $row['change_comment'],
				'UPDATER' 		=> !empty($row['login']) ? '<a href="' . UserUrlBuilder::profile($row['user_id'])->absolute() . '" class="' . UserService::get_level_class($row['level']) . '">' . $row['login'] . '</a>': LangLoader::get_message('guest', 'main'),
				'DATE' 			=> gmdate_format($date_format, $row['update_date'])
			));
		}
	}
	
	private function init()
	{
		$request = AppContext::get_request();
		$id = $request->get_int('id', 0);
		
		try {
			$this->bug = BugtrackerService::get_bug('WHERE id=:id', array('id' => $id));
		} catch (RowNotFoundException $e) {
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('bugs.error.e_unexist_bug', 'bugtracker_common', 'bugtracker'));
			DispatchManager::redirect($controller);
		}
		
		$this->lang = LangLoader::get('bugtracker_common', 'bugtracker');
		$this->view = new FileTemplate('bugtracker/BugtrackerHistoryListController.tpl');
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
	
	private function build_response(View $view)
	{
		$request = AppContext::get_request();
		$success = $request->get_value('success', '');
		
		$body_view = BugtrackerViews::build_body_view($view, 'history', $this->bug->get_id());
		
		//Success messages
		switch ($success)
		{
			case 'add':
				$errstr = sprintf($this->lang['bugs.success.add'], $this->bug->get_id());
				break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$body_view->put('MSG', MessageHelper::display($errstr, E_USER_SUCCESS, 5));
		
		$response = new BugtrackerDisplayResponse();
		$response->add_breadcrumb_link($this->lang['bugs.module_title'], BugtrackerUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['bugs.titles.history_bug'] . ' #' . $this->bug->get_id(), BugtrackerUrlBuilder::history($this->bug->get_id()));
		$response->set_page_title($this->lang['bugs.titles.history_bug'] . ' #' . $this->bug->get_id());
		
		return $response->display($body_view);
	}
}
?>
