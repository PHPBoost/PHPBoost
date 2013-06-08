<?php
/*##################################################
 *                      BugtrackerDetailController.class.php
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

class BugtrackerDetailController extends ModuleController
{
	private $lang;
	private $view;
	private $bug;
	private $current_user;
	
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
		$authorizations = $config->get_authorizations();
		$comments_activated = $config->get_comments_activated();
		$cat_in_title_activated = $config->get_cat_in_title_activated();
		$types = $config->get_types();
		$categories = $config->get_categories();
		$display_categories = sizeof($categories) > 1 ? true : false;
		$severities = $config->get_severities();
		$priorities = $config->get_priorities();
		$versions = $config->get_versions();
		$date_format = $config->get_date_form();
		$auth_moderate = BugtrackerAuthorizationsService::check_authorizations()->moderation();
		
		$main_lang = LangLoader::get('main');
		
		$author = $this->bug->get_author_user();
		$author_group_color = User::get_group_color($author->get_groups(), $author->get_level(), true);
		
		switch ($this->bug->get_status())
		{
			case 'new' :
			case 'assigned' :
			case 'reopen' :
				$c_reopen = false;
				$c_reject = true;
				break;
			case 'fixed' :
			case 'rejected' :
				$c_reopen = true;
				$c_reject = false;
				break;
			default :
				$c_reopen = false;
				$c_reject = true;
		}
		
		if ($auth_moderate || ($this->current_user->get_id() == $this->bug->get_author_user()->get_id() && $this->bug->get_author_user()->get_id() != -1) || ($this->bug->get_assigned_to_id() && $this->current_user->get_id() == $this->bug->get_assigned_to_id()))
		{
			$this->view->put_all(array(
				'C_EDIT_BUG'=> true,
				'L_UPDATE'	=> $main_lang['update']
			));
		}
		
		if ($auth_moderate)
		{
			$this->view->put_all(array(
				'C_REOPEN_BUG'	=> $c_reopen,
				'C_REJECT_BUG'	=> $c_reject,
				'C_HISTORY_BUG'	=> true,
				'C_DELETE_BUG'	=> true,
				'L_DELETE' 		=> $main_lang['delete']
			));
		}
		
		$this->view->put_all(array(
			'C_TYPES' 					=> $types ? true : false,
			'C_CATEGORIES' 				=> $categories ? true : false,
			'C_SEVERITIES' 				=> $severities ? true : false,
			'C_PRIORITIES' 				=> $priorities ? true : false,
			'C_VERSIONS' 				=> $versions ? true : false,
			'C_FIXED_IN' 				=> $this->bug->get_detected_in() ? true : false,
			'C_COMMENT_BUG'				=> $comments_activated ? true : false,
			'C_REPRODUCTIBLE' 			=> ($this->bug->is_reproductible() && $this->bug->get_reproduction_method()) ? true : false,
			'L_GUEST' 					=> $main_lang['guest'],
			'L_ON' 						=> $main_lang['on'],
			'RETURN_NAME' 				=> $main_lang['back'],
			'LINK_BUG_REJECT'			=> BugtrackerUrlBuilder::reject($this->bug->get_id(), 'detail')->absolute(),
			'LINK_BUG_REOPEN'			=> BugtrackerUrlBuilder::reopen($this->bug->get_id(), 'detail')->absolute(),
			'LINK_BUG_EDIT'				=> BugtrackerUrlBuilder::edit($this->bug->get_id() . '/detail')->absolute(),
			'LINK_BUG_HISTORY'			=> BugtrackerUrlBuilder::history($this->bug->get_id())->absolute(),
			'LINK_BUG_DELETE'			=> BugtrackerUrlBuilder::delete($this->bug->get_id(), 'unsolved')->absolute(),
			'LINK_RETURN' 				=> 'javascript:history.back(1);'
		));
		
		$user_assigned = $this->bug->get_assigned_to_id() ? UserService::get_user('WHERE user_aprob = 1 AND user_id=:user_id', array('user_id' => $this->bug->get_assigned_to_id())) : '';
		$user_assigned_group_color = $this->bug->get_assigned_to_id() ? User::get_group_color($user_assigned->get_groups(), $user_assigned->get_level(), true) : '';
		
		$this->view->put_all(array(
			'C_AUTHOR_GROUP_COLOR'			=> !empty($user_assigned_group_color),
			'C_USER_ASSIGNED_GROUP_COLOR'	=> !empty($author_group_color),
			'ID'							=> $this->bug->get_id(),
			'TITLE'							=> ($cat_in_title_activated == true && $display_categories) ? '[' . $categories[$this->bug->get_category()] . '] ' . $this->bug->get_title() : $this->bug->get_title(),
			'CONTENTS'						=> FormatingHelper::second_parse($this->bug->get_contents()),
			'STATUS'						=> $this->lang['bugs.status.' . $this->bug->get_status()],
			'TYPE'							=> (isset($types[$this->bug->get_type()])) ? stripslashes($types[$this->bug->get_type()]) : $this->lang['bugs.notice.none'],
			'CATEGORY'						=> (isset($categories[$this->bug->get_category()])) ? stripslashes($categories[$this->bug->get_category()]) : $this->lang['bugs.notice.none_e'],
			'PRIORITY'						=> (isset($priorities[$this->bug->get_priority()])) ? stripslashes($priorities[$this->bug->get_priority()]) : $this->lang['bugs.notice.none_e'],
			'SEVERITY'						=> (isset($severities[$this->bug->get_severity()])) ? stripslashes($severities[$this->bug->get_severity()]['name']) : $this->lang['bugs.notice.none'],
			'REPRODUCTIBLE'					=> $this->bug->is_reproductible() ? $main_lang['yes'] : $main_lang['no'],
			'REPRODUCTION_METHOD'			=> FormatingHelper::second_parse($this->bug->get_reproduction_method()),
			'DETECTED_IN' 					=> (isset($versions[$this->bug->get_detected_in()])) ? stripslashes($versions[$this->bug->get_detected_in()]['name']) : $this->lang['bugs.notice.not_defined'],
			'FIXED_IN'						=> (isset($versions[$this->bug->get_fixed_in()])) ? stripslashes($versions[$this->bug->get_fixed_in()]['name']) : $this->lang['bugs.notice.not_defined'],
			'USER_ASSIGNED'					=> $user_assigned,
			'SUBMIT_DATE'					=> gmdate_format($date_format, $this->bug->get_submit_date()),
			'AUTHOR'						=> $author->get_pseudo(),
			'AUTHOR_LEVEL_CLASS'			=> UserService::get_level_class($author->get_level()),
			'AUTHOR_GROUP_COLOR'			=> $author_group_color,
			'USER_ASSIGNED'					=> $user_assigned ? $user_assigned->get_pseudo() : '',
			'USER_ASSIGNED_LEVEL_CLASS'		=> $user_assigned ? UserService::get_level_class($user_assigned->get_level()) : '',
			'USER_ASSIGNED_GROUP_COLOR'		=> $user_assigned_group_color,
			'LINK_AUTHOR_PROFILE'			=> UserUrlBuilder::profile($author->get_id())->absolute(),
			'LINK_USER_ASSIGNED_PROFILE'	=> $user_assigned ? UserUrlBuilder::profile($user_assigned->get_id())->absolute() : '',
		));
		
		//Comments display
		if ($comments_activated && is_numeric($this->bug->get_id()))
		{
			$comments_topic = new BugtrackerCommentsTopic();
			$comments_topic->set_id_in_module($this->bug->get_id());
			$comments_topic->set_url(new Url(BugtrackerUrlBuilder::detail($this->bug->get_id())->absolute()));
			$this->view->put_all(array(
				'COMMENTS' => CommentsService::display($comments_topic)->render()
			));
		}
	}
	
	private function init()
	{
		$this->current_user = AppContext::get_current_user();
		$request = AppContext::get_request();
		$id = $request->get_int('id', 0);
		
		$this->lang = LangLoader::get('bugtracker_common', 'bugtracker');
		
		try {
			$this->bug = BugtrackerService::get_bug('WHERE id=:id', array('id' => $id));
		} catch (RowNotFoundException $e) {
			$error_controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), $this->lang['bugs.error.e_unexist_bug']);
			DispatchManager::redirect($error_controller);
		}
		
		$this->view = new FileTemplate('bugtracker/BugtrackerDetailController.tpl');
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
		
		$body_view = BugtrackerViews::build_body_view($view, 'detail', $this->bug->get_id());
		
		//Success messages
		switch ($success)
		{
			case 'add':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.add'], array('id' => $this->bug->get_id()));
				break;
			case 'edit':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.edit'], array('id' => $this->bug->get_id()));
				break;
			case 'fixed':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.fixed'], array('id' => $this->bug->get_id()));
				break;
			case 'delete':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.delete'], array('id' => $this->bug->get_id()));
				break;
			case 'reject':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.reject'], array('id' => $this->bug->get_id()));
				break;
			case 'reopen':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.reopen'], array('id' => $this->bug->get_id()));
				break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$body_view->put('MSG', MessageHelper::display($errstr, E_USER_SUCCESS, 5));
		
		$response = new BugtrackerDisplayResponse();
		$response->add_breadcrumb_link($this->lang['bugs.module_title'], BugtrackerUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['bugs.titles.view_bug'] . ' #' . $this->bug->get_id(), BugtrackerUrlBuilder::detail($this->bug->get_id()));
		$response->set_page_title($this->lang['bugs.titles.view_bug'] . ' #' . $this->bug->get_id());
		
		return $response->display($body_view);
	}
}
?>
